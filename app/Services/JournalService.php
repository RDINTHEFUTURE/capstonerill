<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalService
{
    // Indonesian Chart of Accounts: 1-xxxx = Asset (Piutang/Receivable), 4-xxxx = Revenue
    const ACCOUNT_PIUTANG = '1-1131';
    const ACCOUNT_PENJUALAN = '4-1121';

    /**
     * Posts double-entry journal entries for a credit sale invoice.
     *
     * Business rule: Every credit sale creates exactly two balanced entries:
     *   - Debit Piutang (Accounts Receivable) — the buyer owes this amount
     *   - Credit Penjualan (Revenue) — income earned from the sale
     *
     * This method is idempotent: it reverses any existing entries for this
     * invoice before posting, so it's safe to call on both create and update.
     */
    public function postInvoice(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            // Reverse first to prevent duplicate entries on update
            $this->reverseInvoice($invoice);

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

    /**
     * Removes all journal entries for an invoice.
     * Called before reposting to ensure idempotency — prevents double-counting
     * when an invoice is edited or its total changes.
     */
    public function reverseInvoice(Invoice $invoice): void
    {
        JournalEntry::where('reference_type', Invoice::class)
            ->where('reference_id', $invoice->id)
            ->delete();
    }

    /**
     * Returns the balance for a single account: debit - credit.
     * COALESCE handles accounts that have no journal entries yet.
     */
    public function getBalance(string $accountNo): float
    {
        $result = JournalEntry::where('account_no', $accountNo)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->first();

        return (float) ($result->balance ?? 0);
    }

    /**
     * Returns aggregated debit/credit/balance for every account that has entries.
     * Used by the General Ledger and Trial Balance views.
     */
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
