<?php

namespace App\Http\Controllers\LikedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->whereReactedWithTypeBy($request->user()->getReacter(), ReactionType::fromName('Like'))
            ->with('tags', 'reactant.reactions.reacter.reacterable', 'reactant.reactions.type')
            ->live()
            ->orderBy('publish_date', 'desc')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Posts I like',
            'description' => 'Posts liked by the user',
            'posts' => $posts,
        ]);
    }
}
