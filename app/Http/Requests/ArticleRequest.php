<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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

        // 1. image / thumbnail / cover / cover_image / coverImage / imageUrl
        if (! $this->hasFile('image') && ! $this->hasFile('thumbnail') && ! $this->hasFile('cover') && ! $this->hasFile('file')) {
            $image = $this->input('image') ?? $this->input('thumbnail') ?? $this->input('cover') ?? $this->input('cover_image') ?? $this->input('coverImage') ?? $this->input('imageUrl');
            if ($image !== null) {
                $mergeData['image'] = ! empty($image) ? $image : null;
            }
        }

        // 2. read_time / readTime / reading_time
        $readTime = $this->input('read_time') ?? $this->input('readTime') ?? $this->input('reading_time');
        $mergeData['read_time'] = (! empty($readTime) && is_string($readTime)) ? trim($readTime) : '5 min read';

        // 3. category
        $category = $this->input('category');
        $mergeData['category'] = (! empty($category) && is_string($category)) ? trim($category) : 'General';

        // 4. is_published / isPublished / published / status
        if ($this->has('is_published') || $this->has('isPublished') || $this->has('published') || $this->has('status')) {
            $rawPublished = $this->input('is_published') ?? $this->input('isPublished') ?? $this->input('published');
            if ($rawPublished === null && $this->has('status')) {
                $rawPublished = in_array(strtolower((string) $this->input('status')), ['published', 'active', '1', 'true']);
            }
            $mergeData['is_published'] = filter_var($rawPublished, FILTER_VALIDATE_BOOLEAN);
        } else {
            $mergeData['is_published'] = true;
        }

        // 5. published_at / publishedAt
        $publishedAt = $this->input('published_at') ?? $this->input('publishedAt');
        if ($publishedAt !== null) {
            $mergeData['published_at'] = ! empty($publishedAt) ? $publishedAt : null;
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
        $articleId = $this->route('article') instanceof Article
            ? $this->route('article')->id
            : $this->route('article');

        return [
            'title' => ['required', 'string', 'max:250'],
            'slug' => [
                'nullable',
                'string',
                'max:250',
                Rule::unique('articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable'],
            'thumbnail' => ['nullable'],
            'cover' => ['nullable'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'readTime' => ['nullable', 'string', 'max:50'],
            'is_published' => ['nullable', 'boolean'],
            'isPublished' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'publishedAt' => ['nullable', 'date'],
        ];
    }
}
