<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalService
{
    const ACCOUNT_PIUTANG = '1-1131';
    const ACCOUNT_PENJUALAN = '4-1121';

    public function postInvoice(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $this->reverseInvoice($invoice);

            foreach ($invoice->items as $item) {
                if (!$item->chart_of_account_no_new) {
                    continue;
                }

                JournalEntry::create([
                    'date' => $invoice->tanggal,
                    'account_no' => $item->chart_of_account_no_new,
                    'debit' => $item->subtotal,
                    'credit' => 0,
                    'reference_type' => Invoice::class,
                    'reference_id' => $invoice->id,
                    'description' => "Penjualan: {$item->nama_produk} (Invoice {$invoice->nomor})",
                ]);
            }

            $total = (float) $invoice->total;

            JournalEntry::create([
                'date' => $invoice->tanggal,
                'account_no' => self::ACCOUNT_PIUTANG,
                'debit' => $total,
                'credit' => 0,
                'reference_type' => Invoice::class,
                'reference_id' => $invoice->id,
                'description' => "Piutang Dagang (Invoice {$invoice->nomor})",
            ]);

            JournalEntry::create([
                'date' => $invoice->tanggal,
                'account_no' => self::ACCOUNT_PENJUALAN,
                'debit' => 0,
                'credit' => $total,
                'reference_type' => Invoice::class,
                'reference_id' => $invoice->id,
                'description' => "Penjualan Kredit Lokal (Invoice {$invoice->nomor})",
            ]);
        });
    }

    public function reverseInvoice(Invoice $invoice): void
    {
        JournalEntry::where('reference_type', Invoice::class)
            ->where('reference_id', $invoice->id)
            ->delete();
    }

    public function getBalance(string $accountNo): float
    {
        $result = JournalEntry::where('account_no', $accountNo)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->first();

        return (float) ($result->balance ?? 0);
    }

    public function getAccountBalances(): array
    {
        $entries = JournalEntry::select('account_no')
            ->selectRaw('COALESCE(SUM(debit), 0) as total_debit')
            ->selectRaw('COALESCE(SUM(credit), 0) as total_credit')
            ->groupBy('account_no')
            ->get();

        $balances = [];
        foreach ($entries as $entry) {
            $balances[$entry->account_no] = [
                'debit' => (float) $entry->total_debit,
                'credit' => (float) $entry->total_credit,
                'balance' => (float) $entry->total_debit - (float) $entry->total_credit,
            ];
        }

        return $balances;
    }
}
