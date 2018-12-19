<?php

namespace App\Http\Controllers\LeastReactedPosts\Collect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = [];

        return view('posts.collect', [
            'title' => 'Least Reacted Posts',
            'description' => 'Posts sorted ascending by total reactions count (likes + dislikes). Unavailable in Love v5',
            'posts' => $posts,
        ]);
    }
}
