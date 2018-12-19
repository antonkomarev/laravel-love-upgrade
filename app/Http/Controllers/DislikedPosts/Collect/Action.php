<?php

namespace App\Http\Controllers\DislikedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->whereDislikedBy($request->user()->id)
            ->with([
                'tags',
                'likes',
                'dislikes',
                'likesAndDislikes',
                'likesCounter',
                'dislikesCounter',
            ])
            ->live()
            ->orderBy('publish_date', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Posts I dislike',
            'description' => 'Posts disliked by the user',
            'posts' => $posts,
        ]);
    }
}
