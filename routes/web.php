<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
Route::post('/sale', [SaleController::class, 'store'])->name('sales.store');

Route::get('/invoice/{sale}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{sale}/pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf');

Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
Route::post('/customers/{customer}/payment', [CustomerController::class, 'addPayment'])->name('customers.payment');
