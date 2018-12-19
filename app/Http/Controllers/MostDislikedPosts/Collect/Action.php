<?php

namespace App\Http\Controllers\MostDislikedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->withReactionCounterOfType(ReactionType::fromName('Dislike'))
            ->with([
                'tags',
                'reactant.reactions.reacter.reacterable',
                'reactant.reactions.type',
                'reactant.reactionCounters',
                'reactant.reactionSummary',
            ])
            ->live()
            ->orderBy('reactions_count', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Most Disliked Posts',
            'description' => 'Posts sorted descending by total dislikes reactions count',
            'posts' => $posts,
        ]);
    }
}
