@extends('layouts.app')

@section('content')
<div class="container">
    @if (Auth::check())
        <h1>Dobrodošli, {{ Auth::user()->name }}!</h1>

        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-block">Prikaz korisnika</a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-block">Dodaj korisnika</a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('users.report') }}" class="btn btn-info btn-block">Generiši izveštaj</a>
            </div>
        </div>
    @else
        <p>Niste prijavljeni.</p>
    @endif
</div>
@endsection
