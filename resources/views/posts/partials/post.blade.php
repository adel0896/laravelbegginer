  {{-- this interupt the array at different points --}}
  {{-- @break($key == 2) --}}
  {{-- @continue($key == 1) --}}


  {{-- this works when it is included in the parent and it inherits proprieties --}}
  {{-- @if ($loop->even) --}}
  <h3>
      @if ($post->trashed())
          <del>
      @endif
      <a class='{{ $post->trashed() ? 'text-muted' : '' }}' href="{{ route('posts.show', ['post' => $post->id]) }}">
          {{ $post->title }}</a>
      @if ($post->trashed())
          </del>
      @endif
  </h3>


  {{-- <p class="text-muted">
      Added {{ $post->created_at->diffForHumans() }}
      by {{ $post->user->name }}
  </p> --}}
  <x-updated date="{{ $post->created_at }}" name="{{ $post->user->name }}" userId="{{ $post->user->id }}">
  </x-updated>
  <x-tags :tags="$post->tags">
  </x-tags>
  @if ($post->comments_count)
      <p>{{ $post->comments_count }}comments</p>
  @else
      <p>No comments yet!</p>
  @endif



  {{-- @else
      <div style="background-color:silver">{{ $key }}.{{ $post->title }}</div>
  @endif --}}

  <div class="mb-3">
      @can('update', $post)
          <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit Post</a>
      @endcan

      @if (!$post->trashed())
          @can('delete', $post)
              <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <input type="submit" value="Delete" class="btn btn-primary" />
              </form>
          @endcan
      @endif
  </div>

  {{-- this works when we use the each method in the parent --}}
  {{-- <div>{{ $key }}.{{ $post['title'] }}</div> --}}
