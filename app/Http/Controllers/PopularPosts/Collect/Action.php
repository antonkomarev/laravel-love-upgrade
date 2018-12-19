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
            ->with('tags', 'reactant.reactions.reacter.reacterable')
            ->live()
            ->orderBy('reactions_total_count', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Popular Posts',
            'posts' => $posts,
        ]);
    }
}
