<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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

        // 1. Harmonize image / thumbnail / thumbnail_url (only if not an uploaded file)
        if (! $this->hasFile('image') && ! $this->hasFile('thumbnail') && ! $this->hasFile('file')) {
            $image = $this->input('image') ?? $this->input('thumbnail') ?? $this->input('thumbnail_url');
            if ($image !== null && $image !== '') {
                $mergeData['image'] = $image;
                $mergeData['thumbnail_url'] = $image;
            }
        }

        // 2. Harmonize subtitle / tagline
        $subtitle = $this->input('subtitle') ?? $this->input('tagline');
        if ($subtitle !== null) {
            $mergeData['subtitle'] = $subtitle;
            $mergeData['tagline'] = $subtitle;
        }

        // 3. Harmonize accent_color / accent_color_hex
        $accentColor = $this->input('accent_color') ?? $this->input('accent_color_hex');
        if ($accentColor !== null) {
            $mergeData['accent_color'] = $accentColor;
            $mergeData['accent_color_hex'] = $accentColor;
        }

        // 4. Harmonize badge / ribbon
        $badge = $this->input('badge') ?? $this->input('ribbon');
        if ($badge !== null) {
            $mergeData['badge'] = $badge;
            $mergeData['ribbon'] = $badge;
        }

        // 5. Harmonize live_url / live_demo_url
        $liveUrl = $this->input('live_url') ?? $this->input('live_demo_url');
        $cleanLiveUrl = (! empty($liveUrl) && is_string($liveUrl)) ? trim($liveUrl) : null;
        $mergeData['live_url'] = $cleanLiveUrl;
        $mergeData['live_demo_url'] = $cleanLiveUrl;

        // 6. Harmonize github_url / github_repository
        $githubUrl = $this->input('github_url') ?? $this->input('github_repository');
        $cleanGithubUrl = (! empty($githubUrl) && is_string($githubUrl)) ? trim($githubUrl) : null;
        $mergeData['github_url'] = $cleanGithubUrl;
        $mergeData['github_repository'] = $cleanGithubUrl;

        // 7. Harmonize technologies / tech_stack (all formats: comma string, json, array)
        $rawTech = null;
        foreach (['technologies', 'tech_stack', 'techStack', 'tags', 'skills'] as $key) {
            $val = $this->input($key);
            if ($val !== null && $val !== '') {
                $rawTech = $val;
                break;
            }
        }

        $cleanTech = [];
        if ($rawTech !== null) {
            if (is_string($rawTech)) {
                $trimmed = trim($rawTech);
                if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
                    $decoded = json_decode($trimmed, true);
                    $techArray = is_array($decoded) ? $decoded : [];
                } else {
                    $techArray = explode(',', $trimmed);
                }
            } elseif (is_array($rawTech)) {
                $techArray = $rawTech;
            } else {
                $techArray = [];
            }

            $cleanTech = array_values(array_filter(array_map(function ($item): string {
                return trim((string) $item);
            }, $techArray)));
        }

        $mergeData['technologies'] = $cleanTech;
        $mergeData['tech_stack'] = $cleanTech;

        // 8. Default status if omitted or empty
        $status = $this->input('status');
        $mergeData['status'] = (! empty($status) && is_string($status)) ? trim($status) : 'completed';

        // 9. Harmonize is_featured boolean from FormData '1'/'0' or 'true'/'false'
        if ($this->has('is_featured')) {
            $mergeData['is_featured'] = filter_var($this->input('is_featured'), FILTER_VALIDATE_BOOLEAN);
        }

        // 10. Year
        $year = $this->input('year');
        if ($year !== null && $year !== '') {
            $mergeData['year'] = (string) $year;
        }

        // 11. Category
        $category = $this->input('category');
        if ($category !== null && $category !== '') {
            $mergeData['category'] = (string) $category;
        }

        // Arabic translations & dates
        $titleAr = $this->input('title_ar') ?? $this->input('titleAr');
        if ($titleAr !== null) {
            $mergeData['title_ar'] = $titleAr;
        }

        $subtitleAr = $this->input('subtitle_ar') ?? $this->input('subtitleAr') ?? $this->input('tagline_ar') ?? $this->input('taglineAr');
        if ($subtitleAr !== null) {
            $mergeData['subtitle_ar'] = $subtitleAr;
            $mergeData['tagline_ar'] = $subtitleAr;
        }

        $descAr = $this->input('description_ar') ?? $this->input('descriptionAr');
        if ($descAr !== null) {
            $mergeData['description_ar'] = $descAr;
        }

        $catAr = $this->input('category_ar') ?? $this->input('categoryAr');
        if ($catAr !== null) {
            $mergeData['category_ar'] = $catAr;
        }

        $startDate = $this->input('start_date') ?? $this->input('startDate');
        if ($startDate !== null) {
            $mergeData['start_date'] = $startDate;
        }

        $endDate = $this->input('end_date') ?? $this->input('endDate');
        if ($endDate !== null) {
            $mergeData['end_date'] = $endDate;
        }

        $completionDate = $this->input('completion_date') ?? $this->input('completionDate');
        if ($completionDate !== null) {
            $mergeData['completion_date'] = $completionDate;
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
        $projectId = $this->route('project') instanceof Project
            ? $this->route('project')->id
            : $this->route('project');

        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('projects', 'slug')->ignore($projectId),
            ],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'string', 'max:50'],
            'accent_color' => ['nullable', 'string', 'max:50'],
            'accent_color_hex' => ['nullable', 'string', 'max:50'],
            'badge' => ['nullable', 'string', 'max:100'],
            'ribbon' => ['nullable', 'string', 'max:100'],
            'thumbnail_url' => ['nullable'],
            'image' => ['nullable'],
            'thumbnail' => ['nullable'],
            'live_url' => ['nullable', 'string', 'max:500'],
            'live_demo_url' => ['nullable', 'string', 'max:500'],
            'github_url' => ['nullable', 'string', 'max:500'],
            'github_repository' => ['nullable', 'string', 'max:500'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['nullable', 'string', 'max:100'],
            'tech_stack' => ['nullable'],
            'status' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['nullable', 'boolean'],
            // Arabic Translation & Date fields
            'title_ar' => ['nullable', 'string', 'max:255'],
            'titleAr' => ['nullable', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:255'],
            'subtitleAr' => ['nullable', 'string', 'max:255'],
            'tagline_ar' => ['nullable', 'string', 'max:255'],
            'taglineAr' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'descriptionAr' => ['nullable', 'string'],
            'category_ar' => ['nullable', 'string', 'max:100'],
            'categoryAr' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'string', 'max:50'],
            'startDate' => ['nullable', 'string', 'max:50'],
            'end_date' => ['nullable', 'string', 'max:50'],
            'endDate' => ['nullable', 'string', 'max:50'],
            'completion_date' => ['nullable', 'string', 'max:50'],
            'completionDate' => ['nullable', 'string', 'max:50'],
        ];
    }
}
