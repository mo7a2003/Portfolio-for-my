<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data for validation and harmonize aliases.
     */
    protected function prepareForValidation(): void
    {
        $mergeData = [];

        // 1. Title / Name
        $title = $this->input('title') ?? $this->input('name');
        if ($title !== null && $title !== '') {
            $mergeData['title'] = trim((string) $title);
        } elseif ($this->isMethod('post') && ! $this->has('title')) {
            $uploadedFile = $this->file('file') ?? $this->file('cv') ?? $this->file('document');
            if ($uploadedFile) {
                $nameWithoutExt = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $mergeData['title'] = trim(str_replace(['_', '-'], ' ', $nameWithoutExt)) ?: 'Mohammed Alhwaiti - CV';
            } else {
                $mergeData['title'] = 'Mohammed Alhwaiti - CV';
            }
        }

        // 2. is_active / isActive / active
        if ($this->has('is_active') || $this->has('isActive') || $this->has('active')) {
            $rawActive = $this->input('is_active') ?? $this->input('isActive') ?? $this->input('active');
            $mergeData['is_active'] = filter_var($rawActive, FILTER_VALIDATE_BOOLEAN);
        } elseif ($this->isMethod('post')) {
            $mergeData['is_active'] = true;
        }

        if (! empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCreate = $this->isMethod('post') && ! $this->route('cvDocument') && ! $this->route('cv');

        return [
            'title' => ['required', 'string', 'max:150'],
            'file' => [
                $isCreate ? 'required' : 'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:20480', // Max 20MB
            ],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'is_active' => ['nullable', 'boolean'],
            'isActive' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'A valid CV document file (PDF or DOCX) is required.',
            'file.mimes' => 'The CV document must be a file of type: PDF, DOC, or DOCX.',
            'file.max' => 'The CV file size must not exceed 20MB.',
            'title.required' => 'The CV title is required.',
        ];
    }
}
