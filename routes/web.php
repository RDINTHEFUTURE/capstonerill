<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\Admin\PasswordResetController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'submit'])->name('forgot-password.submit');

Route::get('/password-recovery/{token}', [PasswordRecoveryController::class, 'showForm'])->name('password-recovery.show');
Route::post('/password-recovery/{token}', [PasswordRecoveryController::class, 'updatePassword'])->name('password-recovery.update');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('chart-of-accounts', ChartOfAccountController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::post('invoices/{invoice}/mark-unpaid', [InvoiceController::class, 'markUnpaid'])->name('invoices.mark-unpaid');
    Route::get('invoices/{invoice}/qr', [InvoiceController::class, 'qr'])->name('invoices.qr');
    Route::get('invoices/{invoice}/preview', [InvoicePdfController::class, 'preview'])->name('invoices.preview');
    Route::get('invoices/{invoice}/pdf', [InvoicePdfController::class, 'show'])->name('invoices.pdf');
    Route::get('invoices/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
    Route::post('invoices/bulk-action', [InvoiceController::class, 'bulkAction'])->name('invoices.bulk-action');
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('activity/login-logs', [LoginLogController::class, 'index'])->name('activity.login-logs');
    Route::get('admin/password-resets', [PasswordResetController::class, 'index'])->name('admin.password-resets.index');
    Route::post('admin/password-resets/{passwordResetRequest}/approve', [PasswordResetController::class, 'approve'])->name('admin.password-resets.approve');
    Route::post('admin/password-resets/{passwordResetRequest}/reject', [PasswordResetController::class, 'reject'])->name('admin.password-resets.reject');
    Route::get('settings/company', [CompanyProfileController::class, 'edit'])->name('settings.company');
    Route::put('settings/company', [CompanyProfileController::class, 'update'])->name('settings.company.update');
    Route::get('ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('ledger/{accountNo}', [LedgerController::class, 'show'])->name('ledger.show');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/export-invoices', [ReportController::class, 'exportInvoices'])->name('reports.export-invoices');
    Route::get('reports/export-ledger', [ReportController::class, 'exportLedger'])->name('reports.export-ledger');
    Route::get('reports/export-trial-balance', [ReportController::class, 'exportTrialBalance'])->name('reports.export-trial-balance');
    Route::get('reports/import', [ReportController::class, 'importForm'])->name('reports.import-form');
    Route::post('reports/import-invoices', [ReportController::class, 'importInvoices'])->name('reports.import-invoices');
    Route::post('reports/import-coa', [ReportController::class, 'importChartOfAccounts'])->name('reports.import-coa');
    Route::get('reports/template-invoices', [ReportController::class, 'templateInvoices'])->name('reports.template-invoices');
    Route::get('reports/template-coa', [ReportController::class, 'templateChartOfAccounts'])->name('reports.template-coa');
});




