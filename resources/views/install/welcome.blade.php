@extends('install.layout')

@section('title', 'Welcome')

@section('content')
    <div class="installer__content">
        <h2>Welcome to the installer</h2>
        <p>
            This wizard will guide you through the installation process, check server requirements,
            verify folder permissions, and help you configure your environment.
        </p>
        <div class="installer__actions">
            <a class="btn btn-primary" href="{{ route('install.requirements') }}">
                Start Installation
            </a>
        </div>
    </div>
@endsection
