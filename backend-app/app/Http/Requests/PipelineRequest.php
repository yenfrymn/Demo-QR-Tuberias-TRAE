<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PipelineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qr_code' => ['required', 'string'],
            'name' => ['required', 'string'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'diameter' => ['nullable', 'string'],
            'material' => ['nullable', 'string'],
            'installation_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'description' => ['nullable', 'string'],
        ];
    }
}