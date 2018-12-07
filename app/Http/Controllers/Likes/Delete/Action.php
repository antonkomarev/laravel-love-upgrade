<?php

namespace App\Http\Controllers\Likes\Delete;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $liker = Author::query()->whereKey($request->input('author_id'))->firstOrFail();
        $likeable = Post::query()->whereKey($request->input('post_id'))->firstOrFail();

        try {
            $liker->unlike($likeable);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }
}
