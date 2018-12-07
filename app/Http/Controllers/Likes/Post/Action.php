<?php

namespace App\Http\Controllers\Likes\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Wink\WinkAuthor;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $liker = WinkAuthor::query()->whereKey($request->input('author_id'))->firstOrFail();
        $likeable = Post::query()->whereKey($request->input('post_id'))->firstOrFail();

        try {
            $liker->like($likeable);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }
}
