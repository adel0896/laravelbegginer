@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

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
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember"
                    value="{{ old('remember') ? 'checked' : '' }}">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
@endsection
