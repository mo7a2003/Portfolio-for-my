<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaUploadController extends Controller
{
    /**
     * Upload an image or media file securely and return its public URL & path.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required_without:file', 'nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,gif,svg', 'max:25600'],
            'file' => ['required_without:image', 'nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif,svg,pdf', 'max:30720'],
            'folder' => ['nullable', 'string', 'max:50'],
        ]);

        $file = $request->file('image') ?? $request->file('file');

        if (! $file) {
            return response()->json([
                'success' => false,
                'message' => 'No image or file provided.',
            ], 400);
        }

        $folder = trim((string) $request->input('folder', 'uploads'), '/');
        $folder = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $folder) ?: 'uploads';

        // Store file onto public disk
        $path = $file->store($folder, 'public');
        $url = asset('storage/'.$path);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'url' => $url,
            'path' => $path,
            'data' => [
                'url' => $url,
                'path' => $path,
                'stored_name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'size_human' => $this->formatBytes($file->getSize()),
            ],
        ], 200);
    }

    /**
     * Alias for store to adhere to RESTful conventions.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->upload($request);
    }

    /**
     * Delete an existing media file from storage by relative path or full URL.
     */
    public function destroy(Request $request): JsonResponse
    {
        $input = $request->input('path') ?? $request->input('url') ?? $request->input('file') ?? $request->input('image');

        if (! $input) {
            return response()->json([
                'success' => false,
                'message' => 'File path or URL is required.',
            ], 422);
        }

        if (static::deleteFileSafely($input)) {
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found',
        ], 404);
    }

    /**
     * Delete a file safely from the public storage disk.
     */
    public static function deleteFileSafely(?string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        $cleanPath = static::resolveStoragePath($pathOrUrl);

        if (empty($cleanPath) || str_contains($cleanPath, '..')) {
            return false;
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->delete($cleanPath);
        }

        return false;
    }

    /**
     * Helper to resolve clean disk path from path or URL.
     */
    public static function resolveStoragePath(string $pathOrUrl): string
    {
        $path = parse_url($pathOrUrl, PHP_URL_PATH) ?? $pathOrUrl;

        // Strip leading /storage/ or storage/
        if (str_starts_with($path, '/storage/')) {
            $path = substr($path, 9);
        } elseif (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        return ltrim($path, '/');
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
