<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Admin\MediaUploadController;
use App\Http\Controllers\Controller;
use App\Models\CvDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvController extends Controller
{
    /**
     * Download the currently active CV document.
     */
    public function download(): StreamedResponse|JsonResponse
    {
        $cv = CvDocument::query()
            ->active()
            ->latest('updated_at')
            ->first() ?? CvDocument::query()->latest('id')->first();

        if (! $cv) {
            return response()->json([
                'success' => false,
                'message' => 'No active CV document is currently available for download.',
            ], 404);
        }

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

    /**
     * Get details and URL of the currently active CV document.
     */
    public function show(): JsonResponse
    {
        $cv = CvDocument::query()
            ->active()
            ->latest('updated_at')
            ->first() ?? CvDocument::query()->latest('id')->first();

        if (! $cv) {
            return response()->json([
                'success' => false,
                'message' => 'No active CV document found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cv,
        ]);
    }
}
