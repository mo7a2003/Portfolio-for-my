<?php

namespace App\Models;

use App\Http\Controllers\Api\Admin\MediaUploadController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'file_path',
        'is_active',
        'isActive',
        'original_filename',
        'file_size',
        'mime_type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'file_url',
        'download_url',
        'file_size_human',
        'isActive',
    ];

    /**
     * Bootstrap the model and its events.
     */
    protected static function booted(): void
    {
        static::saved(function (CvDocument $cv): void {
            if ($cv->is_active) {
                // Deactivate all other CV documents
                static::where('id', '!=', $cv->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                // Sync active CV URL with primary Profile if profile exists
                Profile::first()?->update(['cv_url' => $cv->file_url]);
            }
        });

        static::deleted(function (CvDocument $cv): void {
            // Clean up the physical file from storage
            if ($cv->file_path) {
                MediaUploadController::deleteFileSafely($cv->file_path);
            }

            // If the deleted document was active, activate the most recent remaining one
            if ($cv->is_active) {
                $nextActive = static::latest('id')->first();
                if ($nextActive) {
                    $nextActive->update(['is_active' => true]);
                } else {
                    Profile::first()?->update(['cv_url' => null]);
                }
            }
        });
    }

    /**
     * Format a storage path or URL to a full public URL.
     */
    public static function formatPublicUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') && !str_starts_with($value, 'http://localhost') && !str_starts_with($value, 'http://127.0.0.1')) {
            $value = 'https://' . substr($value, 7);
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/'.ltrim($value, '/'));
    }

    /**
     * Get full public URL for accessing or previewing the file.
     */
    public function getFileUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['file_path'] ?? null);
    }

    /**
     * Get direct download endpoint URL.
     */
    public function getDownloadUrlAttribute(): string
    {
        return url('/api/cv/download');
    }

    /**
     * Human-readable file size accessor.
     */
    public function getFileSizeHumanAttribute(): ?string
    {
        $bytes = (int) ($this->attributes['file_size'] ?? 0);
        if ($bytes <= 0) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes) / log(1024));
        $pow = min((int) $pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2).' '.$units[$pow];
    }

    /**
     * Vue camelCase alias for is_active.
     */
    public function getIsActiveAttribute(): bool
    {
        return isset($this->attributes['is_active']) ? (bool) $this->attributes['is_active'] : true;
    }

    /**
     * Mutator for isActive / is_active.
     */
    public function setIsActiveAttribute(mixed $value): void
    {
        $this->attributes['is_active'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    /**
     * Scope query to only the active CV document.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
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
            'file_size' => 'integer',
        ];
    }
}
