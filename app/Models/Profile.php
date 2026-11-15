<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'name',
        'fullName',
        'title',
        'role',
        'job_title',
        'bio',
        'about',
        'detailed_bio',
        'detailedBio',
        'experience_years',
        'experience',
        'projects_completed',
        'projectsCount',
        'milestones',
        'education',
        'avatar_url',
        'avatarUrl',
        'avatar',
        'cv_url',
        'cvUrl',
        'cv',
        'resume',
        'email',
        'phone',
        'phoneNumber',
        'availability_status',
        'availabilityStatus',
    ];

    /**
     * The accessors to append to the model's array form for Vue.js reactivity.
     *
     * @var list<string>
     */
    protected $appends = [
        'name',
        'fullName',
        'role',
        'about',
        'detailedBio',
        'experience',
        'experienceYears',
        'projectsCount',
        'projectsCompleted',
        'avatar',
        'avatarUrl',
        'cv',
        'cvUrl',
        'resume',
        'phoneNumber',
        'availabilityStatus',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->attributes['full_name'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['full_name'] = $value;
    }

    public function getFullNameAttribute(): ?string
    {
        return $this->attributes['full_name'] ?? null;
    }

    public function setFullNameAttribute(?string $value): void
    {
        $this->attributes['full_name'] = $value;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['title'] ?? null;
    }

    public function setRoleAttribute(?string $value): void
    {
        $this->attributes['title'] = $value;
    }

    public function getJobTitleAttribute(): ?string
    {
        return $this->attributes['title'] ?? null;
    }

    public function setJobTitleAttribute(?string $value): void
    {
        $this->attributes['title'] = $value;
    }

    public function getAboutAttribute(): ?string
    {
        return $this->attributes['detailed_bio'] ?? $this->attributes['bio'] ?? null;
    }

    public function setAboutAttribute(?string $value): void
    {
        $this->attributes['detailed_bio'] = $value;
        if (empty($this->attributes['bio'])) {
            $this->attributes['bio'] = $value;
        }
    }

    public function getDetailedBioAttribute(): ?string
    {
        return $this->attributes['detailed_bio'] ?? null;
    }

    public function setDetailedBioAttribute(?string $value): void
    {
        $this->attributes['detailed_bio'] = $value;
    }

    public function getExperienceAttribute(): ?int
    {
        return isset($this->attributes['experience_years']) ? (int) $this->attributes['experience_years'] : null;
    }

    public function setExperienceAttribute(mixed $value): void
    {
        $this->attributes['experience_years'] = (int) $value;
    }

    public function getExperienceYearsAttribute(): ?int
    {
        return isset($this->attributes['experience_years']) ? (int) $this->attributes['experience_years'] : null;
    }

    public function setExperienceYearsAttribute(mixed $value): void
    {
        $this->attributes['experience_years'] = (int) $value;
    }

    public function getProjectsCountAttribute(): ?int
    {
        return isset($this->attributes['projects_completed']) ? (int) $this->attributes['projects_completed'] : null;
    }

    public function setProjectsCountAttribute(mixed $value): void
    {
        $this->attributes['projects_completed'] = (int) $value;
    }

    public function getProjectsCompletedAttribute(): ?int
    {
        return isset($this->attributes['projects_completed']) ? (int) $this->attributes['projects_completed'] : null;
    }

    public function setProjectsCompletedAttribute(mixed $value): void
    {
        $this->attributes['projects_completed'] = (int) $value;
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

    public function getAvatarAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['avatar_url'] ?? null);
    }

    public function setAvatarAttribute(?string $value): void
    {
        $this->attributes['avatar_url'] = $value;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['avatar_url'] ?? null);
    }

    public function setAvatarUrlAttribute(?string $value): void
    {
        $this->attributes['avatar_url'] = $value;
    }

    public function getCvAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['cv_url'] ?? null);
    }

    public function setCvAttribute(?string $value): void
    {
        $this->attributes['cv_url'] = $value;
    }

    public function getCvUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['cv_url'] ?? null);
    }

    public function setCvUrlAttribute(?string $value): void
    {
        $this->attributes['cv_url'] = $value;
    }

    public function getResumeAttribute(): ?string
    {
        return static::formatPublicUrl($this->attributes['cv_url'] ?? null);
    }

    public function setResumeAttribute(?string $value): void
    {
        $this->attributes['cv_url'] = $value;
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->attributes['phone'] ?? null;
    }

    public function setPhoneNumberAttribute(?string $value): void
    {
        $this->attributes['phone'] = $value;
    }

    public function getAvailabilityStatusAttribute(): ?string
    {
        return $this->attributes['availability_status'] ?? null;
    }

    public function setAvailabilityStatusAttribute(?string $value): void
    {
        $this->attributes['availability_status'] = $value;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'projects_completed' => 'integer',
            'milestones' => 'array',
        ];
    }
}
