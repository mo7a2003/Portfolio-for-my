<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    /**
     * Bootstrap the model and its traits.
     */
    protected static function booted(): void
    {
        static::creating(function (Project $project): void {
            if (empty($project->slug) && ! empty($project->title)) {
                $baseSlug = Str::slug($project->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $project->slug = $slug;
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
        'subtitle',
        'tagline',
        'slug',
        'description',
        'category',
        'year',
        'accent_color',
        'accent_color_hex',
        'badge',
        'ribbon',
        'thumbnail_url',
        'image',
        'live_url',
        'live_demo_url',
        'github_url',
        'github_repository',
        'technologies',
        'tech_stack',
        'status',
        'is_featured',
    ];

    /**
     * The accessors to append to the model's array form for Vue.js reactivity.
     *
     * @var list<string>
     */
    protected $appends = [
        'image',
        'tagline',
        'tech_stack',
        'accent_color_hex',
        'ribbon',
        'live_demo_url',
        'github_repository',
    ];

    /**
     * Format a path or URL into a full public URL.
     */
    public static function formatPublicUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/'.ltrim($value, '/'));
    }

    /**
     * Get the image attribute (fallback to thumbnail_url if image is null).
     */
    public function getImageAttribute(?string $value): ?string
    {
        $raw = $value ?? $this->attributes['thumbnail_url'] ?? null;

        return static::formatPublicUrl($raw);
    }

    /**
     * Set the image attribute.
     */
    public function setImageAttribute(?string $value): void
    {
        $this->attributes['image'] = $value;
        if (empty($this->attributes['thumbnail_url'])) {
            $this->attributes['thumbnail_url'] = $value;
        }
    }

    /**
     * Get the thumbnail_url attribute (fallback to image if thumbnail_url is null).
     */
    public function getThumbnailUrlAttribute(?string $value): ?string
    {
        $raw = $value ?? $this->attributes['image'] ?? null;

        return static::formatPublicUrl($raw);
    }

    /**
     * Set the thumbnail_url attribute.
     */
    public function setThumbnailUrlAttribute(?string $value): void
    {
        $this->attributes['thumbnail_url'] = $value;
        if (empty($this->attributes['image'])) {
            $this->attributes['image'] = $value;
        }
    }

    /**
     * Alias: tagline for subtitle.
     */
    public function getTaglineAttribute(): ?string
    {
        return $this->attributes['subtitle'] ?? null;
    }

    /**
     * Mutator for tagline.
     */
    public function setTaglineAttribute(?string $value): void
    {
        $this->attributes['subtitle'] = $value;
    }

    /**
     * Mutator for technologies (handles array, comma-separated string, or JSON string).
     */
    public function setTechnologiesAttribute(mixed $value): void
    {
        if (is_string($value)) {
            $trimmed = trim($value);
            if (str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) {
                $decoded = json_decode($trimmed, true);
                $value = is_array($decoded) ? $decoded : [];
            } else {
                $value = array_values(array_filter(array_map('trim', explode(',', $trimmed))));
            }
        }

        $this->attributes['technologies'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Alias: tech_stack for technologies.
     *
     * @return array<int, string>
     */
    public function getTechStackAttribute(): array
    {
        return is_array($this->technologies) ? $this->technologies : [];
    }

    /**
     * Mutator for tech_stack.
     */
    public function setTechStackAttribute(mixed $value): void
    {
        $this->setTechnologiesAttribute($value);
    }

    /**
     * Alias: accent_color_hex for accent_color.
     */
    public function getAccentColorHexAttribute(): ?string
    {
        return $this->attributes['accent_color'] ?? null;
    }

    /**
     * Mutator for accent_color_hex.
     */
    public function setAccentColorHexAttribute(?string $value): void
    {
        $this->attributes['accent_color'] = $value;
    }

    /**
     * Alias: ribbon for badge.
     */
    public function getRibbonAttribute(): ?string
    {
        return $this->attributes['badge'] ?? null;
    }

    /**
     * Mutator for ribbon.
     */
    public function setRibbonAttribute(?string $value): void
    {
        $this->attributes['badge'] = $value;
    }

    /**
     * Alias: live_demo_url for live_url.
     */
    public function getLiveDemoUrlAttribute(): ?string
    {
        return $this->attributes['live_url'] ?? null;
    }

    /**
     * Mutator for live_demo_url.
     */
    public function setLiveDemoUrlAttribute(?string $value): void
    {
        $this->attributes['live_url'] = $value;
    }

    /**
     * Alias: github_repository for github_url.
     */
    public function getGithubRepositoryAttribute(): ?string
    {
        return $this->attributes['github_url'] ?? null;
    }

    /**
     * Mutator for github_repository.
     */
    public function setGithubRepositoryAttribute(?string $value): void
    {
        $this->attributes['github_url'] = $value;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Scope query to featured projects only.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope query by project status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
