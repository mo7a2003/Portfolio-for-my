<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'platform',
        'platform_name',
        'name',
        'handle',
        'category',
        'url',
        'link',
        'icon',
        'accent_color',
        'accentColor',
        'is_direct_contact',
        'isDirectContact',
        'direct_contact',
        'sort_order',
        'sortOrder',
        'order',
        'is_active',
        'isActive',
        'active',
    ];

    /**
     * The accessors to append to the model's array form for Vue.js reactivity.
     *
     * @var list<string>
     */
    protected $appends = [
        'platform',
        'name',
        'link',
        'accentColor',
        'isDirectContact',
        'sortOrder',
        'isActive',
        'active',
    ];

    /**
     * Platform accessor (supports platform and platform_name).
     */
    public function getPlatformAttribute(): ?string
    {
        return $this->attributes['platform'] ?? $this->attributes['platform_name'] ?? null;
    }

    public function setPlatformAttribute(?string $value): void
    {
        $this->attributes['platform'] = $value;
        $this->attributes['platform_name'] = $value;
    }

    public function getPlatformNameAttribute(): ?string
    {
        return $this->attributes['platform'] ?? $this->attributes['platform_name'] ?? null;
    }

    public function setPlatformNameAttribute(?string $value): void
    {
        $this->attributes['platform'] = $value;
        $this->attributes['platform_name'] = $value;
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['platform'] ?? $this->attributes['platform_name'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['platform'] = $value;
        $this->attributes['platform_name'] = $value;
    }

    public function getLinkAttribute(): ?string
    {
        return $this->attributes['url'] ?? null;
    }

    public function setLinkAttribute(?string $value): void
    {
        $this->attributes['url'] = $value;
    }

    public function getAccentColorAttribute(): string
    {
        return $this->attributes['accent_color'] ?? 'amber';
    }

    public function setAccentColorAttribute(?string $value): void
    {
        $this->attributes['accent_color'] = $value ?: 'amber';
    }

    public function getIsDirectContactAttribute(): bool
    {
        return isset($this->attributes['is_direct_contact']) ? (bool) $this->attributes['is_direct_contact'] : false;
    }

    public function setIsDirectContactAttribute(mixed $value): void
    {
        $this->attributes['is_direct_contact'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    public function setDirectContactAttribute(mixed $value): void
    {
        $this->setIsDirectContactAttribute($value);
    }

    public function getSortOrderAttribute(): int
    {
        return (int) ($this->attributes['sort_order'] ?? 0);
    }

    public function setSortOrderAttribute(mixed $value): void
    {
        $this->attributes['sort_order'] = (int) $value;
    }

    public function setOrderAttribute(mixed $value): void
    {
        $this->attributes['sort_order'] = (int) $value;
    }

    public function getIsActiveAttribute(): bool
    {
        return isset($this->attributes['is_active']) ? (bool) $this->attributes['is_active'] : true;
    }

    public function setIsActiveAttribute(mixed $value): void
    {
        $this->attributes['is_active'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    public function getActiveAttribute(): bool
    {
        return $this->getIsActiveAttribute();
    }

    public function setActiveAttribute(mixed $value): void
    {
        $this->setIsActiveAttribute($value);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_direct_contact' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope query to only active links.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query ordered by display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('platform', 'asc');
    }

    /**
     * Scope query to direct contact links only.
     */
    public function scopeDirectContact(Builder $query): Builder
    {
        return $query->where('is_direct_contact', true);
    }
}
