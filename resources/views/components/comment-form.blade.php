<div class="mb-2 mt-2">
    @auth
        <form action="{{ $route }}" method="post">
            @csrf

            <div>
                <textarea class="form-control" id="content" name="content" cols="30" rows="10"></textarea>
            </div>
            <button type='submit' class='btn btn-primary btn-block'>Add Comment</button>
        </form>

        <x-errors></x-errors>
    @else
        <a href="{{ route('login') }}"> Sign-in to post comments</a>
    @endauth
</div>
<hr />
