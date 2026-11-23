<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate admin and return Sanctum Bearer token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $deviceName = $credentials['device_name'] ?? 'portfolio-admin-client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Authenticated successfully.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);
    }

    /**
     * Invalidate current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked successfully.',
        ]);
    }

    /**
     * Get authenticated admin user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    /**
     * Get aggregated telemetry and overview stats for the admin dashboard.
     */
    public function overviewStats(): JsonResponse
    {
        $projectsCount = \App\Models\Project::count();
        $activeProjects = \App\Models\Project::where('is_active', true)->count();
        $messagesCount = \App\Models\ContactMessage::count();
        $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
        $skillsCount = \App\Models\Skill::count();
        $certificatesCount = \App\Models\Certificate::count();
        $socialLinksCount = \App\Models\SocialLink::count();
        $articlesCount = \App\Models\Article::count();
        $publishedArticles = \App\Models\Article::where('is_published', true)->count();
        $totalVisits = \App\Models\PageVisit::count();
        $todayVisits = \App\Models\PageVisit::whereDate('created_at', today())->count();
        $yesterdayVisits = \App\Models\PageVisit::whereDate('created_at', today()->subDay())->count();

        if ($yesterdayVisits > 0) {
            $growth = round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100, 1);
            $viewsGrowth = ($growth >= 0 ? '+' : '') . $growth . '%';
        } elseif ($todayVisits > 0) {
            $viewsGrowth = '+100%';
        } else {
            $viewsGrowth = '0%';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_projects' => $projectsCount,
                'active_projects' => $activeProjects,
                'total_inquiries' => $messagesCount,
                'unread_inquiries' => $unreadMessages,
                'total_skills' => $skillsCount,
                'total_certificates' => $certificatesCount,
                'total_social_links' => $socialLinksCount,
                'total_articles' => $articlesCount,
                'published_articles' => $publishedArticles,
                'portfolio_views' => $totalVisits,
                'views_growth' => $viewsGrowth,
                'today_visits' => $todayVisits,
            ],
        ]);
    }
}
