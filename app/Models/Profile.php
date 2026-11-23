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
        'timeline',
        'career_milestones',
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
        // Arabic Translation & Date fields
        'full_name_ar',
        'fullNameAr',
        'name_ar',
        'nameAr',
        'title_ar',
        'titleAr',
        'role_ar',
        'roleAr',
        'bio_ar',
        'bioAr',
        'about_ar',
        'aboutAr',
        'detailed_bio_ar',
        'detailedBioAr',
        'education_ar',
        'educationAr',
        'milestones_ar',
        'milestonesAr',
        'birth_date',
        'birthDate',
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
        'fullNameAr',
        'titleAr',
        'bioAr',
        'detailedBioAr',
        'educationAr',
        'milestonesAr',
        'timeline',
        'birthDate',
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

    public function getFullNameArAttribute(): ?string
    {
        return $this->attributes['full_name_ar'] ?? null;
    }

    public function setFullNameArAttribute(?string $value): void
    {
        $this->attributes['full_name_ar'] = $value;
    }

    public function getNameArAttribute(): ?string
    {
        return $this->attributes['full_name_ar'] ?? null;
    }

    public function setNameArAttribute(?string $value): void
    {
        $this->attributes['full_name_ar'] = $value;
    }

    public function getTitleArAttribute(): ?string
    {
        return $this->attributes['title_ar'] ?? null;
    }

    public function setTitleArAttribute(?string $value): void
    {
        $this->attributes['title_ar'] = $value;
    }

    public function getRoleArAttribute(): ?string
    {
        return $this->attributes['title_ar'] ?? null;
    }

    public function setRoleArAttribute(?string $value): void
    {
        $this->attributes['title_ar'] = $value;
    }

    public function getBioArAttribute(): ?string
    {
        return $this->attributes['bio_ar'] ?? null;
    }

    public function setBioArAttribute(?string $value): void
    {
        $this->attributes['bio_ar'] = $value;
    }

    public function getAboutArAttribute(): ?string
    {
        return $this->attributes['bio_ar'] ?? null;
    }

    public function setAboutArAttribute(?string $value): void
    {
        $this->attributes['bio_ar'] = $value;
    }

    public function getDetailedBioArAttribute(): ?string
    {
        return $this->attributes['detailed_bio_ar'] ?? null;
    }

    public function setDetailedBioArAttribute(?string $value): void
    {
        $this->attributes['detailed_bio_ar'] = $value;
    }

    public function getEducationArAttribute(): ?string
    {
        return $this->attributes['education_ar'] ?? null;
    }

    public function setEducationArAttribute(?string $value): void
    {
        $this->attributes['education_ar'] = $value;
    }

    public function getMilestonesAttribute(): array
    {
        $val = $this->attributes['milestones'] ?? null;
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            $val = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        if (! is_array($val)) {
            return [];
        }

        return array_map(function ($m) {
            if (! is_array($m)) return $m;
            $period = trim((string) ($m['period'] ?? $m['year'] ?? $m['date'] ?? $m['time_frame'] ?? $m['duration'] ?? $m['start_date'] ?? $m['completion_date'] ?? ''));
            $company = trim((string) ($m['company'] ?? $m['organization'] ?? $m['subtitle'] ?? ''));
            $companyAr = trim((string) ($m['company_ar'] ?? $m['companyAr'] ?? ''));
            $title = trim((string) ($m['title'] ?? ''));
            $titleAr = trim((string) ($m['title_ar'] ?? $m['titleAr'] ?? ''));
            $desc = trim((string) ($m['description'] ?? ''));
            $descAr = trim((string) ($m['description_ar'] ?? $m['descriptionAr'] ?? ''));

            return array_merge($m, [
                'period' => $period,
                'year' => $period,
                'date' => $period,
                'time_frame' => $period,
                'start_date' => $m['start_date'] ?? $period,
                'completion_date' => $m['completion_date'] ?? $period,
                'company' => $company,
                'organization' => $company,
                'subtitle' => $company,
                'company_ar' => $companyAr,
                'title' => $title,
                'title_ar' => $titleAr,
                'description' => $desc,
                'description_ar' => $descAr,
            ]);
        }, $val);
    }

    public function setMilestonesAttribute(mixed $value): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_array($value)) {
            $normalized = [];
            foreach ($value as $m) {
                if (! is_array($m)) continue;
                $period = trim((string) ($m['period'] ?? $m['year'] ?? $m['date'] ?? $m['time_frame'] ?? $m['duration'] ?? $m['start_date'] ?? $m['completion_date'] ?? ''));
                $company = trim((string) ($m['company'] ?? $m['organization'] ?? $m['subtitle'] ?? ''));
                $companyAr = trim((string) ($m['company_ar'] ?? $m['companyAr'] ?? ''));
                $title = trim((string) ($m['title'] ?? ''));
                $titleAr = trim((string) ($m['title_ar'] ?? $m['titleAr'] ?? ''));
                $desc = trim((string) ($m['description'] ?? ''));
                $descAr = trim((string) ($m['description_ar'] ?? $m['descriptionAr'] ?? ''));

                $normalized[] = array_merge($m, [
                    'period' => $period,
                    'year' => $period,
                    'date' => $period,
                    'time_frame' => $period,
                    'start_date' => $m['start_date'] ?? $period,
                    'completion_date' => $m['completion_date'] ?? $period,
                    'company' => $company,
                    'organization' => $company,
                    'subtitle' => $company,
                    'company_ar' => $companyAr,
                    'title' => $title,
                    'title_ar' => $titleAr,
                    'description' => $desc,
                    'description_ar' => $descAr,
                ]);
            }
            $this->attributes['milestones'] = json_encode($normalized, JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['milestones'] = $value;
        }
    }

    public function getTimelineAttribute(): array
    {
        return $this->getMilestonesAttribute();
    }

    public function setTimelineAttribute(mixed $value): void
    {
        $this->setMilestonesAttribute($value);
    }

    public function setCareerMilestonesAttribute(mixed $value): void
    {
        $this->setMilestonesAttribute($value);
    }

    public function getMilestonesArAttribute(): mixed
    {
        $val = $this->attributes['milestones_ar'] ?? null;
        if (is_string($val)) {
            $decoded = json_decode($val, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
        }

        return $val;
    }

    public function setMilestonesArAttribute(mixed $value): void
    {
        $this->attributes['milestones_ar'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getBirthDateAttribute(): ?string
    {
        return $this->attributes['birth_date'] ?? null;
    }

    public function setBirthDateAttribute(?string $value): void
    {
        $this->attributes['birth_date'] = $value;
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
            'milestones_ar' => 'array',
        ];
    }
}
