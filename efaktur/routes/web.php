<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;

Route::get('/', [HomeController::class, 'index']);

Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/qr', [InvoiceController::class, 'qr'])->name('invoices.qr');


