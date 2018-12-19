<?php

namespace App\Http\Controllers\MostReactedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->withReactionSummary()
            ->with('tags', 'reactant.reactions.reacter.reacterable', 'reactant.reactions.type')
            ->live()
            ->orderBy('reactions_total_count', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Most Reacted Posts',
            'description' => 'Posts sorted descending by total reactions count (likes + dislikes)',
            'posts' => $posts,
        ]);
    }
}
