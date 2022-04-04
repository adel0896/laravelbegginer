@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-4">
            <img src="{{ $user->image ? $user->image->url() : '' }}" alt="image avatar" class='img-thumbnail avatar'>
        </div>
        <div class="col-8">
            <h3>{{ $user->name }}</h3>
            <x-commentForm route="{{ route('users.comments.store', ['user' => $user->id]) }}"></x-commentForm>

            <x-comment-list :comments="$user->comments"></x-comment-list>
        </div>

    </div>
@endsection
