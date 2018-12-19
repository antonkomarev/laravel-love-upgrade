<?php

namespace App\Http\Controllers\Dislikes\Delete;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $liker = $request->user();
        $likeable = Post::query()->whereKey($request->input('post_id'))->firstOrFail();

        try {
            $liker->undislike($likeable);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }
}
