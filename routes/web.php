<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmiController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PaymentTypeController;

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

// EMI Plans
Route::resource('emis', EmiController::class)->middleware(['auth']);
Route::post('/emis/{installment}/pay', [EmiController::class, 'pay'])->middleware(['auth'])->name('emis.pay');

// Google SSO
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Payment Types
Route::resource('payment-types', PaymentTypeController::class)->middleware('auth');

// Scan receipts (upload & OCR)
use App\Http\Controllers\ScanController;
Route::get('scan/create', [ScanController::class, 'create'])->name('scan.create')->middleware('auth');
Route::post('scan', [ScanController::class, 'store'])->name('scan.store')->middleware('auth');
Route::get('scan/{receipt}', [ScanController::class, 'review'])->name('scan.review')->middleware('auth');
Route::post('scan/{receipt}/confirm', [ScanController::class, 'confirm'])->name('scan.confirm')->middleware('auth');
