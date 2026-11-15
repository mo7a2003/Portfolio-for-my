<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    /**
     * Display a listing of all skills.
     */
    public function index(): JsonResponse
    {
        $skills = Skill::query()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $skills,
            'total' => $skills->count(),
        ]);
    }

    /**
     * Store a newly created skill.
     */
    public function store(SkillRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $uploadedFile = $request->file('image') ?? $request->file('icon') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('skills', 'public');
            $publicUrl = asset('storage/'.$path);
            $validated['image'] = $publicUrl;
            $validated['icon'] = $publicUrl;
        }

        $skill = Skill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully.',
            'data' => $skill,
        ], 201);
    }

    /**
     * Display the specified skill.
     */
    public function show(Skill $skill): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $skill,
        ]);
    }

    /**
     * Update the specified skill.
     */
    public function update(SkillRequest $request, Skill $skill): JsonResponse
    {
        $validated = $request->validated();

        $uploadedFile = $request->file('image') ?? $request->file('icon') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('skills', 'public');
            $publicUrl = asset('storage/'.$path);
            $validated['image'] = $publicUrl;
            $validated['icon'] = $publicUrl;
        }

        $newImage = $validated['image'] ?? $validated['icon'] ?? null;
        $oldImage = $skill->image ?? $skill->icon;

        if ($newImage && $oldImage && $newImage !== $oldImage) {
            MediaUploadController::deleteFileSafely($oldImage);
        }

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.',
            'data' => $skill->fresh(),
        ]);
    }

    /**
     * Remove the specified skill.
     */
    public function destroy(Skill $skill): JsonResponse
    {
        $image = $skill->image ?? $skill->icon;
        if ($image) {
            MediaUploadController::deleteFileSafely($image);
        }

        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully.',
        ]);
    }
}
