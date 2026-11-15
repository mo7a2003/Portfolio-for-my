<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of all articles for admin.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::query();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->has('is_published')) {
            $query->where('is_published', filter_var($request->query('is_published'), FILTER_VALIDATE_BOOLEAN));
        }

        $articles = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $articles,
            'total' => $articles->count(),
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $uploadedFile = $request->file('image') ?? $request->file('cover') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('articles', 'public');
            $validated['image'] = asset('storage/'.$path);
        }

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Article::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        if (empty($validated['published_at']) && ($validated['is_published'] ?? true)) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully.',
            'data' => $article,
        ], 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        $validated = $request->validated();

        $uploadedFile = $request->file('image') ?? $request->file('cover') ?? $request->file('file');
        if ($uploadedFile) {
            $path = $uploadedFile->store('articles', 'public');
            $validated['image'] = asset('storage/'.$path);
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = $article->slug ?: Str::slug($validated['title']);
        }

        // Clean up previous image if replaced
        $newImage = $validated['image'] ?? null;
        $oldImage = $article->image;

        if ($newImage && $oldImage && $newImage !== $oldImage) {
            MediaUploadController::deleteFileSafely($oldImage);
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully.',
            'data' => $article->fresh(),
        ]);
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article): JsonResponse
    {
        if ($article->image) {
            MediaUploadController::deleteFileSafely($article->image);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully.',
        ]);
    }
}
