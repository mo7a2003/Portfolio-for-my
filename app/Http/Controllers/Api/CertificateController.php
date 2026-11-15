<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of active certificates.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Certificate::query()->active()->ordered();

        if ($request->filled('issuer')) {
            $query->where('issuer', $request->query('issuer'));
        }

        $certificates = $query->get();

        return response()->json([
            'success' => true,
            'data' => $certificates,
            'total' => $certificates->count(),
        ]);
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate): JsonResponse
    {
        if (! $certificate->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $certificate,
        ]);
    }
}
