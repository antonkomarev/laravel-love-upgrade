<?php

declare(strict_types=1);

namespace App\Console\Commands\Install;

use App\Models\Post;
use App\User;
use Cog\Contracts\Love\ReactionType\Exceptions\ReactionTypeInvalid;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'love:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createReactionTypes();
        $this->createReacters();
        $this->createReactants();
    }

    private function createReactionTypes(): void
    {
        $names = [
            'Like',
            'Dislike',
        ];

        $weights = [
            'Like' => 1,
            'Dislike' => -1,
        ];

        foreach ($names as $name) {
            if (ReactionType::query()->where('name', $name)->exists()) {
                continue;
            }

            ReactionType::query()->create([
                'name' => $name,
                'weight' => $weights[$name],
            ]);
        }
    }

    private function createReacters(): void
    {
        /** @var \App\User[] $users */
        $users = User::query()->get();
        foreach ($users as $user) {
            if ($user->love_reacter_id !== 0 && !is_null($user->love_reacter_id)) {
                continue;
            }

            $reacter = $user->reacter()->create([
                'type' => $user->getMorphClass(),
            ]);
            $user->setAttribute('love_reacter_id', $reacter->getKey());
            $user->save();
        }
    }

    private function createReactants(): void
    {
        /** @var \App\Models\Post[] $posts */
        $posts = Post::query()->get();
        foreach ($posts as $post) {
            if ($post->love_reactant_id !== 0 && !is_null($post->love_reactant_id)) {
                continue;
            }

            $reactant = $post->reactant()->create([
                'type' => $post->getMorphClass(),
            ]);
            $post->setAttribute('love_reactant_id', $reactant->getKey());
            $post->save();
        }
    }
}
