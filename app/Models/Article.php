<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    /**
     * Bootstrap the model and its traits.
     */
    protected static function booted(): void
    {
        static::creating(function (Article $article): void {
            if (empty($article->slug) && ! empty($article->title)) {
                $baseSlug = Str::slug($article->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $article->slug = $slug;
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image',
        'cover',
        'thumbnail',
        'read_time',
        'readTime',
        'is_published',
        'isPublished',
        'published_at',
        'publishedAt',
        // Arabic Translation fields
        'title_ar',
        'titleAr',
        'excerpt_ar',
        'excerptAr',
        'content_ar',
        'contentAr',
        'category_ar',
        'categoryAr',
    ];

    /**
     * The accessors to append to the model's array form for Vue.js reactivity.
     *
     * @var list<string>
     */
    protected $appends = [
        'cover',
        'readTime',
        'isPublished',
        'publishedAt',
        'titleAr',
        'excerptAr',
        'contentAr',
        'categoryAr',
    ];

    /**
     * Format a path or URL into a full public URL.
     */
    public static function formatPublicUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') && !str_starts_with($value, 'http://localhost') && !str_starts_with($value, 'http://127.0.0.1')) {
            $value = 'https://' . substr($value, 7);
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/'.ltrim($value, '/'));
    }

    public function getImageAttribute(?string $value): ?string
    {
        return static::formatPublicUrl($value);
    }

    public function getCoverAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['image'] ?? null);
    }

    public function setCoverAttribute(?string $value): void
    {
        $this->attributes['image'] = $value;
    }

    public function getThumbnailAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['image'] ?? null);
    }

    public function setThumbnailAttribute(?string $value): void
    {
        $this->attributes['image'] = $value;
    }

    public function getReadTimeAttribute(): ?string
    {
        return $this->attributes['read_time'] ?? null;
    }

    public function setReadTimeAttribute(?string $value): void
    {
        $this->attributes['read_time'] = $value;
    }

    public function getIsPublishedAttribute(): bool
    {
        return isset($this->attributes['is_published']) ? (bool) $this->attributes['is_published'] : true;
    }

    public function setIsPublishedAttribute(mixed $value): void
    {
        $this->attributes['is_published'] = (bool) $value;
    }

    public function getPublishedAtAttribute(): ?string
    {
        return isset($this->attributes['published_at']) ? (string) $this->attributes['published_at'] : null;
    }

    public function setPublishedAtAttribute(?string $value): void
    {
        $this->attributes['published_at'] = $value;
    }

    public function getTitleArAttribute(): ?string
    {
        return $this->attributes['title_ar'] ?? null;
    }

    public function setTitleArAttribute(?string $value): void
    {
        $this->attributes['title_ar'] = $value;
    }

    public function getExcerptArAttribute(): ?string
    {
        return $this->attributes['excerpt_ar'] ?? null;
    }

    public function setExcerptArAttribute(?string $value): void
    {
        $this->attributes['excerpt_ar'] = $value;
    }

    public function getContentArAttribute(): ?string
    {
        return $this->attributes['content_ar'] ?? null;
    }

    public function setContentArAttribute(?string $value): void
    {
        $this->attributes['content_ar'] = $value;
    }

    public function getCategoryArAttribute(): ?string
    {
        return $this->attributes['category_ar'] ?? null;
    }

    public function setCategoryArAttribute(?string $value): void
    {
        $this->attributes['category_ar'] = $value;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope query to published articles only.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
