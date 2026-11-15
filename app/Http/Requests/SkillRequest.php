<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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

        // 1. name / title / skill_name / skillName
        $name = $this->input('name') ?? $this->input('title') ?? $this->input('skill_name') ?? $this->input('skillName');
        if ($name !== null) {
            $mergeData['name'] = $name;
        }

        // 2. category / category_name / categoryName / group
        $category = $this->input('category') ?? $this->input('category_name') ?? $this->input('categoryName') ?? $this->input('group');
        $mergeData['category'] = (! empty($category) && is_string($category)) ? trim($category) : 'Frontend';

        // 3. proficiency_level / proficiency / level / percentage / rating / proficiencyLevel
        $proficiency = $this->input('proficiency_level') ?? $this->input('proficiency') ?? $this->input('level') ?? $this->input('percentage') ?? $this->input('rating') ?? $this->input('proficiencyLevel');
        if ($proficiency !== null && $proficiency !== '') {
            if (is_numeric($proficiency)) {
                $mergeData['proficiency_level'] = (int) $proficiency;
            } else {
                $mergeData['proficiency_level'] = trim((string) $proficiency);
            }
        } else {
            $mergeData['proficiency_level'] = 85;
        }

        // 4. order_index / order / orderIndex / sort_order / sortOrder
        $order = $this->input('order_index') ?? $this->input('order') ?? $this->input('orderIndex') ?? $this->input('sort_order') ?? $this->input('sortOrder');
        if ($order !== null && $order !== '') {
            $mergeData['order_index'] = (int) $order;
        } else {
            $mergeData['order_index'] = 0;
        }

        // 5. icon / image / icon_name / iconName
        if (! $this->hasFile('icon') && ! $this->hasFile('image')) {
            $icon = $this->input('icon') ?? $this->input('image') ?? $this->input('icon_name') ?? $this->input('iconName');
            if ($icon !== null) {
                $mergeData['icon'] = $icon;
                $mergeData['image'] = $icon;
            }
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
            'name' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'skill_name' => ['nullable', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'group' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable'],
            'image' => ['nullable'],
            'proficiency_level' => ['required', 'max:50'],
            'proficiency' => ['nullable', 'max:50'],
            'level' => ['nullable', 'max:50'],
            'percentage' => ['nullable', 'max:50'],
            'order_index' => ['nullable', 'integer'],
            'order' => ['nullable', 'integer'],
            'orderIndex' => ['nullable', 'integer'],
        ];
    }
}
