<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'nurse') {
            return redirect()->route('nurse.dashboard');
        }
    }
    return view('welcome'); // only show welcome if not logged in
});

//update
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/nurse/dashboard', [NurseController::class, 'index'])->name('nurse.dashboard');

    //for nurse inventory
    Route::get('/nurse/inventory', [NurseController::class, 'inventory'])->name('nurse.inventory');

    //transactions
    Route::get('/nurse/day-transactions', [NurseController::class, 'dayTransactions'])->name('nurse.day.transactions');
    Route::post('/nurse/sale', [NurseController::class, 'storeSale'])->name('nurse.sale.store');

    Route::get('/admin/drugs/create', [AdminController::class, 'create'])->name('admin.drugs.create');
    Route::post('/admin/drugs', [AdminController::class, 'store'])->name('admin.drugs.store');
    Route::get('/admin/drugs/{id}/edit', [AdminController::class, 'edit'])->name('admin.drugs.edit');
    Route::put('/admin/drugs/{id}', [AdminController::class, 'update'])->name('admin.drugs.update');
    Route::delete('/admin/drugs/{id}', [AdminController::class, 'destroy'])->name('admin.drugs.destroy');

    Route::put('/admin/drugs/{drug}', [AdminController::class, 'update'])->name('admin.drugs.update');



});

Route::put('/admin/drugs/{drug}', [AdminController::class, 'update'])->name('admin.drugs.update');
Route::prefix('admin')->group(function () {
    //Route::resource('drugs', AdminController::class);
});

//for the report
Route::prefix('admin')->group(function() {
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('admin.reports.show');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('admin.reports.download');
    Route::post('/reports/generate', [ReportController::class, 'storeFromTransactions'])->name('admin.reports.generate');
});

Route::get('/admin/sales', [AdminController::class, 'sale'])->name('admin.sales');



require __DIR__.'/auth.php';
