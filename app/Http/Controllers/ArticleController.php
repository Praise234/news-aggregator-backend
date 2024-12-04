<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Preferences;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class ArticleController extends Controller
{
    /**
     * Fetch articles for the frontend with filters.
     */
    public function index(Request $request)
    {


        // Retrieve user preferences
        $preference = Preferences::where('user_id', \Auth::id())->first();


        // Start with a base query
        $query = Articles::query();

        

        // Filter by keyword
        if ($request->has('keyword') && $request->input('keyword') != '') {
            $query->where('description', 'like', '%' . $request->input('keyword') . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->input('category') != '') {
            $query->where('category',  $request->input('category') ?? $preference->categories);
        }

        // Filter by source
        if ($request->has('source') && $request->input('source') != '') {
            $query->where('source', $request->input('source') ?? $preference->sources);
        }

        // Filter by author
        if ($request->has('author') && $request->input('author') != '') {
            $query->where('author',  $request->input('author') ?? $preference->authors);
        }

        // Get paginated results, sorted by `published_at` in descending order
        $articles = $query->orderBy('published_at', 'desc')->paginate(10);

        // Return the JSON response
        return response()->json($articles);
    }

    /**
     * Get a single article by ID.
     */
    public function show($id): JsonResponse
    {
        // Fetch the article by ID
        $article = Articles::find($id);

        // Check if the article exists
        if (!$article) {
            return response()->json([
                'message' => 'Article not found.',
            ], 404);
        }

        return response()->json($article);
    }
}
