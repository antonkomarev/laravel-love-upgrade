<?php

namespace App\Http\Controllers\WorstPosts\Collect;

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
            ->orderBy('reactions_total_weight', 'asc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Worst Posts',
            'description' => 'Posts sorted ascending by total reactions weight (likes - dislikes)',
            'posts' => $posts,
        ]);
    }
}
