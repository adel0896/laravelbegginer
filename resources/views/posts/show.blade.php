@extends('layouts.app')
@section('title', $post->title)
@section('content')
    {{-- @if ($post['is_new'])
        <div>A new blogpost using if</div>
    @else
        <div>Blog post is old, using else if</div>
    @endif --}}

    {{-- this one works only when the condition is false --}}
    {{-- @unless($post['is_new'])
        <div>It is an old post using unless</div>
    @endunless --}}
    @if ($post->image)
        <div
            style="background-image: url('{{ $post->image->url() }}'); min-height:500px; color:white; text-align:center; background-attachment:fixed;">
            <h1 style='padding-top:100px; text-shadow:1px 10px #000;'>
            @else
                <h1>
    @endif
    <h1>{{ $post->title }}
        {{-- @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 60) --}}
        {{-- this was before adding an alias in AppServiceProvider --}}
        {{-- @component('components.badge', ['type' => 'primary'])
            Brand new post!
        @endcomponent --}}
        <x-badge show="{{ now()->diffInMinutes($post->created_at) < 10 }}">
            Brand new post!
        </x-badge>
        {{-- @endif --}}

        @if ($post->image)
    </h1>
    </div>
@else
    </h1>
    @endif
    </h1>

    <p>{{ $post->content }}</p>
    {{-- <img src="{{ $post->image->url() }}" alt="image of man"> --}}
    {{-- <p>Added {{ $post->created_at->diffForHumans() }} </p> --}}
    <x-updated date="{{ $post->created_at }}" name="{{ $post->user->name }}">
    </x-updated>
    <x-updated date="{{ $post->updated_at }}">
        Post updated
    </x-updated>
    <x-tags :tags="$post->tags">
    </x-tags>
    <p>Currently read by {{ $counter }} people</p>

    <h4>Comments</h4>

    {{-- this was for when i had a partial, turned the form into a component --}}
    {{-- @include('comments._form') --}}

    <x-commentForm route="{{ route('posts.comments.store', ['post' => $post->id]) }}"></x-commentForm>

    <x-commentList :comments="$post->comments"></x-commentList>

    {{-- converted into a component as well --}}
    {{-- @forelse($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p> --}}
    {{-- <p class="text-muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p> --}}
    {{-- <x-updated date="{{ $comment->created_at }}" name="{{ $comment->user->name }}">
        </x-updated>
    @empty
        <p>No comments yet!</p>
    @endforelse --}}



    {{-- @isset($post['has_comments'])
        <div>The post has some comments using Isset</div>
    @endisset --}}

@endsection
