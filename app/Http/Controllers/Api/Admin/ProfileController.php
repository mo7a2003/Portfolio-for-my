<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display the primary profile settings.
     */
    public function show(): JsonResponse
    {
        $profile = Profile::first();

        return response()->json([
            'success' => true,
            'data' => $profile,
        ]);
    }

    /**
     * Update or create the primary profile settings.
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $profile = Profile::first();

        // Handle avatar upload if file provided
        $avatarFile = $request->file('avatar') ?? $request->file('avatar_url') ?? $request->file('image');
        if ($avatarFile) {
            $path = $avatarFile->store('profiles', 'public');
            $newAvatar = asset('storage/'.$path);
            $validated['avatar_url'] = $newAvatar;
            $validated['avatar'] = $newAvatar;
        }

        // Handle CV upload if file provided
        $cvFile = $request->file('cv') ?? $request->file('cv_url') ?? $request->file('resume');
        if ($cvFile) {
            $path = $cvFile->store('profiles', 'public');
            $newCv = asset('storage/'.$path);
            $validated['cv_url'] = $newCv;
            $validated['cv'] = $newCv;
        }

        if ($profile) {
            if (! empty($validated['avatar_url']) && ! empty($profile->avatar_url) && $validated['avatar_url'] !== $profile->avatar_url) {
                MediaUploadController::deleteFileSafely($profile->avatar_url);
            }
            if (! empty($validated['cv_url']) && ! empty($profile->cv_url) && $validated['cv_url'] !== $profile->cv_url) {
                MediaUploadController::deleteFileSafely($profile->cv_url);
            }
            $profile->update($validated);
        } else {
            $profile = Profile::create($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $profile->fresh(),
        ]);
    }
}
