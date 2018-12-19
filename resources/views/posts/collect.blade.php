@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">{{ $title }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($posts as $post)
                <div class="card my-4">
                    <div class="card-body">
                        <h1>{{ $post->title }}</h1>
                        <div>{!! $post->body !!}</div>
                        <div class="my-2">
                            <cog-love-reaction-component
                                uri="/likes/?post_id={{ $post->id }}"
                                :is-reacted="@json(auth()->check() && auth()->user()->getReacter()->isReactedWithTypeTo($post->getReactant(), \Cog\Laravel\Love\ReactionType\Models\ReactionType::fromName('Like')))"
                                :reaction-count="{{ optional($post->getReactant()->getReactionCounters()->where('reaction_type_id', \Cog\Laravel\Love\ReactionType\Models\ReactionType::fromName('Like')->getKey())->first())->getCount() }}"
                                active-icon="fas fa-thumbs-up"
                                inactive-icon="far fa-thumbs-up"
                                button-class="btn btn-outline-success"
                                active-text="Unlike"
                                inactive-text="Like"
                            ></cog-love-reaction-component>
                            <cog-love-reaction-component
                                uri="/dislikes/?post_id={{ $post->id }}"
                                :is-reacted="@json(auth()->check() && auth()->user()->getReacter()->isReactedWithTypeTo($post->getReactant(), \Cog\Laravel\Love\ReactionType\Models\ReactionType::fromName('Dislike')))"
                                :reaction-count="{{ optional($post->getReactant()->getReactionCounters()->where('reaction_type_id', \Cog\Laravel\Love\ReactionType\Models\ReactionType::fromName('Dislike')->getKey())->first())->getCount() }}"
                                active-icon="fas fa-thumbs-down"
                                inactive-icon="far fa-thumbs-down"
                                button-class="btn btn-outline-danger"
                                active-text="Undislike"
                                inactive-text="Dislike"
                            ></cog-love-reaction-component>
                        </div>
                        <div>
                            @foreach ($post->getReactant()->getReactions() as $reaction)
                                <span class="badge-pill @if ($reaction->getType()->getName() === 'Dislike') badge-danger @endif @if ($reaction->getType()->getName() === 'Like') badge-success @endif">
                                    {{ $reaction->getReacter()->getReacterable()->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
