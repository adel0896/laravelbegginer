@extends('layouts.app')
@section('title', 'Blog Posts')
@section('content')



    {{-- @each('posts.partials.post', $posts, 'post') --}}


    {{-- this one is instead of saying  for each post do smth or elso do smth else --}}
    <div class='row'>
        <div class='col-8'>
            @forelse ($posts as $key => $post)
                {{-- takes all the proprieties from the loop and goes to a separate partial --}}
                @include('posts.partials.post', [])
            @empty
                No posts found!
            @endforelse
        </div>
        <div class='col-4'>
            <div class='container'>
                <div class='row'>

                    {{-- before separating it into components --}}
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Commented</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                What people are currently talking about
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $post->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    <x-card title="Most Commented" subtitle="What people are currently talking about"
                        :items="$mostCommented->pluck('title')">
                    </x-card>
                </div>
                <div class='row mt-4'>
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Users with most posts written
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostActive as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    <x-card title="Most Active" subtitle="Users with most posts written"
                        :items="$mostActive->pluck('name')">
                        {{-- <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $mostActive->pluck('name') }}
                        </a> --}}
                    </x-card>
                    {{-- @slot('items', collect($mostActive)->pluck('name')) --}}
                </div>
                <div class='row mt-4'>
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active Last Month</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Users that were most active last month
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostActiveLastMonth as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    <x-card title="Most Active Last Month" subtitle="Users that were most active last month"
                        :items="$mostActiveLastMonth->pluck('name')">
                    </x-card>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
