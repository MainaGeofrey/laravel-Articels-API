<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    //users will be able to see a list of articles without being authenticated
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    public function index()
    {
        $articles = Article::all();
        return ArticleResource::collection($articles);
    }

    public function store(Request $request)
    {
        $article = Article::create([
            'title' => $request->title,
            'user_id' => $request->user()->id,
            'body' => $request->body,
            'description' => $request->description,
        ]);

        return new ArticleResource($article);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function update(Request $request, Article $article)
    {
        if ($request->user()->id !== $article->user_id) {
            return response()->json(['error' => 'You can only edit your own articles.'], 403);
        }

        $article->update($request->only(['title', 'body', 'description']));

        return new ArticleREsource($article);
    }

    public function destroy(Request $request, Article $article)
    {
        if ($request->user()->id !== $article->user_id) {
            return response()->json(['error' => 'You can only delete your own articles.'], 403);
        }

        $article->delete();

        return response()->json(null, 204);
    }
}
