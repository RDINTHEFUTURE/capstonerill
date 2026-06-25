<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Services\JournalService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Export/import restricted to Admin, Manager, and Supervisor.
     * Staff should not bulk-download financial data or modify
     * the chart of accounts via CSV import.
     */
    private function authorizeReport(): void
    {
        abort_if(!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isManager() && !auth()->user()->isSupervisor()), 403, 'Akses ditolak.');
    }

    public function sales(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('from')) {
            $query->where('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('tanggal', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->get();

        $summary = [
            'total_invoices' => $invoices->count(),
            'total_revenue' => $invoices->sum('total'),
            'paid_count' => $invoices->where('status', 'paid')->count(),
            'unpaid_count' => $invoices->where('status', 'unpaid')->count(),
            'paid_revenue' => $invoices->where('status', 'paid')->sum('total'),
            'unpaid_revenue' => $invoices->where('status', 'unpaid')->sum('total'),
        ];

        $monthlyData = $invoices->groupBy(function ($inv) {
            return $inv->tanggal->format('Y-m');
        })->map(function ($group) {
            return [
                'month' => $group->first()->tanggal->format('M Y'),
                'count' => $group->count(),
                'revenue' => $group->sum('total'),
                'paid' => $group->where('status', 'paid')->sum('total'),
                'unpaid' => $group->where('status', 'unpaid')->sum('total'),
            ];
        })->values();

        return view('reports.sales', compact('invoices', 'summary', 'monthlyData'));
    }

    /**
     * Streams CSV directly to the browser without buffering the entire file
     * in memory — important for large datasets.
     */
    public function exportInvoices(Request $request)
    {
        $this->authorizeReport();

        $query = Invoice::query()->latest();

        if ($request->filled('from')) {
            $query->where('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('tanggal', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->get();

        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nomor', 'Tanggal', 'Penjual', 'Pembeli', 'Total', 'Currency', 'Status', 'Tanggal Bayar']);

            foreach ($invoices as $inv) {
                fputcsv($file, [
                    $inv->nomor,
                    $inv->tanggal->format('Y-m-d'),
                    $inv->nama_penjual ?? '-',
                    $inv->nama_pembeli ?? '-',
                    $inv->total,
                    $inv->currency,
                    $inv->status,
                    $inv->paid_at ? $inv->paid_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportLedger()
    {
        $this->authorizeReport();

        $journalService = new JournalService();
        $balances = $journalService->getAccountBalances();

        $filename = 'ledger_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($balances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Akun', 'Nama Akun', 'Total Debit', 'Total Kredit', 'Saldo']);

            foreach ($balances as $accountNo => $balance) {
                $account = \App\Models\ChartOfAccount::find($accountNo);
                fputcsv($file, [
                    $accountNo,
                    $account ? $account->account_name : '-',
                    $balance['debit'],
                    $balance['credit'],
                    $balance['balance'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportTrialBalance()
    {
        $this->authorizeReport();

        $journalService = new JournalService();
        $balances = $journalService->getAccountBalances();

        $filename = 'trial_balance_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($balances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Akun', 'Nama Akun', 'Tipe', 'Debit', 'Kredit', 'Saldo']);

            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($balances as $accountNo => $balance) {
                $account = \App\Models\ChartOfAccount::find($accountNo);
                if (!$account) continue;

                fputcsv($file, [
                    $accountNo,
                    $account->account_name,
                    $account->account_type ?? '-',
                    $balance['debit'],
                    $balance['credit'],
                    $balance['balance'],
                ]);

                $totalDebit += $balance['debit'];
                $totalCredit += $balance['credit'];
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', 'TOTAL', $totalDebit, $totalCredit, $totalDebit - $totalCredit]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importForm()
    {
        return view('reports.import');
    }

    /**
     * Imports invoices from CSV. Duplicate invoice numbers are skipped
     * (not overwritten) to prevent data loss. Imported invoices default
     * to unpaid status with no seller/buyer details or QR data.
     */
    public function importInvoices(Request $request)
    {
        $this->authorizeReport();

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            try {
                $nomor = $data['Nomor'] ?? $data['nomor'] ?? null;
                $tanggal = $data['Tanggal'] ?? $data['tanggal'] ?? null;
                $namaPenjual = $data['Penjual'] ?? $data['nama_penjual'] ?? null;
                $namaPembeli = $data['Pembeli'] ?? $data['nama_pembeli'] ?? null;
                $total = $data['Total'] ?? $data['total'] ?? 0;
                $currency = $data['Currency'] ?? $data['currency'] ?? 'IDR';

                if (!$nomor || !$tanggal) {
                    $errors[] = "Baris " . ($imported + $errors->count() + 2) . ": Nomor dan Tanggal wajib diisi.";
                    continue;
                }

                if (Invoice::where('nomor', $nomor)->exists()) {
                    $errors[] = "Nomor {$nomor} sudah ada, dilewati.";
                    continue;
                }

                Invoice::create([
                    'nomor' => $nomor,
                    'tanggal' => $tanggal,
                    'nama_penjual' => $namaPenjual,
                    'nama_pembeli' => $namaPembeli,
                    'total' => $total,
                    'currency' => $currency,
                    'status' => 'unpaid',
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Error baris " . ($imported + $errors->count() + 2) . ": " . $e->getMessage();
            }
        }

        fclose($handle);

        return redirect()->route('reports.import-form')
            ->with('success', "Berhasil import {$imported} invoice.")
            ->with('errors', $errors);
    }

    /**
     * Imports chart of accounts from CSV. Uses updateOrCreate — existing
     * accounts are updated with new data, new accounts are created.
     * This is safe because account_no_new is the natural key.
     */
    public function importChartOfAccounts(Request $request)
    {
        $this->authorizeReport();

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            try {
                $accountNo = $data['Kode Akun'] ?? $data['account_no_new'] ?? null;
                $accountName = $data['Nama Akun'] ?? $data['account_name'] ?? null;

                if (!$accountNo || !$accountName) {
                    $errors[] = "Baris " . ($imported + $errors->count() + 2) . ": Kode dan Nama Akun wajib diisi.";
                    continue;
                }

                \App\Models\ChartOfAccount::updateOrCreate(
                    ['account_no_new' => $accountNo],
                    [
                        'account_name' => $accountName,
                        'account_no_old_1' => $data['Kode Lama 1'] ?? $data['account_no_old_1'] ?? null,
                        'account_no_old_2' => $data['Kode Lama 2'] ?? $data['account_no_old_2'] ?? null,
                        'is_header' => $data['Header'] ?? $data['is_header'] ?? null,
                        'account_type' => $data['Tipe'] ?? $data['account_type'] ?? null,
                    ]
                );

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Error baris " . ($imported + $errors->count() + 2) . ": " . $e->getMessage();
            }
        }

        fclose($handle);

        return redirect()->route('reports.import-form')
            ->with('success', "Berhasil import {$imported} akun.")
            ->with('errors', $errors);
    }

    public function templateInvoices()
    {
        $filename = 'template_invoices.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nomor', 'Tanggal', 'Penjual', 'Pembeli', 'Total', 'Currency']);
            fputcsv($file, ['INV-2026-0001', '2026-01-15', 'PT ABC', 'PT XYZ', '1000000', 'IDR']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function templateChartOfAccounts()
    {
        $filename = 'template_chart_of_accounts.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Akun', 'Nama Akun', 'Kode Lama 1', 'Kode Lama 2', 'Header', 'Tipe']);
            fputcsv($file, ['1-1111', 'Kas Rp', '1111', '11111', '', 'Bank']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function trialBalance()
    {
        $journalService = new JournalService();
        $balances = $journalService->getAccountBalances();

        $accounts = collect();
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($balances as $accountNo => $balance) {
            $account = \App\Models\ChartOfAccount::find($accountNo);
            if (!$account) continue;

            $accounts->push([
                'account_no' => $accountNo,
                'account_name' => $account->account_name,
                'account_type' => $account->account_type ?? '-',
                'debit' => $balance['debit'],
                'credit' => $balance['credit'],
                'balance' => $balance['balance'],
            ]);

            $totalDebit += $balance['debit'];
            $totalCredit += $balance['credit'];
        }

        return view('reports.trial-balance', compact('accounts', 'totalDebit', 'totalCredit'));
    }
}
