@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($posts as $post)
                <div class="card my-4">
                    <div class="card-body">
                        <h1>{{ $post->title }}</h1>
                        <div>{!! $post->body !!}</div>
                        <div>
                            <cog-love-react-component
                                uri="/likes/?post_id={{ $post->id }}"
                                :is-liked="@json(auth()->check() && auth()->user()->getReacter()->isReactedTo($post->getReactant()))"
                            >
                                <i class="fas fa-heart"></i>
                            </cog-love-react-component>
                            <cog-love-reaction-counter-component
                                count="{{ $post->getReactant()->getReactionSummary()->getTotalCount() }}"
                            ></cog-love-reaction-counter-component>
                        </div>
                        <div>
                            @foreach ($post->getReactant()->getReactions() as $reaction)
                                <span class="badge-pill badge-secondary">
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
