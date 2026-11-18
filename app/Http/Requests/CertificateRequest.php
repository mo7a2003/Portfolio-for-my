<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
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
        if ($title !== null) {
            $mergeData['title'] = trim((string) $title);
        }

        // 2. Issuer / Organization
        $issuer = $this->input('issuer') ?? $this->input('organization') ?? $this->input('company');
        if ($issuer !== null) {
            $mergeData['issuer'] = trim((string) $issuer);
        }

        // 3. Date / Year
        $date = $this->input('date') ?? $this->input('year');
        $mergeData['date'] = (! empty($date) && is_string($date)) ? trim($date) : '2026';

        // 3b. Description fallback
        $description = $this->input('description') ?? $this->input('description_en');
        if ($description !== null && trim((string) $description) !== '') {
            $mergeData['description'] = trim((string) $description);
        } elseif (! empty($mergeData['title'])) {
            $mergeData['description'] = $mergeData['title'];
        }

        // 4. Accent / Color
        $accent = $this->input('accent') ?? $this->input('accent_color') ?? $this->input('color');
        $mergeData['accent'] = (! empty($accent) && is_string($accent)) ? trim($accent) : 'amber';

        // 4b. Badge / Ribbon
        $badge = $this->input('badge') ?? $this->input('ribbon');
        if ($badge !== null) {
            $mergeData['badge'] = trim((string) $badge);
        }

        // 5. Credential ID
        $credentialId = $this->input('credential_id') ?? $this->input('credentialId') ?? $this->input('certificate_id');
        if ($credentialId !== null) {
            $mergeData['credential_id'] = ! empty($credentialId) ? trim((string) $credentialId) : null;
        }

        // 6. Verify URL
        $verifyUrl = $this->input('verify_url') ?? $this->input('verifyUrl') ?? $this->input('verification_url') ?? $this->input('url');
        if ($verifyUrl !== null) {
            $mergeData['verify_url'] = ! empty($verifyUrl) ? trim((string) $verifyUrl) : null;
        }

        // 7. Skills (array or comma-separated string)
        $rawSkills = $this->input('skills') ?? $this->input('skills_list') ?? $this->input('technologies');
        if ($rawSkills !== null) {
            if (is_string($rawSkills)) {
                $trimmed = trim($rawSkills);
                if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
                    $decoded = json_decode($trimmed, true);
                    $skillsArray = is_array($decoded) ? $decoded : [];
                } else {
                    $skillsArray = explode(',', $trimmed);
                }
            } elseif (is_array($rawSkills)) {
                $skillsArray = $rawSkills;
            } else {
                $skillsArray = [];
            }

            $cleanSkills = array_values(array_filter(array_map(function ($item): string {
                return trim((string) $item);
            }, $skillsArray)));

            $mergeData['skills'] = $cleanSkills;
        }

        // 8. Sort order
        $order = $this->input('sort_order') ?? $this->input('sortOrder') ?? $this->input('order');
        $mergeData['sort_order'] = ($order !== null && $order !== '') ? (int) $order : 0;

        // 9. is_active
        if ($this->has('is_active') || $this->has('isActive') || $this->has('active')) {
            $rawActive = $this->input('is_active') ?? $this->input('isActive') ?? $this->input('active');
            $mergeData['is_active'] = filter_var($rawActive, FILTER_VALIDATE_BOOLEAN);
        } else {
            $mergeData['is_active'] = true;
        }

        // 10. Image (if string)
        if (! $this->hasFile('image') && ! $this->hasFile('file')) {
            $image = $this->input('image') ?? $this->input('thumbnail') ?? $this->input('file_url');
            if ($image !== null) {
                $mergeData['image'] = ! empty($image) ? trim((string) $image) : null;
            }
        }

        // Arabic translations & dates
        $titleAr = $this->input('title_ar') ?? $this->input('titleAr');
        if ($titleAr !== null) {
            $mergeData['title_ar'] = $titleAr;
        }

        $issuerAr = $this->input('issuer_ar') ?? $this->input('issuerAr');
        if ($issuerAr !== null) {
            $mergeData['issuer_ar'] = $issuerAr;
        }

        $descAr = $this->input('description_ar') ?? $this->input('descriptionAr');
        if ($descAr !== null) {
            $mergeData['description_ar'] = $descAr;
        }

        $issueDate = $this->input('issue_date') ?? $this->input('issueDate');
        if ($issueDate !== null) {
            $mergeData['issue_date'] = $issueDate;
        }

        $expiryDate = $this->input('expiry_date') ?? $this->input('expiryDate');
        if ($expiryDate !== null) {
            $mergeData['expiry_date'] = $expiryDate;
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
            'title' => ['required', 'string', 'max:150'],
            'issuer' => ['required', 'string', 'max:150'],
            'date' => ['nullable', 'string', 'max:50'],
            'badge' => ['nullable', 'string', 'max:100'],
            'ribbon' => ['nullable', 'string', 'max:100'],
            'accent' => ['nullable', 'string', 'max:50'],
            'credential_id' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'string', 'max:100'],
            'verify_url' => ['nullable', 'string', 'max:500'],
            'image' => $this->hasFile('image')
                ? ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:30720']
                : ['nullable', 'string', 'max:1000'],
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:30720'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:30720'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            // Arabic Translation & Date fields
            'title_ar' => ['nullable', 'string', 'max:150'],
            'titleAr' => ['nullable', 'string', 'max:150'],
            'issuer_ar' => ['nullable', 'string', 'max:150'],
            'issuerAr' => ['nullable', 'string', 'max:150'],
            'description_ar' => ['nullable', 'string'],
            'descriptionAr' => ['nullable', 'string'],
            'issue_date' => ['nullable', 'string', 'max:50'],
            'issueDate' => ['nullable', 'string', 'max:50'],
            'expiry_date' => ['nullable', 'string', 'max:50'],
            'expiryDate' => ['nullable', 'string', 'max:50'],
        ];
    }
}
