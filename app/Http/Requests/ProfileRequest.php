<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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

        // 1. full_name / fullName / name
        $name = $this->input('full_name') ?? $this->input('fullName') ?? $this->input('name');
        if ($name !== null) {
            $mergeData['full_name'] = $name;
        }

        // 2. title / role / job_title / professional_role / professionalRole
        $title = $this->input('title') ?? $this->input('role') ?? $this->input('job_title') ?? $this->input('professional_role') ?? $this->input('professionalRole');
        if ($title !== null) {
            $mergeData['title'] = $title;
        }

        // 3. bio / about / short_bio
        $bio = $this->input('bio') ?? $this->input('short_bio');
        if ($bio !== null) {
            $mergeData['bio'] = $bio;
        }

        // 4. detailed_bio / detailedBio / long_bio / about
        $detailedBio = $this->input('detailed_bio') ?? $this->input('detailedBio') ?? $this->input('about') ?? $this->input('long_bio');
        if ($detailedBio !== null) {
            $mergeData['detailed_bio'] = $detailedBio;
            if (! isset($mergeData['bio']) && ! $this->has('bio')) {
                $mergeData['bio'] = $detailedBio;
            }
        }

        // 5. experience_years / experienceYears / experience
        $experience = $this->input('experience_years') ?? $this->input('experienceYears') ?? $this->input('experience');
        $mergeData['experience_years'] = ($experience !== null && $experience !== '') ? (int) $experience : 1;

        // 6. projects_completed / projectsCompleted / projects_count / projectsCount
        $projectsCompleted = $this->input('projects_completed') ?? $this->input('projectsCompleted') ?? $this->input('projects_count') ?? $this->input('projectsCount');
        $mergeData['projects_completed'] = ($projectsCompleted !== null && $projectsCompleted !== '') ? (int) $projectsCompleted : 3;

        // 7. milestones (handle array or JSON string from FormData)
        $milestones = $this->input('milestones');
        if (is_string($milestones)) {
            $decoded = json_decode($milestones, true);
            if (is_array($decoded)) {
                $mergeData['milestones'] = $decoded;
            }
        } elseif (is_array($milestones)) {
            $mergeData['milestones'] = $milestones;
        }

        // 8. avatar_url / avatarUrl / avatar / image
        if (! $this->hasFile('avatar') && ! $this->hasFile('avatar_url') && ! $this->hasFile('image')) {
            $avatar = $this->input('avatar_url') ?? $this->input('avatarUrl') ?? $this->input('avatar') ?? $this->input('image');
            if ($avatar !== null) {
                $mergeData['avatar_url'] = ! empty($avatar) ? $avatar : null;
            }
        }

        // 9. cv_url / cvUrl / cv / resume / resume_url
        if (! $this->hasFile('cv') && ! $this->hasFile('cv_url') && ! $this->hasFile('resume')) {
            $cv = $this->input('cv_url') ?? $this->input('cvUrl') ?? $this->input('cv') ?? $this->input('resume') ?? $this->input('resume_url');
            if ($cv !== null) {
                $mergeData['cv_url'] = ! empty($cv) ? $cv : null;
            }
        }

        // 10. phone / phoneNumber / phone_number
        $phone = $this->input('phone') ?? $this->input('phoneNumber') ?? $this->input('phone_number');
        if ($phone !== null) {
            $mergeData['phone'] = ! empty($phone) ? $phone : null;
        }

        // 11. availability_status / availabilityStatus / availability / status
        $availability = $this->input('availability_status') ?? $this->input('availabilityStatus') ?? $this->input('availability') ?? $this->input('status');
        $mergeData['availability_status'] = (! empty($availability) && is_string($availability)) ? trim($availability) : 'available';

        // 12. education
        $education = $this->input('education');
        if ($education !== null) {
            $mergeData['education'] = $education;
        }

        // 13. Arabic fields & birth date
        $fullNameAr = $this->input('full_name_ar') ?? $this->input('fullNameAr') ?? $this->input('name_ar') ?? $this->input('nameAr');
        if ($fullNameAr !== null) {
            $mergeData['full_name_ar'] = $fullNameAr;
        }

        $titleAr = $this->input('title_ar') ?? $this->input('titleAr') ?? $this->input('role_ar') ?? $this->input('roleAr');
        if ($titleAr !== null) {
            $mergeData['title_ar'] = $titleAr;
        }

        $bioAr = $this->input('bio_ar') ?? $this->input('bioAr') ?? $this->input('about_ar') ?? $this->input('aboutAr');
        if ($bioAr !== null) {
            $mergeData['bio_ar'] = $bioAr;
        }

        $detailedBioAr = $this->input('detailed_bio_ar') ?? $this->input('detailedBioAr');
        if ($detailedBioAr !== null) {
            $mergeData['detailed_bio_ar'] = $detailedBioAr;
        }

        $educationAr = $this->input('education_ar') ?? $this->input('educationAr');
        if ($educationAr !== null) {
            $mergeData['education_ar'] = $educationAr;
        }

        $milestonesAr = $this->input('milestones_ar') ?? $this->input('milestonesAr');
        if (is_string($milestonesAr)) {
            $decoded = json_decode($milestonesAr, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $mergeData['milestones_ar'] = $decoded;
            }
        } elseif (is_array($milestonesAr)) {
            $mergeData['milestones_ar'] = $milestonesAr;
        }

        $birthDate = $this->input('birth_date') ?? $this->input('birthDate');
        if ($birthDate !== null) {
            $mergeData['birth_date'] = $birthDate;
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
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'name' => ['nullable', 'string', 'max:150'],
            'fullName' => ['nullable', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:150'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'bio' => ['required', 'string'],
            'about' => ['nullable', 'string'],
            'detailed_bio' => ['nullable', 'string'],
            'detailedBio' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'experience' => ['nullable', 'integer', 'min:0'],
            'projects_completed' => ['nullable', 'integer', 'min:0'],
            'milestones' => ['nullable'],
            'education' => ['nullable', 'string', 'max:255'],
            'avatar_url' => ['nullable'],
            'avatar' => ['nullable'],
            'cv_url' => ['nullable'],
            'cv' => ['nullable'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phoneNumber' => ['nullable', 'string', 'max:50'],
            'availability_status' => ['nullable', 'string', 'max:50'],
            'availability' => ['nullable', 'string', 'max:50'],
            // Arabic Translation & Date fields
            'full_name_ar' => ['nullable', 'string', 'max:150'],
            'fullNameAr' => ['nullable', 'string', 'max:150'],
            'name_ar' => ['nullable', 'string', 'max:150'],
            'nameAr' => ['nullable', 'string', 'max:150'],
            'title_ar' => ['nullable', 'string', 'max:150'],
            'titleAr' => ['nullable', 'string', 'max:150'],
            'role_ar' => ['nullable', 'string', 'max:150'],
            'roleAr' => ['nullable', 'string', 'max:150'],
            'bio_ar' => ['nullable', 'string'],
            'bioAr' => ['nullable', 'string'],
            'detailed_bio_ar' => ['nullable', 'string'],
            'detailedBioAr' => ['nullable', 'string'],
            'education_ar' => ['nullable', 'string', 'max:255'],
            'educationAr' => ['nullable', 'string', 'max:255'],
            'milestones_ar' => ['nullable'],
            'milestonesAr' => ['nullable'],
            'birth_date' => ['nullable', 'string', 'max:50'],
            'birthDate' => ['nullable', 'string', 'max:50'],
        ];
    }
}
