@extends('install.layout')

@section('title', 'Create Account')

@section('content')
    <div class="installer__content">
        <h2>Create Administrator Account</h2>
        <p>Create the first administrator user. You will use this account to log in.</p>

        <form class="installer__form" method="post" action="{{ route('install.account.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>
            <div class="installer__actions">
                <a class="btn btn-light" href="{{ route('install.environment') }}">Back</a>
                <button class="btn btn-primary" type="submit">Create Account</button>
            </div>
        </form>
    </div>
@endsection
