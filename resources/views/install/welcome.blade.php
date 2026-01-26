@extends('install.layout')

@section('title', 'Welcome')

@section('content')
    <div class="installer__content">
        <h2>Welcome</h2>
        <p>
            Before getting started, we need some information on the database. You will need to know the
            following items before proceeding.
        </p>
        <div class="form-group">
            <label for="language">Language</label>
            <select id="language" name="language">
                <option value="en">English - en</option>
            </select>
        </div>
        <div class="installer__actions">
            <a class="btn btn-primary" href="{{ route('install.requirements') }}">
                Let's go
            </a>
        </div>
    </div>
@endsection
