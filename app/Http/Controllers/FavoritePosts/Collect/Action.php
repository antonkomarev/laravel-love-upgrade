<?php

namespace App\Http\Controllers\FavoritePosts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->whereLikedBy($request->user()->id)
            ->with('tags', 'likesCounter', 'likes')
            ->live()
            ->orderBy('publish_date', 'DESC')
            ->simplePaginate(50);

        return view('posts.collect', [
            'title' => 'Favorite Posts',
            'posts' => $posts,
        ]);
    }
}
