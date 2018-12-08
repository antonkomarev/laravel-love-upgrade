<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableContract;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableContract;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class Upgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'love:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade love package from v5 to v6';

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
    public function handle(): void
    {
        $this->createReactionTypes();
        $this->createReacters();
        $this->createReactants();
        $this->convertLikesToReactions();
    }

    private function createReactionTypes(): void
    {
        $names = $this->collectLikeTypes();
        $weights = [
            'Like' => 1,
            'Dislike' => -1,
        ];

        foreach ($names as $name) {
            $name = $this->reactionTypeNameFromLikeTypeName($name);

            if (!isset($weights[$name])) {
                $this->warn("Reaction weight for type `{$name}` not found.");
                continue;
            }

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
        $classes = $this->collectLikerTypes();
        // TODO: Get User class from auth config

        $reacterableClasses = [];
        foreach ($classes as $class) {
            if (!class_exists($class)) {
                $this->warn("Class `{$class}` is not found.");
                continue;
            }

            if (!in_array(ReacterableContract::class, class_implements($class))) {
                $this->warn("Class `{$class}` need to implement Reacterable contract.");
                continue;
            }

            $reacterableClasses[] = $class;
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
        $classes = $this->collectLikeableTypes();

        $reactableClasses = [];
        foreach ($classes as $class) {
            $actualClass = Relation::getMorphedModel($class);
            if (!is_null($actualClass)) {
                $class = $actualClass;
            }

            if (!class_exists($class)) {
                $this->warn("Class `{$class}` is not found.");
                continue;
            }

            if (!in_array(ReactableContract::class, class_implements($class))) {
                $this->warn("Class `{$class}` need to implement Reactable contract.");
                continue;
            }

            $reactableClasses[] = $class;
        }

        foreach ($reactableClasses as $class) {
            $reactables = $class::query()->get();
            foreach ($reactables as $reactable) {
                if ($reactable->love_reactant_id !== 0 && !is_null($reactable->love_reactant_id)) {
                    continue;
                }

                $reactant = $reactable->reactant()->create([
                    'type' => $reactable->getMorphClass(),
                ]);
                $reactable->setAttribute('love_reactant_id', $reactant->getKey());
                $reactable->save();
            }
        }
    }

    private function convertLikesToReactions(): void
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $query = DB::query();
        $likes = $query
            ->select('*')
            ->from('love_likes')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($likes as $like) {
            $class = $like->likeable_type;
            $actualClass = Relation::getMorphedModel($class);
            if (!is_null($actualClass)) {
                $class = $actualClass;
            }

            if (!class_exists($class)) {
                $this->warn("Class `{$class}` is not found.");
                continue;
            }

            $reactable = $class::whereKey($like->likeable_id)->firstOrFail();

            $userClass = $this->getUserClass();

            if (!class_exists($class)) {
                $this->warn("Class `{$userClass}` is not found.");
                continue;
            }

            $reacterable = $userClass::whereKey($like->user_id)->firstOrFail();
            $reactionTypeName = $this->reactionTypeNameFromLikeTypeName($like->type_id);

            $reactionTypeId = ReactionType::fromName($reactionTypeName)->getKey();
            $reactantId = $reactable->getReactant()->getKey();
            $reacterId = $reacterable->getReacter()->getKey();

            $isReactionExists = Reaction::query()
                ->where('reaction_type_id', $reactionTypeId)
                ->where('reactant_id', $reactantId)
                ->where('reacter_id', $reacterId)
                ->exists();

            if ($isReactionExists) {
                continue;
            }

            $reaction = new Reaction();
            $reaction->forceFill([
                'reaction_type_id' => $reactionTypeId,
                'reactant_id' => $reactantId,
                'reacter_id' => $reacterId,
                'created_at' => $like->created_at,
                'updated_at' => $like->updated_at,
            ]);
            $reaction->save();
        }
    }

    private function collectLikeableTypes(): iterable
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $query = DB::query();
        $types = $query
            ->select('likeable_type')
            ->from('love_likes')
            ->groupBy('likeable_type')
            ->get()
            ->pluck('likeable_type');

        return $types;
    }

    private function collectLikerTypes(): iterable
    {
        return [
            $this->getUserClass(),
        ];
    }

    private function collectLikeTypes(): iterable
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $query = DB::query();
        $types = $query
            ->select('type_id')
            ->from('love_likes')
            ->groupBy('type_id')
            ->get()
            ->pluck('type_id');

        return $types;
    }

    private function getUserClass(): string
    {
        $guard = config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $class = config("auth.providers.{$provider}.model") ?? '';

        return $class;
    }

    private function reactionTypeNameFromLikeTypeName(string $name): string
    {
        return studly_case(strtolower($name));
    }
}
