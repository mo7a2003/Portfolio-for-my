<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SocialLinkRequest extends FormRequest
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

        // 1. platform / platform_name / name / platformName
        $platform = $this->input('platform') ?? $this->input('platform_name') ?? $this->input('name') ?? $this->input('platformName');
        if ($platform !== null) {
            $mergeData['platform'] = trim((string) $platform);
            $mergeData['platform_name'] = trim((string) $platform);
        }

        // 2. handle / username
        $handle = $this->input('handle') ?? $this->input('username');
        if ($handle !== null) {
            $mergeData['handle'] = ! empty($handle) ? trim((string) $handle) : null;
        }

        // 3. category / group
        $category = $this->input('category') ?? $this->input('group');
        if ($category !== null) {
            $mergeData['category'] = ! empty($category) ? trim((string) $category) : 'Social';
        }

        // 4. url / link
        $url = $this->input('url') ?? $this->input('link');
        if ($url !== null) {
            $mergeData['url'] = trim((string) $url);
        }

        // 5. accent_color / accentColor / color
        $accentColor = $this->input('accent_color') ?? $this->input('accentColor') ?? $this->input('color');
        $mergeData['accent_color'] = (! empty($accentColor) && is_string($accentColor)) ? trim($accentColor) : 'amber';

        // 6. is_direct_contact / isDirectContact / direct_contact
        if ($this->has('is_direct_contact') || $this->has('isDirectContact') || $this->has('direct_contact')) {
            $rawDirect = $this->input('is_direct_contact') ?? $this->input('isDirectContact') ?? $this->input('direct_contact');
            $mergeData['is_direct_contact'] = filter_var($rawDirect, FILTER_VALIDATE_BOOLEAN);
        } else {
            $mergeData['is_direct_contact'] = false;
        }

        // 7. sort_order / sortOrder / order
        $order = $this->input('sort_order') ?? $this->input('sortOrder') ?? $this->input('order');
        $mergeData['sort_order'] = ($order !== null && $order !== '') ? (int) $order : 0;

        // 8. is_active / isActive / active / status
        if ($this->has('is_active') || $this->has('isActive') || $this->has('active') || $this->has('status')) {
            $rawActive = $this->input('is_active') ?? $this->input('isActive') ?? $this->input('active');
            if ($rawActive === null && $this->has('status')) {
                $rawActive = in_array(strtolower((string) $this->input('status')), ['active', '1', 'true', 'enabled']);
            }
            $mergeData['is_active'] = filter_var($rawActive, FILTER_VALIDATE_BOOLEAN);
        } else {
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
        return [
            'platform' => ['required', 'string', 'max:100'],
            'platform_name' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:100'],
            'handle' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:500'],
            'link' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string'],
            'accent_color' => ['nullable', 'string', 'max:50'],
            'is_direct_contact' => ['nullable', 'boolean'],
            'isDirectContact' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'isActive' => ['nullable', 'boolean'],
        ];
    }
}
