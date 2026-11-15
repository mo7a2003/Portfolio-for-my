<?php

namespace App\Models;

use App\Http\Controllers\Api\Admin\MediaUploadController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'issuer',
        'date',
        'badge',
        'accent',
        'credential_id',
        'credentialId',
        'description',
        'skills',
        'verify_url',
        'verifyUrl',
        'verification_url',
        'image',
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
        'credentialId',
        'verifyUrl',
        'sortOrder',
        'isActive',
    ];

    /**
     * Bootstrap the model and its events.
     */
    protected static function booted(): void
    {
        static::deleted(function (Certificate $certificate): void {
            if ($certificate->image) {
                MediaUploadController::deleteFileSafely($certificate->image);
            }
        });
    }

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
     * Accessor for image: return full public URL.
     */
    public function getImageAttribute(?string $value): ?string
    {
        return static::formatPublicUrl($value);
    }

    /**
     * Mutator for skills (accepts array, comma-separated string, or JSON string).
     */
    public function setSkillsAttribute(mixed $value): void
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

        $this->attributes['skills'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Aliases for credential_id.
     */
    public function getCredentialIdAttribute(): ?string
    {
        return $this->attributes['credential_id'] ?? null;
    }

    public function setCredentialIdAttribute(?string $value): void
    {
        $this->attributes['credential_id'] = $value;
    }

    /**
     * Aliases for verify_url.
     */
    public function getVerifyUrlAttribute(): ?string
    {
        return $this->attributes['verify_url'] ?? null;
    }

    public function setVerifyUrlAttribute(?string $value): void
    {
        $this->attributes['verify_url'] = $value;
    }

    public function setVerificationUrlAttribute(?string $value): void
    {
        $this->attributes['verify_url'] = $value;
    }

    /**
     * Aliases for sort_order.
     */
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

    /**
     * Aliases for is_active.
     */
    public function getIsActiveAttribute(): bool
    {
        return isset($this->attributes['is_active']) ? (bool) $this->attributes['is_active'] : true;
    }

    public function setIsActiveAttribute(mixed $value): void
    {
        $this->attributes['is_active'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    public function setActiveAttribute(mixed $value): void
    {
        $this->setIsActiveAttribute($value);
    }

    /**
     * Scope query to only active certificates.
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
        return $query->orderBy('sort_order', 'asc')->orderByDesc('date');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
