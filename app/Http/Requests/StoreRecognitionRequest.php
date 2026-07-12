<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecognitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'          => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx', 'max:20480'],
            'document_type' => ['required', 'in:Birth Certificate,Marriage Certificate,Death Certificate'],
        ];
    }
}
