<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    /**
     * Known crawler and bot patterns to ignore.
     */
    protected const BOT_REGEX = '/(bot|crawl|spider|slurp|facebookexternalhit|bingpreview|headless|phantom|lighthouse|pagespeed|googlebot|yandex|duckduckbot|baiduspider)/i';

    /**
     * Cooldown period in minutes to prevent visit inflation.
     */
    protected const COOLDOWN_MINUTES = 30;

    /**
     * Record a genuine page visit.
     */
    public function record(Request $request): JsonResponse
    {
        if ($request->isMethod('options') || $request->isMethod('head')) {
            return response()->json(['success' => true], 200);
        }

        $userAgent = (string) $request->userAgent();

        // 1. Bot & Crawler Detection
        if (! empty($userAgent) && preg_match(self::BOT_REGEX, $userAgent)) {
            return response()->json([
                'success' => true,
                'is_new' => false,
                'bot' => true,
                'total_visits' => PageVisit::count(),
            ]);
        }

        // 2. Anonymized IP Fingerprint
        $clientIp = $request->ip() ?? '127.0.0.1';
        $ipHash = hash('sha256', $clientIp . (string) config('app.key'));

        // 3. Cooldown check (prevent inflation from rapid refreshes)
        $isRecent = PageVisit::query()
            ->recentForIp($ipHash, self::COOLDOWN_MINUTES)
            ->exists();

        if ($isRecent) {
            return response()->json([
                'success' => true,
                'is_new' => false,
                'cooldown' => true,
                'total_visits' => PageVisit::count(),
            ]);
        }

        // 4. Record new legitimate visit
        $path = substr((string) ($request->input('path') ?? $request->path()), 0, 255);
        $referer = substr((string) $request->header('referer', ''), 0, 500);

        PageVisit::create([
            'ip_hash' => $ipHash,
            'path' => $path ?: '/',
            'user_agent' => substr($userAgent, 0, 500),
            'referer' => $referer ?: null,
        ]);

        return response()->json([
            'success' => true,
            'is_new' => true,
            'total_visits' => PageVisit::count(),
        ], 201);
    }

    /**
     * Get real visit statistics.
     */
    public function stats(): JsonResponse
    {
        $totalVisits = PageVisit::count();
        $todayVisits = PageVisit::whereDate('created_at', today())->count();
        $yesterdayVisits = PageVisit::whereDate('created_at', today()->subDay())->count();
        $last7Days = PageVisit::where('created_at', '>=', now()->subDays(7))->count();
        $uniqueVisitors = PageVisit::distinct('ip_hash')->count('ip_hash');

        // Growth rate calculation compared to yesterday
        if ($yesterdayVisits > 0) {
            $growth = round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100, 1);
            $growthFormatted = ($growth >= 0 ? '+' : '') . $growth . '%';
        } elseif ($todayVisits > 0) {
            $growthFormatted = '+100%';
        } else {
            $growthFormatted = '0%';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_visits' => $totalVisits,
                'today_visits' => $todayVisits,
                'yesterday_visits' => $yesterdayVisits,
                'last_7_days' => $last7Days,
                'unique_visitors' => $uniqueVisitors,
                'growth_rate' => $growthFormatted,
            ],
        ]);
    }
}
