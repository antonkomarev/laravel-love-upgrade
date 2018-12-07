<?php

declare(strict_types=1);

namespace App\Console\Commands\Install;

use App\Models\Post;
use App\User;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableContract;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableContract;
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
//        $classes = get_declared_classes();
        $classes = [
            User::class,
        ];

        $reacterableClasses = [];
        foreach ($classes as $class) {
            if (in_array(ReacterableContract::class, class_implements($class))) {
                $reacterableClasses[] = $class;
            }
        }

        foreach ($reacterableClasses as $class) {
            $reacterables = $class::query()->get();
            foreach ($reacterables as $reacterable) {
                if ($reacterable->love_reacter_id !== 0 && !is_null($reacterable->love_reacter_id)) {
                    continue;
                }

                $reacter = $reacterable->reacter()->create([
                    'type' => $reacterable->getMorphClass(),
                ]);
                $reacterable->setAttribute('love_reacter_id', $reacter->getKey());
                $reacterable->save();
            }
        }
    }

    private function createReactants(): void
    {
//        $classes = get_declared_classes();
        $classes = [
            Post::class,
        ];

        $reactableClasses = [];
        foreach ($classes as $class) {
            if (in_array(ReactableContract::class, class_implements($class))) {
                $reactableClasses[] = $class;
            }
        }

        foreach ($reactableClasses as $class) {
            $reactables = $class::query()->get();
            foreach ($reactables as $reactable) {
                if ($reactable->love_reactant_id !== 0 && !is_null($reactable->love_reactant_id)) {
                    continue;
                }

                $reacter = $reactable->reacter()->create([
                    'type' => $reactable->getMorphClass(),
                ]);
                $reactable->setAttribute('love_reactant_id', $reacter->getKey());
                $reactable->save();
            }
        }
    }
}
