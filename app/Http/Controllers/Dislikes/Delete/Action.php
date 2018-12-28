<?php

namespace App\Http\Controllers\Dislikes\Delete;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        $liker = $request->user()->getLoveReacter();
        $likeable = Post::query()->whereKey($request->input('post_id'))->firstOrFail()->getLoveReactant();
        $reactionType = ReactionType::fromName('Dislike');

        try {
            $liker->unreactTo($likeable, $reactionType);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }
}
