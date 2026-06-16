<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Services\JournalService;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index()
    {
        $journalService = new JournalService();
        $balances = $journalService->getAccountBalances();

        $accounts = ChartOfAccount::query()
            ->where(function ($query) {
                $query->whereNull('is_header')
                    ->orWhere('is_header', '!=', 'H');
            })
            ->orderBy('account_no_new')
            ->get();

        $accountData = $accounts->map(function ($account) use ($balances) {
            $balance = $balances[$account->account_no_new] ?? ['debit' => 0, 'credit' => 0, 'balance' => 0];
            return [
                'account' => $account,
                'debit' => $balance['debit'],
                'credit' => $balance['credit'],
                'balance' => $balance['balance'],
            ];
        })->filter(fn ($item) => $item['debit'] > 0 || $item['credit'] > 0);

        $totalDebit = $accountData->sum('debit');
        $totalCredit = $accountData->sum('credit');

        return view('ledger.index', compact('accountData', 'totalDebit', 'totalCredit'));
    }

    public function show(string $accountNo, Request $request)
    {
        $account = ChartOfAccount::findOrFail($accountNo);

        $query = JournalEntry::forAccount($accountNo)->orderBy('date', 'desc');

        if ($request->filled('from')) {
            $query->where('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', $request->to);
        }

        $entries = $query->paginate(20)->withQueryString();

        $journalService = new JournalService();
        $balance = $journalService->getBalance($accountNo);

        $totalDebit = $entries->sum('debit');
        $totalCredit = $entries->sum('credit');

        return view('ledger.show', compact('account', 'entries', 'balance', 'totalDebit', 'totalCredit'));
    }
}
