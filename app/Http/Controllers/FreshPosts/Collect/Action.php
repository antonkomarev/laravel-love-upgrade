<?php

namespace App\Http\Controllers\FreshPosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->with('tags', 'reactant.reactions.reacter.reacterable')
            ->live()
            ->orderBy('publish_date', 'DESC')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Fresh Posts',
            'posts' => $posts,
        ]);
    }
}
