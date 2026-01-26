@extends('install.layout')

@section('title', 'Permissions')

@section('content')
    <div class="installer__content">
        <h2>Folder Permissions</h2>
        <p>Make sure the following folders are writable by your web server.</p>
        <div class="installer__card">
            <ul class="checklist">
                @foreach ($paths as $path => $status)
                    <li class="{{ $status ? 'is-ok' : 'is-fail' }}">
                        <span>{{ $path }}</span>
                        <span class="status">
                            <i class="fa-solid {{ $status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            {{ $status ? 'Writable' : 'Not writable' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="installer__actions">
            <a class="btn btn-light" href="{{ route('install.requirements') }}">Back</a>
            <a class="btn btn-primary" href="{{ route('install.environment') }}">Next</a>
        </div>
    </div>
@endsection
