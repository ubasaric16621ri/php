@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Uredi korisnika</h1>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf

        <div class="form-group">
            <label for="name">Ime</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label for="role">Uloga</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="anonimus" {{ $user->role == 'anonimus' ? 'selected' : '' }}>Anonimus</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ažuriraj korisnika</button>
    </form>
</div>
@endsection
