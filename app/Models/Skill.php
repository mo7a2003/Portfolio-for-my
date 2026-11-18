<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'title',
        'skill_name',
        'skillName',
        'category',
        'group',
        'icon',
        'image',
        'proficiency_level',
        'proficiency',
        'level',
        'percentage',
        'order_index',
        'order',
        'orderIndex',
        // Arabic Translation fields
        'name_ar',
        'nameAr',
        'category_ar',
        'categoryAr',
        'description_ar',
        'descriptionAr',
    ];

    /**
     * The accessors to append to the model's array form for Vue.js reactivity.
     *
     * @var list<string>
     */
    protected $appends = [
        'group',
        'image',
        'title',
        'skill_name',
        'proficiency',
        'level',
        'percentage',
        'order',
        'orderIndex',
        'nameAr',
        'categoryAr',
        'descriptionAr',
    ];

    public function getGroupAttribute(): ?string
    {
        return $this->attributes['category'] ?? null;
    }

    public function setGroupAttribute(?string $value): void
    {
        $this->attributes['category'] = $value;
    }

    public function getSkillNameAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function setSkillNameAttribute(?string $value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Format a path or URL into a full public URL, or leave icon identifier intact.
     */
    public static function formatPublicUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }

        // If it's a relative file path (contains a slash or extension)
        if (str_contains($value, '/') || str_contains($value, '.')) {
            if (str_starts_with($value, 'storage/')) {
                return asset($value);
            }

            return asset('storage/'.ltrim($value, '/'));
        }

        // Otherwise it's an icon identifier (e.g. 'vue', 'simple-icons:vue')
        return $value;
    }

    /**
     * Get the image attribute (fallback to icon if image is null).
     */
    public function getImageAttribute(?string $value): ?string
    {
        $raw = $value ?? $this->attributes['icon'] ?? null;

        return static::formatPublicUrl($raw);
    }

    /**
     * Set the image attribute.
     */
    public function setImageAttribute(?string $value): void
    {
        $this->attributes['image'] = $value;
        if (empty($this->attributes['icon'])) {
            $this->attributes['icon'] = $value;
        }
    }

    /**
     * Get the icon attribute (fallback to image if icon is null).
     */
    public function getIconAttribute(?string $value): ?string
    {
        $raw = $value ?? $this->attributes['image'] ?? null;

        return static::formatPublicUrl($raw);
    }

    /**
     * Set the icon attribute.
     */
    public function setIconAttribute(?string $value): void
    {
        $this->attributes['icon'] = $value;
        if (empty($this->attributes['image'])) {
            $this->attributes['image'] = $value;
        }
    }

    public function getTitleAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function setTitleAttribute(?string $value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Resolve numeric proficiency level (1-100) from string or integer.
     */
    public function resolveNumericProficiency(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $num = (int) $value;

            return min(100, max(1, $num));
        }

        $normalized = trim(mb_strtolower((string) $value));

        return match ($normalized) {
            'خبير', 'محترف', 'متقن', 'expert', 'master' => 95,
            'متقدم', 'ممتاز', 'advanced', 'senior' => 85,
            'جيد جدا', 'جيد جداً', 'very good' => 80,
            'متوسط', 'جيد', 'intermediate', 'mid', 'medium', 'good' => 70,
            'مبتدئ', 'أساسي', 'مبتديء', 'beginner', 'junior', 'basic', 'novice' => 50,
            default => (int) preg_replace('/[^0-9]/', '', $normalized) ?: 80,
        };
    }

    public function getProficiencyAttribute(): ?int
    {
        return $this->resolveNumericProficiency($this->attributes['proficiency_level'] ?? null);
    }

    public function setProficiencyAttribute(mixed $value): void
    {
        $this->attributes['proficiency_level'] = $value;
    }

    public function getLevelAttribute(): ?int
    {
        return $this->resolveNumericProficiency($this->attributes['proficiency_level'] ?? null);
    }

    public function setLevelAttribute(mixed $value): void
    {
        $this->attributes['proficiency_level'] = $value;
    }

    public function getPercentageAttribute(): ?int
    {
        return $this->resolveNumericProficiency($this->attributes['proficiency_level'] ?? null);
    }

    public function setPercentageAttribute(mixed $value): void
    {
        $this->attributes['proficiency_level'] = $value;
    }

    public function getOrderAttribute(): ?int
    {
        return isset($this->attributes['order_index']) ? (int) $this->attributes['order_index'] : null;
    }

    public function setOrderAttribute(mixed $value): void
    {
        $this->attributes['order_index'] = (int) $value;
    }

    public function getOrderIndexAttribute(): ?int
    {
        return isset($this->attributes['order_index']) ? (int) $this->attributes['order_index'] : null;
    }

    public function setOrderIndexAttribute(mixed $value): void
    {
        $this->attributes['order_index'] = (int) $value;
    }

    public function getNameArAttribute(): ?string
    {
        return $this->attributes['name_ar'] ?? null;
    }

    public function setNameArAttribute(?string $value): void
    {
        $this->attributes['name_ar'] = $value;
    }

    public function getCategoryArAttribute(): ?string
    {
        return $this->attributes['category_ar'] ?? null;
    }

    public function setCategoryArAttribute(?string $value): void
    {
        $this->attributes['category_ar'] = $value;
    }

    public function getDescriptionArAttribute(): ?string
    {
        return $this->attributes['description_ar'] ?? null;
    }

    public function setDescriptionArAttribute(?string $value): void
    {
        $this->attributes['description_ar'] = $value;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order_index' => 'integer',
        ];
    }

    /**
     * Scope query ordered by display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order_index', 'asc')->orderBy('name', 'asc');
    }
}
