<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

Route::get('/', function () {
    return view('welcome');
});

// Laravel auth routes
Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

// HomeController ruta sa middleware-om za autentifikaciju
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// AdminController sa middleware-om za admina
Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin');

// Users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Import korisnika
Route::post('/users/import', [UserController::class, 'import'])->name('users.import');


// Export korisnika
Route::get('/users/export', function() {
    return Excel::download(new UsersExport, 'users.xlsx');
})->name('users.export');

// Create, edit, update, delete korisnika
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Generisanje PDF izveštaja
Route::get('/users/report', [UserController::class, 'generateReport'])->name('users.report');

// Zaštićene rute sa middleware-om 'auth'
Route::group(['middleware' => ['auth']], function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
});
