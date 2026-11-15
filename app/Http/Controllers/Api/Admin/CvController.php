<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CvRequest;
use App\Models\CvDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvController extends Controller
{
    /**
     * Display a listing of all CV documents.
     */
    public function index(): JsonResponse
    {
        $documents = CvDocument::query()
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
            'total' => $documents->count(),
            'active_id' => $documents->firstWhere('is_active', true)?->id,
        ]);
    }

    /**
     * Upload and store a newly created CV document.
     */
    public function store(CvRequest $request): JsonResponse
    {
        $file = $request->file('file') ?? $request->file('cv') ?? $request->file('document');

        if (! $file) {
            return response()->json([
                'success' => false,
                'message' => 'No CV document file was uploaded.',
            ], 422);
        }

        // Store file securely in storage/app/public/cv
        $path = $file->store('cv', 'public');

        $data = [
            'title' => $request->input('title'),
            'file_path' => $path,
            'is_active' => $request->boolean('is_active', true),
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ];

        $document = CvDocument::create($data);

        return response()->json([
            'success' => true,
            'message' => 'CV document uploaded successfully.',
            'data' => $document->fresh(),
        ], 201);
    }

    /**
     * Display the specified CV document.
     */
    public function show(CvDocument $cv): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $cv,
        ]);
    }

    /**
     * Update the specified CV document (metadata or replace physical file).
     */
    public function update(CvRequest $request, CvDocument $cv): JsonResponse
    {
        $data = [];

        if ($request->has('title')) {
            $data['title'] = $request->input('title');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        // Check if replacing with a new physical file
        $file = $request->file('file') ?? $request->file('cv') ?? $request->file('document');
        if ($file) {
            // Delete previous physical file safely
            if ($cv->file_path) {
                MediaUploadController::deleteFileSafely($cv->file_path);
            }

            // Store new file
            $newPath = $file->store('cv', 'public');

            $data['file_path'] = $newPath;
            $data['original_filename'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getClientMimeType();
        }

        $cv->update($data);

        return response()->json([
            'success' => true,
            'message' => 'CV document updated successfully.',
            'data' => $cv->fresh(),
        ]);
    }

    /**
     * Activate the specified CV document.
     */
    public function activate(CvDocument $cv): JsonResponse
    {
        $cv->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'CV document set as active.',
            'data' => $cv->fresh(),
        ]);
    }

    /**
     * Remove the specified CV document and delete its physical file.
     */
    public function destroy(CvDocument $cv): JsonResponse
    {
        $cv->delete();

        return response()->json([
            'success' => true,
            'message' => 'CV document and physical file deleted successfully.',
        ]);
    }

    /**
     * Download the specified CV document.
     */
    public function download(CvDocument $cv): StreamedResponse|JsonResponse
    {
        $cleanPath = MediaUploadController::resolveStoragePath($cv->file_path);

        if (! Storage::disk('public')->exists($cleanPath)) {
            return response()->json([
                'success' => false,
                'message' => 'The physical CV document file was not found on the server.',
            ], 404);
        }

        $extension = pathinfo($cleanPath, PATHINFO_EXTENSION);
        $downloadName = Str::slug($cv->title).($extension ? '.'.$extension : '.pdf');

        return Storage::disk('public')->download($cleanPath, $downloadName);
    }
}
