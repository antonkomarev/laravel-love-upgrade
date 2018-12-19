<?php

namespace App\Http\Controllers\MostDislikedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->with('tags', 'likesCounter', 'likes', 'dislikesCounter', 'dislikes')
            ->live()
            ->orderByDislikesCount('desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Most Disliked Posts',
            'description' => 'Posts sorted descending by total dislikes reactions count',
            'posts' => $posts,
        ]);
    }
}
