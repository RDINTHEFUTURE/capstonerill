<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ChartOfAccount;
use App\Services\JournalService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalRevenue = Invoice::sum('total');
        $paidCount = Invoice::where('status', 'paid')->count();
        $unpaidCount = Invoice::where('status', 'unpaid')->count();
        $totalInvoices = Invoice::count();

        $recentInvoices = Invoice::latest()->take(5)->get();

        $journalService = new JournalService();
        $accountBalances = $journalService->getAccountBalances();

        $topAccounts = collect($accountBalances)
            ->filter(fn ($b) => $b['debit'] > 0 || $b['credit'] > 0)
            ->sortByDesc(fn ($b) => abs($b['balance']))
            ->take(5)
            ->map(function ($balance, $accountNo) {
                $account = ChartOfAccount::find($accountNo);
                return [
                    'account' => $account,
                    'balance' => $balance['balance'],
                ];
            })
            ->filter(fn ($item) => $item['account']);

        return view('home', compact(
            'totalRevenue',
            'paidCount',
            'unpaidCount',
            'totalInvoices',
            'recentInvoices',
            'topAccounts'
        ));
    }
}
