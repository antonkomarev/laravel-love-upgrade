@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">{{ $title }}</h1>
    <h5 class="text-center">{{ $description }}</h5>
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
                                :is-reacted="@json(Love::isReactableReactedByWithTypeName($post, auth()->user(), 'Like'))"
                                :reaction-count="{{ Love::getReactableReactionsCountForTypeName($post, 'Like') }}"
                                active-icon="fas fa-thumbs-up"
                                inactive-icon="far fa-thumbs-up"
                                button-class="btn btn-outline-success"
                                active-text="Unlike"
                                inactive-text="Like"
                            ></cog-love-reaction-component>
                            <cog-love-reaction-component
                                uri="/dislikes/?post_id={{ $post->id }}"
                                :is-reacted="@json(Love::isReactableReactedByWithTypeName($post, auth()->user(), 'Dislike'))"
                                :reaction-count="{{ Love::getReactableReactionsCountForTypeName($post, 'Dislike') }}"
                                active-icon="fas fa-thumbs-down"
                                inactive-icon="far fa-thumbs-down"
                                button-class="btn btn-outline-danger"
                                active-text="Undislike"
                                inactive-text="Dislike"
                            ></cog-love-reaction-component>
                            <cog-love-reaction-total-component
                                :count="{{ Love::getReactableReactionsTotalCount($post) }}"
                                :weight="{{ Love::getReactableReactionsTotalWeight($post) }}"
                            ></cog-love-reaction-total-component>
                        </div>
                        <div>
                            @foreach ($post->getReactant()->getReactions() as $reaction)
                                <span class="badge-pill @if (Love::isReactionOfTypeName($reaction, 'Like')) badge-success @elseif (Love::isReactionOfTypeName($reaction, 'Dislike')) badge-danger @endif">
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
