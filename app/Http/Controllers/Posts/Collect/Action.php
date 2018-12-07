<?php

namespace App\Http\Controllers\Posts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\Post[] $posts */
        $posts = Post::query()
            ->with('tags')
            ->live()
            ->orderBy('publish_date', 'DESC')
            ->simplePaginate(12);

        return view('posts.collect', [
            'posts' => $posts,
        ]);
    }
}
