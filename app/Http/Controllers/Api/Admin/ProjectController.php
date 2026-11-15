<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::query();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'total' => $projects->count(),
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Handle direct image file upload if provided
        $uploadedFile = $request->file('image') ?? $request->file('thumbnail') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('projects', 'public');
            $publicUrl = asset('storage/'.$path);
            $validated['thumbnail_url'] = $publicUrl;
            $validated['image'] = $publicUrl;
        } elseif (! empty($validated['image'])) {
            $validated['thumbnail_url'] = $validated['image'];
        } elseif (! empty($validated['thumbnail_url'])) {
            $validated['image'] = $validated['thumbnail_url'];
        }

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Project::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $project = Project::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'data' => $project,
        ], 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $validated = $request->validated();

        // Handle direct image file upload if provided
        $uploadedFile = $request->file('image') ?? $request->file('thumbnail') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('projects', 'public');
            $publicUrl = asset('storage/'.$path);
            $validated['thumbnail_url'] = $publicUrl;
            $validated['image'] = $publicUrl;
        } elseif (! empty($validated['image'])) {
            $validated['thumbnail_url'] = $validated['image'];
        } elseif (! empty($validated['thumbnail_url'])) {
            $validated['image'] = $validated['thumbnail_url'];
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = $project->slug ?: Str::slug($validated['title']);
        }

        // Clean up previous image if replaced with a new one
        $newImage = $validated['image'] ?? $validated['thumbnail_url'] ?? null;
        $oldImage = $project->image ?? $project->thumbnail_url;

        if ($newImage && $oldImage && $newImage !== $oldImage) {
            MediaUploadController::deleteFileSafely($oldImage);
        }

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => $project->fresh(),
        ]);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project): JsonResponse
    {
        $image = $project->image ?? $project->thumbnail_url;
        if ($image) {
            MediaUploadController::deleteFileSafely($image);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.',
        ]);
    }
}
