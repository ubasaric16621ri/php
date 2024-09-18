<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    // Metod za prikaz forme za dodavanje korisnika
    public function create()
    {
        return view('users.create');
    }

    // Metod za unos korisnika u bazu
    public function store(Request $request)
    {
        // Validacija podataka
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,anonimus',
        ]);

        // Kreiranje korisnika
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Šifrovanje lozinke
            'role' => $request->role,
        ]);

        // Preusmeravanje nazad na listu korisnika
        return redirect()->route('users.index')->with('success', 'Korisnik uspešno dodat!');
    }

public function import(Request $request)
{
    // Validacija da li je fajl prosleđen i da li je ispravnog formata
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    // Importovanje korisnika
    Excel::import(new UsersImport, $request->file('file'));

    // Preusmeravanje nazad sa porukom uspeha
    return redirect()->back()->with('success', 'Korisnici uspešno importovani!');
}


    // Metod za prikaz forme za uređivanje korisnika
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Metod za ažuriranje korisnika
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validacija podataka
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,anonimus',
        ]);

        // Ažuriranje korisnika
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Korisnik uspešno ažuriran!');
    }

    // Metod za brisanje korisnika
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Korisnik uspešno obrisan!');
    }



public function generateReport()
{
    $users = User::all();

    // Kreiranje PDF fajla
    $pdf = PDF::loadView('users.report', compact('users'));

    // Preuzimanje PDF fajla
    return $pdf->download('izvestaj_korisnika.pdf');
}


    // Metod za prikaz i filtriranje korisnika
  public function index(Request $request)
  {
      $query = User::query();

      // Filtriranje po vremenskom periodu (od-do)
      if ($request->filled('from_date') && $request->filled('to_date')) {
          $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
      }

      // Filtriranje po ulozi korisnika
      if ($request->filled('role')) {
          $query->where('role', $request->role);
      }

      // Paginacija
      $users = $query->paginate(10);

      // Brojanje korisnika po ulogama
      $adminCount = User::where('role', 'admin')->count();
      $anonimusCount = User::where('role', 'anonimus')->count();

      return view('users.index', compact('users', 'adminCount', 'anonimusCount'));
  }
}
