<?php

namespace App\Http\Controllers\FreshPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->with([
                'tags',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
            ])
            ->live()
            ->orderBy('publish_date', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Fresh Posts',
            'description' => 'Posts sorted by publish date from new to old',
            'posts' => $posts,
        ]);
    }
}
