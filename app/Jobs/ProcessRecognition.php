<?php

namespace App\Jobs;

use App\Models\Recognition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessRecognition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The recognition instance.
     *
     * @var \App\Models\Recognition
     */
    public $recognition;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Recognition $recognition
     * @return void
     */
    public function __construct(Recognition $recognition)
    {
        $this->recognition = $recognition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = $this->recognition->file_path;
        $fullFilePath = Storage::disk('public')->path($filePath);
        
        if (!Storage::disk('public')->exists($filePath)) {
            $this->failAndLog('File not found at path: ' . $filePath);
            return;
        }

        try {
            $pythonScriptPath = base_path('resources/ml/predict_single.py');
            $modelDir = base_path('resources/ml/trocr-finetuned');
            if (!is_dir($modelDir)) {
                $modelDir = 'microsoft/trocr-base-handwritten'; // Use base model if fine-tuned not found
            }

            // Build the command
            $command = escapeshellcmd('python') . ' ' . escapeshellarg($pythonScriptPath) . ' ' . escapeshellarg($fullFilePath) . ' ' . escapeshellarg($modelDir);
            
            // Execute the command
            $output = shell_exec($command . ' 2>&1');
            
            if ($output === null) {
                $this->failAndLog('Failed to execute Python script.', ['command' => $command]);
                return;
            }

            $result = json_decode($output, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->failAndLog('Invalid JSON output from Python script.', ['output' => $output]);
                return;
            }

            if (isset($result['success']) && $result['success']) {
                $this->recognition->update([
                    'status' => 'completed',
                    'recognized_text' => $result['text'],
                    'confidence' => $result['confidence'] ?? null,
                    'api_response' => $result,
                ]);
            } else {
                $this->failAndLog($result['error'] ?? 'Unknown error from Python script.', $result);
            }
        } catch (Throwable $e) {
            $this->failAndLog('Failed to run recognition.', [
                'exception_message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mark the job as failed and log the reason.
     */
    protected function failAndLog(string $message, array $context = []): void
    {
        Log::error($message, array_merge($context, [
            'recognition_id' => $this->recognition->id,
        ]));

        $this->recognition->update([
            'status' => 'failed',
            'api_response' => ['error' => $message],
        ]);
    }
}