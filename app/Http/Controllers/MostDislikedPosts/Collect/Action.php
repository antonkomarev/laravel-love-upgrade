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
            ->joinReactionCounterOfType(ReactionType::fromName('Dislike'))
            ->with([
                'tags',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
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
