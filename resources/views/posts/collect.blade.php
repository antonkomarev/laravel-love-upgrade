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
                                uri="/likes/?post_id={{ $post->id }}&author_id={{ auth('wink')->user()->id }}"
                                :is-liked="@json($post->isLikedBy(auth('wink')->user()->id))"
                            >
                                <i class="fas fa-heart"></i>
                            </cog-love-react-component>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
