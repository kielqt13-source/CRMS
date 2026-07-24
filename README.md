# AI-Based Handwritten and Typewritten Text Recognition System

An AI-powered web application designed to assist the **Local Civil Registrar (LCR) Office of Maasin City** in digitizing legacy civil registry documents.

## About
This project combines OCR, Handwritten Text Recognition (HTR), and human verification to convert scanned birth, marriage, and death certificates into structured digital records.

## Features
- User authentication
- Single and batch document processing
- PDF upload
- Image preprocessing
- Handwritten (TrOCR) and typewritten (PaddleOCR) recognition
- Human verification
- Duplicate detection
- Analytics dashboard
- Audit logs

## Technology Stack
- Laravel, PHP, MySQL
- Python, FastAPI
- TrOCR, PaddleOCR
- OpenCV, pdf2image, Poppler
- Bootstrap, HTML, CSS, JavaScript

## AI Workflow
1. Upload document
2. Convert PDF to images
3. Preprocess images
4. Recognize text
5. Map fields
6. Human verification
7. Save verified record

## Installation

```bash
git clone https://github.com/yourusername/your-repository.git
```

### Laravel
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

### FastAPI (not yet final)
```bash
python -m venv venv
pip install -r requirements.txt
uvicorn main:app --reload
```

## Developers
- Michael Gono
- Miro C. Lim
- Harold Anthony P. Piodos
- Ziv Llagane C. Gil

## License
Academic Capstone Project.
