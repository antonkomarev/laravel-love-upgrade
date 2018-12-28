<?php

namespace App\Http\Controllers\DislikedPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->whereReactedWithTypeBy($request->user()->getLoveReacter(), ReactionType::fromName('Dislike'))
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
            'title' => 'Posts I dislike',
            'description' => 'Posts disliked by the user',
            'posts' => $posts,
        ]);
    }
}
