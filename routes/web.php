<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;

// Profile routes for edit, update, and delete
Route::middleware(['auth'])->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes (Breeze)
require __DIR__.'/auth.php';

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Categories
Route::resource('categories', CategoryController::class)->middleware(['auth']);

// Budgets
Route::resource('budgets', BudgetController::class)->middleware(['auth']);

// Expenses
Route::resource('expenses', ExpenseController::class)->middleware(['auth']);

// Reports
Route::get('/reports', [ReportController::class, 'index'])->middleware(['auth'])->name('reports.index');
Route::post('/reports/filter', [ReportController::class, 'filter'])->middleware(['auth'])->name('reports.filter');
