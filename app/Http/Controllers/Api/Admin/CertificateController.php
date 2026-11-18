<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    /**
     * Display a listing of all certificates.
     */
    public function index(): JsonResponse
    {
        $certificates = Certificate::query()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $certificates,
            'total' => $certificates->count(),
        ]);
    }

    /**
     * Store a newly created certificate.
     */
    public function store(CertificateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Handle direct image file upload if provided
        $uploadedFile = $request->file('image') ?? $request->file('file') ?? $request->file('thumbnail');
        if ($uploadedFile) {
            $path = $uploadedFile->store('certificates', 'public');
            $validated['image'] = asset('storage/'.$path);
        } elseif (! $request->has('image') || empty($validated['image'])) {
            unset($validated['image']);
        }

        $certificate = Certificate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Certificate created successfully.',
            'data' => $certificate->fresh(),
        ], 201);
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $certificate,
        ]);
    }

    /**
     * Update the specified certificate.
     */
    public function update(CertificateRequest $request, Certificate $certificate): JsonResponse
    {
        $validated = $request->validated();

        // Handle direct image file upload if provided
        $uploadedFile = $request->file('image') ?? $request->file('file') ?? $request->file('thumbnail');
        if ($uploadedFile) {
            $path = $uploadedFile->store('certificates', 'public');
            $validated['image'] = asset('storage/'.$path);
        } elseif (! $request->has('image') || empty($validated['image'])) {
            unset($validated['image']);
        }

        // Clean up old image if replaced with a new one
        $newImage = $validated['image'] ?? null;
        $oldImage = $certificate->getRawOriginal('image') ?? $certificate->image;

        if ($newImage && $oldImage && $newImage !== $oldImage) {
            MediaUploadController::deleteFileSafely($oldImage);
        }

        $certificate->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Certificate updated successfully.',
            'data' => $certificate->fresh(),
        ]);
    }

    /**
     * Remove the specified certificate.
     */
    public function destroy(Certificate $certificate): JsonResponse
    {
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate deleted successfully.',
        ]);
    }
}
