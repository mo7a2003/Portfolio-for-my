<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::query()->published();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $articles = $query->orderByDesc('published_at')->get();

        return response()->json([
            'success' => true,
            'data' => $articles,
            'total' => $articles->count(),
        ]);
    }

    /**
     * Display the specified article by slug or ID.
     */
    public function show(string $slugOrId): JsonResponse
    {
        $article = Article::query()
            ->published()
            ->where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }
}
