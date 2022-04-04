@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label>Name</label>
            <input name="name" value=" {{ old('name') }}" required
                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
            @if ($errors->has('name'))
                <span>
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <label>E-mail</label>
            <input name="email" value="{{ old('email') }}" required
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
            @if ($errors->has('email'))
                <span>
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <label>Password</label>
            <input name="password" required class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                type="password">
            @if ($errors->has('password'))
                <span>
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <label>Retyped Password</label>
            <input name="password_confirmation" required class="form-control" type="password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
@endsection
