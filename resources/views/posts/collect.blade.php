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
                                :is-reacted="@json(auth()->check() && auth()->user()->hasLiked($post))"
                                :reaction-count="{{ $post->likesCount }}"
                                active-icon="fas fa-thumbs-up"
                                inactive-icon="far fa-thumbs-up"
                                button-class="btn btn-outline-success"
                                active-text="Unlike"
                                inactive-text="Like"
                            ></cog-love-reaction-component>
                            <cog-love-reaction-component
                                uri="/dislikes/?post_id={{ $post->id }}"
                                :is-reacted="@json(auth()->check() && auth()->user()->hasDisliked($post))"
                                :reaction-count="{{ $post->dislikesCount }}"
                                active-icon="fas fa-thumbs-down"
                                inactive-icon="far fa-thumbs-down"
                                button-class="btn btn-outline-danger"
                                active-text="Undislike"
                                inactive-text="Dislike"
                            ></cog-love-reaction-component>
                            <cog-love-reaction-total-component
                                :count="{{ $post->likesAndDislikes->count() }}"
                                :weight="{{ $post->likesDiffDislikesCount }}"
                            ></cog-love-reaction-total-component>
                        </div>
                        <div>
                            @foreach ($post->collectLikers() as $liker)
                                <span class="badge-pill badge-success">
                                    {{ $liker->name }}
                                </span>
                            @endforeach
                            @foreach ($post->collectDislikers() as $disliker)
                                <span class="badge-pill badge-danger">
                                    {{ $disliker->name }}
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
