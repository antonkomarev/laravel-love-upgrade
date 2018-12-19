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
            foreach ($users as $user) {
                if (mt_rand(0, 1)) {
                    $post->likeBy($user->id);
                }
            }
        }
    }
}
