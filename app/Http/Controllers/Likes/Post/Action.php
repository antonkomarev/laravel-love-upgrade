<?php

namespace App\Http\Controllers\Likes\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $likerable = $request->user();
        $likeable = Post::query()->whereKey($request->input('post_id'))->firstOrFail();

        try {
            $likerable->viaLoveReacter()->reactTo($likeable, 'Like');
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }
}
