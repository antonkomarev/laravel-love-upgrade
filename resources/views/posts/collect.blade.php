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
                        <div>
                            <cog-love-react-component
                                uri="/likes/?post_id={{ $post->id }}"
                                :is-liked="@json(auth()->check() && auth()->user()->hasLiked($post))"
                            >
                                <i class="fas fa-heart"></i>
                            </cog-love-react-component>
                            <cog-love-reaction-counter-component
                                count="{{ $post->likesCount }}"
                            ></cog-love-reaction-counter-component>
                        </div>
                        <div>
                            @foreach ($post->collectLikers() as $liker)
                                <span class="badge-pill badge-secondary">
                                    {{ $liker->name }}
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
