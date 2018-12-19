<?php

namespace App\Http\Controllers\MostReactedPosts\Collect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = [];

        return view('posts.collect', [
            'title' => 'Most Reacted Posts',
            'description' => 'Posts sorted descending by total reactions count (likes + dislikes). Unavailable in Love v5',
            'posts' => $posts,
        ]);
    }
}
