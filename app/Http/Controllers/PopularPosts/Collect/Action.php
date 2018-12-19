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
            ->withReactionSummary()
            ->with([
                'tags',
                'reactant.reactions.reacter.reacterable',
                'reactant.reactions.type',
                'reactant.reactionCounters',
                'reactant.reactionSummary',
            ])
            ->live()
            ->orderBy('reactions_total_weight', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Popular Posts',
            'description' => 'Posts sorted descending by total reactions weight (likes - dislikes)',
            'posts' => $posts,
        ]);
    }
}
