@extends('install.layout')

@section('title', 'Environment')

@section('content')
    <div class="installer__content">
        <h2>Environment Settings</h2>
        <p>Fill in your application and database settings.</p>

        <form class="installer__form" method="post" action="{{ route('install.environment.save') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="app_name">Application name</label>
                    <input id="app_name" name="app_name" type="text" value="{{ old('app_name', $defaults['app_name']) }}" required>
                    @error('app_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="app_url">Application URL</label>
                    <input id="app_url" name="app_url" type="url" value="{{ old('app_url', $defaults['app_url']) }}" required>
                    @error('app_url')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_connection">Database driver</label>
                    <input id="db_connection" name="db_connection" type="text" value="{{ old('db_connection', $defaults['db_connection']) }}" required>
                    @error('db_connection')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_host">Database host</label>
                    <input id="db_host" name="db_host" type="text" value="{{ old('db_host', $defaults['db_host']) }}" required>
                    @error('db_host')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_port">Database port</label>
                    <input id="db_port" name="db_port" type="text" value="{{ old('db_port', $defaults['db_port']) }}" required>
                    @error('db_port')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_database">Database name</label>
                    <input id="db_database" name="db_database" type="text" value="{{ old('db_database', $defaults['db_database']) }}" required>
                    @error('db_database')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_username">Database username</label>
                    <input id="db_username" name="db_username" type="text" value="{{ old('db_username', $defaults['db_username']) }}" required>
                    @error('db_username')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="db_password">Database password</label>
                    <input id="db_password" name="db_password" type="password" value="{{ old('db_password', $defaults['db_password']) }}">
                    @error('db_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="installer__actions">
                <a class="btn btn-light" href="{{ route('install.permissions') }}">Back</a>
                <button class="btn btn-primary" type="submit">Save &amp; Install</button>
            </div>
        </form>
    </div>
@endsection
