@extends('install.layout')

@section('title', 'Installation Complete')

@section('content')
    <div class="installer__content">
        <div class="installer__success">
            <i class="fa-solid fa-circle-check"></i>
            <h2>Installation Complete</h2>
            <p>Your application has been installed successfully.</p>
        </div>
        <div class="installer__actions">
            <a class="btn btn-primary" href="{{ route('home') }}">Go to Homepage</a>
        </div>
    </div>
@endsection
