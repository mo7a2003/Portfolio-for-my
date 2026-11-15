<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Get list of projects with optional status and featured filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::query();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->has('featured')) {
            $isFeatured = filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN);
            $query->where('is_featured', $isFeatured);
        }

        $projects = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'total' => $projects->count(),
        ]);
    }

    /**
     * Get single project details by slug or id.
     */
    public function show(string $slugOrId): JsonResponse
    {
        $project = Project::query()
            ->where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }
}
