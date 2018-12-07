<?php

namespace App\Http\Controllers\Posts\Collect;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\User;
use Illuminate\Http\Request;

class Action extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\User[] $users */
        $users = User::query()->get();
        foreach ($users as $user) {
            if ($user->love_reacter_id === 0 || is_null($user->love_reacter_id)) {
                $reacter = $user->reacter()->create([
                    'type' => $user->getMorphClass(),
                ]);
                $user->setAttribute('love_reacter_id', $reacter->getKey());
                $user->save();
            }
        }

        /** @var \App\Models\Post[] $posts */
        $posts = Post::query()
            ->with('tags')
            ->live()
            ->orderBy('publish_date', 'DESC')
            ->simplePaginate(12);

        foreach ($posts as $post) {
            if ($post->love_reactant_id === 0 || is_null($post->love_reactant_id)) {
                $reactant = $post->reactant()->create([
                    'type' => $post->getMorphClass(),
                ]);
                $post->setAttribute('love_reactant_id', $reactant->getKey());
                $post->save();
            }
        }

        return view('posts.collect', [
            'posts' => $posts,
        ]);
    }
}
