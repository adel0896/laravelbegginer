<div>
    <label for="title" class="form-label">Title</label>
    <input class="form-control" type="text" id="title" name="title"
        value="{{ old('title', optional($post ?? null)->title) }}">
</div>
@error('title')
    <div class="alert alert-danger mt-3">{{ $message }}</div>
@enderror
<div>
    <label for="content" class="form-label">Content</label>

    <textarea class="form-control" id="content" name="content" cols="30"
        rows="10">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
<div>
    <label for="title" class="form-label">Thumbnail</label>
    <input class="form-control-file" type="file" id="title" name="thumbnail" ">
</div>
<x-errors></x-errors>
{{-- before having a component for it --}}
{{-- @if ($errors->any())
    <div class="mb-3 mt-3">
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
