<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Certificate;
use App\Models\CvDocument;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    /**
     * Get aggregated portfolio payload for interactive 3D client application.
     */
    public function show(): JsonResponse
    {
        $profile = Profile::first();

        $skills = Skill::query()
            ->ordered()
            ->get();

        $skillsByCategory = $skills->groupBy('category');

        $allProjects = Project::query()
            ->orderByDesc('created_at')
            ->get();

        $featuredProjects = $allProjects->where('is_featured', true)->values();
        if ($featuredProjects->isEmpty()) {
            $featuredProjects = $allProjects;
        }

        $articles = Article::query()
            ->published()
            ->orderByDesc('published_at')
            ->get();

        $certificates = Certificate::query()
            ->active()
            ->ordered()
            ->get();

        $socialLinks = SocialLink::query()
            ->active()
            ->ordered()
            ->get();

        $activeCv = CvDocument::query()
            ->active()
            ->first();

        $totalVisits = \App\Models\PageVisit::count();

        return response()->json([
            'success' => true,
            'data' => [
                'profile' => $profile,
                'cv' => $activeCv,
                'skills' => $skills,
                'skills_by_category' => $skillsByCategory,
                'projects' => $allProjects,
                'featured_projects' => $featuredProjects,
                'articles' => $articles,
                'certificates' => $certificates,
                'social_links' => $socialLinks,
                'total_visits' => $totalVisits,
            ],
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'version' => '1.0.0',
            ],
        ]);
    }
}
