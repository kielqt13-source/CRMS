"""
predict_single.py
Predicts handwritten text from a single image, PDF, or Word file using TrOCR model.

Usage:
  python predict_single.py <file_path> [model_dir]
"""

import os
import sys
import warnings
import logging
import json
import math
import tempfile
from pathlib import Path

# --- Suppress warnings ---
os.environ["HF_HUB_DISABLE_SYMLINKS_WARNING"] = "1"
os.environ["HF_HUB_DISABLE_IMPLICIT_TOKEN"] = "1"
os.environ["TRANSFORMERS_NO_ADVISORY_WARNINGS"] = "1"
os.environ["TRANSFORMERS_VERBOSITY"] = "error"
warnings.filterwarnings("ignore")
logging.disable(logging.WARNING)

import torch
from PIL import Image
from transformers import TrOCRProcessor, VisionEncoderDecoderModel

# --- Try to import PDF and Word handling libraries
try:
    from pdf2image import convert_from_path
    HAS_PDF2IMAGE = True
except ImportError:
    HAS_PDF2IMAGE = False

try:
    from docx import Document
    from docx2pdf import convert as docx_to_pdf
    HAS_DOCX = True
except ImportError:
    HAS_DOCX = False

# ============================================================
# CONFIG
# ============================================================
DEFAULT_MODEL_DIR = "microsoft/trocr-base-handwritten"  # Use base model if no fine-tuned model exists
# ============================================================

def process_file(file_path):
    """Process any supported file (image, PDF, DOC/DOCX) into a list of PIL Images"""
    ext = Path(file_path).suffix.lower()
    
    if ext in [".jpg", ".jpeg", ".png", ".gif", ".svg", ".bmp"]:
        # Single image
        return [Image.open(file_path).convert("RGB")]
    elif ext == ".pdf":
        if not HAS_PDF2IMAGE:
            raise Exception("pdf2image not installed. Please install it with: pip install pdf2image")
        # Convert PDF to images
        return convert_from_path(file_path)
    elif ext in [".doc", ".docx"]:
        if not HAS_DOCX:
            raise Exception("python-docx and docx2pdf not installed. Please install with: pip install python-docx docx2pdf")
        # Convert DOCX to PDF first, then to images
        with tempfile.TemporaryDirectory() as temp_dir:
            temp_pdf_path = os.path.join(temp_dir, "temp.pdf")
            docx_to_pdf(file_path, temp_pdf_path)
            return convert_from_path(temp_pdf_path)
    else:
        raise Exception(f"Unsupported file type: {ext}")


def predict_single_image(image, model, processor, device):
    """Predict text for a single PIL image and return (text, confidence)"""
    pixel_values = processor(images=image, return_tensors="pt").pixel_values.to(device)

    with torch.no_grad():
        gen_output = model.generate(
            pixel_values,
            max_new_tokens=32,
            output_scores=True,
            return_dict_in_generate=True,
        )

    generated_ids = gen_output.sequences
    predicted_text = processor.batch_decode(generated_ids, skip_special_tokens=True)[0].strip()

    confidence = 0.0
    try:
        scores = model.compute_transition_scores(
            gen_output.sequences, gen_output.scores, normalize_logits=True
        )[0]
        gen_tokens = gen_output.sequences[0][1:1+len(scores)]
        log_probs = []
        for tok, lp in zip(gen_tokens, scores):
            if not torch.isfinite(lp):
                continue
            log_probs.append(lp.item())
            if tok.item() == (getattr(model.generation_config, "eos_token_id", None) or
                             getattr(model.config, "eos_token_id", None) or
                             processor.tokenizer.sep_token_id):
                break
        if log_probs:
            confidence = math.exp(sum(log_probs) / len(log_probs)) * 100.0
    except Exception:
        pass

    return predicted_text, confidence


def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Missing file path argument"}))
        return

    file_path = sys.argv[1]
    model_dir = sys.argv[2] if len(sys.argv) > 2 else DEFAULT_MODEL_DIR

    # Check if model_dir is a local path that exists; otherwise treat as Hugging Face name
    looks_like_local_path = os.path.sep in model_dir or model_dir.startswith(".")
    is_hf_name = "/" in model_dir and not looks_like_local_path
    if not os.path.isdir(model_dir) and not is_hf_name:
        model_dir = DEFAULT_MODEL_DIR

    try:
        # --- Device ---
        device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

        # --- Load model ---
        processor = TrOCRProcessor.from_pretrained(model_dir)
        model = VisionEncoderDecoderModel.from_pretrained(model_dir)
        model.to(device)
        model.eval()

        # --- Process file into images ---
        images = process_file(file_path)

        all_texts = []
        all_confidences = []

        for img in images:
            text, conf = predict_single_image(img, model, processor, device)
            all_texts.append(text)
            all_confidences.append(conf)

        full_text = "\n\n".join(all_texts)
        avg_confidence = sum(all_confidences)/len(all_confidences) if all_confidences else 0.0

        # --- Output result ---
        print(json.dumps({
            "success": True,
            "text": full_text,
            "confidence": round(avg_confidence, 1),
            "page_count": len(images)
        }))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "error": str(e)
        }))


if __name__ == "__main__":
    main()
