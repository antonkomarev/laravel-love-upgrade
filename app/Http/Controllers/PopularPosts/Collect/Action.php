<?php

namespace App\Http\Controllers\PopularPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->joinReactionTotal()
            ->with([
                'tags',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
            ])
            ->live()
            ->orderBy('reaction_total_weight', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Popular Posts',
            'description' => 'Posts sorted descending by total reactions weight (likes - dislikes)',
            'posts' => $posts,
        ]);
    }
}
