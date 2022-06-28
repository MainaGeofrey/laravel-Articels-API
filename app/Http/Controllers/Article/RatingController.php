<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Article;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
        //
        public function __construct()
        {
            $this->middleware('auth:api');
        }
        public function store(Request $request, Article $article)
        {
            $rating = Rating::firstOrCreate([// firstOrCreate() is a method from the Eloquent model
                //that checks if a comment exists for a given user and article, and if not, creates a new one.
                'user_id' => $request->user()->id,
                'article_id' => $article->id,
            ],
            [
                'rating' => $request->rating,
            ]);

            return new RatingResource($rating);
        }
}

