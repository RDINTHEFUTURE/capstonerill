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

        $unpaidInvoices = Invoice::where('status', 'unpaid')->latest()->take(5)->get();

        $monthlyData = Invoice::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as month')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(total) as revenue')
            ->selectRaw('SUM(CASE WHEN status = "paid" THEN total ELSE 0 END) as paid')
            ->selectRaw('SUM(CASE WHEN status = "unpaid" THEN total ELSE 0 END) as unpaid')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                return [
                    'month' => \Carbon\Carbon::parse($row->month . '-01')->format('M Y'),
                    'count' => $row->count,
                    'revenue' => (float) $row->revenue,
                    'paid' => (float) $row->paid,
                    'unpaid' => (float) $row->unpaid,
                ];
            });

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
            'unpaidInvoices',
            'monthlyData',
            'topAccounts'
        ));
    }
}
