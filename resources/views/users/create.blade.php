@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dodaj novog korisnika</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Ime</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Lozinka</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role">Uloga</label>
            <select name="role" class="form-control" required>
                <option value="admin">Administrator</option>
                <option value="anonimus">Anonimus</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Dodaj korisnika</button>
    </form>
</div>
@endsection
