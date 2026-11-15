<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation and harmonize Vue.js field aliases.
     */
    protected function prepareForValidation(): void
    {
        $mergeData = [];

        // 1. name / fullName / full_name / sender_name
        $name = $this->input('name') ?? $this->input('fullName') ?? $this->input('full_name') ?? $this->input('sender_name');
        if ($name !== null) {
            $mergeData['name'] = $name;
        }

        // 2. email / emailAddress
        $email = $this->input('email') ?? $this->input('emailAddress');
        if ($email !== null) {
            $mergeData['email'] = $email;
        }

        // 3. message / msg / body / content
        $message = $this->input('message') ?? $this->input('msg') ?? $this->input('body') ?? $this->input('content');
        if ($message !== null) {
            $mergeData['message'] = $message;
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
        return [
            'name' => ['required', 'string', 'max:150'],
            'fullName' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'emailAddress' => ['nullable', 'email', 'max:150'],
            'message' => ['required', 'string', 'min:3', 'max:5000'],
            'msg' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
