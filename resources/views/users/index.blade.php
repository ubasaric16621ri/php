@extends('layouts.app')

@section('content')
<div class="container">


    <h1>Lista korisnika</h1>

    <!-- Forma za filtriranje -->
    <form method="GET" action="{{ route('users.index') }}">
        <div class="form-group">
            <label for="from_date">Datum od:</label>
            <input type="date" name="from_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="to_date">Datum do:</label>
            <input type="date" name="to_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="role">Uloga:</label>
            <select name="role" class="form-control">
                <option value="">Svi</option>
                <option value="admin">Administrator</option>
                <option value="anonimus">Anonimus</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtriraj</button>
        <a href="{{ route('users.export') }}" class="btn btn-success">Eksportuj korisnike u Excel</a>
    </form>

    <br>

    <!-- Prikaz korisnika -->
    <table class="table">
        <thead>
            <tr>
                <th>Ime</th>
                <th>Email</th>
                <th>Uloga</th>
                <th>Datum kreiranja</th>
                <th>Akcije</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <!-- Forma za brisanje korisnika unutar foreach petlje -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Obriši</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginacija sa centriranjem (jedinstvena) -->
    <div class="d-flex justify-content-center">
      {{ $users->links('pagination::bootstrap-4') }}

    </div>

    <br>

 <!-- Forma za import korisnika -->
 <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
     @csrf
     <div class="form-group">
         <label for="file">Importuj korisnike (CSV/Excel):</label>
         <input type="file" name="file" class="form-control" required>
     </div>
     <button type="submit" class="btn btn-primary">Importuj</button>
 </form>

    <br>

    <!-- Smanjen pie chart -->
    <h3>Broj korisnika po ulogama</h3>
    <div style="max-width: 400px; margin: 0 auto;">
        <canvas id="roleChart"></canvas>
    </div> <!-- Ograničene dimenzije chart-a -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('roleChart').getContext('2d');
        var roleChart = new Chart(ctx, {
            type: 'pie', // Promenjen tip grafikona u "pie"
            data: {
                labels: ['Administrator', 'Anonimus'],
                datasets: [{
                    label: 'Broj korisnika',
                    data: [{{ $adminCount }}, {{ $anonimusCount }}], // Podaci o broju korisnika
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Da grafikon bude prilagodljiv
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                maintainAspectRatio: false // Onemogućava fiksni odnos stranica
            }
        });
    </script>
</div>
@endsection
