@forelse($comments as $comment)
    <p>
        {{ $comment->content }}
    </p>
    {{-- <p class="text-muted">
    added {{ $comment->created_at->diffForHumans() }}
</p> --}}
    <x-tags :tags="$comment->tags">
    </x-tags>
    <x-updated date="{{ $comment->created_at }}" name="{{ $comment->user->name }}" userId="
        {{ $comment->user->id }}">
    </x-updated>
@empty
    <p>No comments yet!</p>
@endforelse
