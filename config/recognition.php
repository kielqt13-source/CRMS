<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Handwritten Recognition Service
    |--------------------------------------------------------------------------
    |
    | This value is the URL of the Python-based API that performs the
    | actual handwriting recognition.
    |
    */
    'api_url' => env('RECOGNITION_API_URL', 'http://127.0.0.1:5000/predict'),
];