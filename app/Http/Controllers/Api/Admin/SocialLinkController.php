<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of all social links.
     */
    public function index(): JsonResponse
    {
        $links = SocialLink::query()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $links,
            'total' => $links->count(),
        ]);
    }

    /**
     * Store a newly created social link.
     */
    public function store(SocialLinkRequest $request): JsonResponse
    {
        $link = SocialLink::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Social link created successfully.',
            'data' => $link->fresh(),
        ], 201);
    }

    /**
     * Display the specified social link.
     */
    public function show(SocialLink $socialLink): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $socialLink,
        ]);
    }

    /**
     * Update the specified social link.
     */
    public function update(SocialLinkRequest $request, SocialLink $socialLink): JsonResponse
    {
        $socialLink->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Social link updated successfully.',
            'data' => $socialLink->fresh(),
        ]);
    }

    /**
     * Remove the specified social link.
     */
    public function destroy(SocialLink $socialLink): JsonResponse
    {
        $socialLink->delete();

        return response()->json([
            'success' => true,
            'message' => 'Social link deleted successfully.',
        ]);
    }
}
