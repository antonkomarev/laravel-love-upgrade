<?php

use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $posts = \App\Models\Post::all();
        $users = \App\User::all();

        foreach ($posts as $post) {
            // 3/4 of posts will have likes
            if (mt_rand(0, 3)) {
                foreach ($users as $user) {
                    if (mt_rand(0, 1)) {
                        $post->likeBy($user->id);
                    }
                }
            }

            // 1/4 of posts will have dislikes
            if (!mt_rand(0, 3)) {
                foreach ($users as $user) {
                    if (!mt_rand(0, 4)) {
                        $post->dislikeBy($user->id);
                    }
                }
            }
        }
    }
}
