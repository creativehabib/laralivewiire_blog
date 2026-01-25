@extends('install.layout')

@section('title', 'Requirements')

@section('content')
    <div class="installer__content">
        <h2>Server Requirements</h2>
        <p>Ensure the following requirements are satisfied before continuing.</p>
        <div class="installer__card">
            <ul class="checklist">
                @foreach ($requirements as $label => $status)
                    <li class="{{ $status ? 'is-ok' : 'is-fail' }}">
                        <span>{{ $label }}</span>
                        <span class="status">
                            <i class="fa-solid {{ $status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            {{ $status ? 'OK' : 'Missing' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="installer__actions">
            <a class="btn btn-light" href="{{ route('install.index') }}">Back</a>
            <a class="btn btn-primary" href="{{ route('install.permissions') }}">Next</a>
        </div>
    </div>
@endsection
