@extends('layouts.app')
@section('content')
    <form action="{{ route('users.update', ['user' => $user->id]) }}" method='post' enctype='multipart/form-data'
        class="form-horizontal">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-4">
                <img src="{{ $user->image ? $user->image->url() : '' }}" alt="image avatar" class='img-thumbnail avatar'>
                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input type="file" class="form-control-file" name='avatar'>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div>
                    <label for="">Name:</label>
                    <input type="text" class='form-control' value='' name='name'>
                </div>

                <x-errors></x-errors>
                <div>
                    <input type="submit" class='btn btn-primary' value='Save changes'>
                </div>
            </div>
        </div>
    </form>
@endsection
