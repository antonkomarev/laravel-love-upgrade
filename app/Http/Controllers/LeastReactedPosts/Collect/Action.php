<?php

namespace App\Http\Controllers\LeastReactedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->withReactionTotal()
            ->with([
                'tags',
                'reactant.reactions.reacter.reacterable',
                'reactant.reactions.type',
                'reactant.reactionCounters',
                'reactant.reactionTotal',
            ])
            ->live()
            ->orderBy('reactions_total_count', 'asc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Least Reacted Posts',
            'description' => 'Posts sorted ascending by total reactions count (likes + dislikes)',
            'posts' => $posts,
        ]);
    }
}
