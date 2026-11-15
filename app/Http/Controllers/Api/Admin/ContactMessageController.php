<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of incoming contact messages.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactMessage::query();

        if ($request->has('is_read')) {
            $isRead = filter_var($request->query('is_read'), FILTER_VALIDATE_BOOLEAN);
            $query->where('is_read', $isRead);
        }

        $messages = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $messages,
            'unread_count' => ContactMessage::unread()->count(),
            'total' => $messages->count(),
        ]);
    }

    /**
     * Display the specified contact message.
     */
    public function show(ContactMessage $contactMessage): JsonResponse
    {
        if (! $contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'data' => $contactMessage,
        ]);
    }

    /**
     * Mark message as read or unread explicitly.
     */
    public function markAsRead(Request $request, ContactMessage $contactMessage): JsonResponse
    {
        $isRead = $request->has('is_read')
            ? filter_var($request->input('is_read'), FILTER_VALIDATE_BOOLEAN)
            : true;

        $contactMessage->update(['is_read' => $isRead]);

        return response()->json([
            'success' => true,
            'message' => $isRead ? 'Message marked as read.' : 'Message marked as unread.',
            'data' => $contactMessage->fresh(),
        ]);
    }

    /**
     * Remove the specified contact message.
     */
    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact message deleted successfully.',
        ]);
    }
}
