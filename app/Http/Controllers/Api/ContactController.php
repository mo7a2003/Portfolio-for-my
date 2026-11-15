<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Store incoming contact message.
     */
    public function store(ContactRequest $request): JsonResponse
    {
        $message = ContactMessage::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your message has been received.',
            'data' => [
                'id' => $message->id,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ], 201);
    }
}
