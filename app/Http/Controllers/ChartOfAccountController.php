<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
{
    private function authorizeManager(): void
    {
        abort_if(!auth()->check() || !auth()->user()->isManager(), 403, 'Hanya Accounting Manager yang dapat mengelola Chart of Accounts.');
    }

    public function index(Request $request)
    {
        $query = ChartOfAccount::query()->orderBy('account_no_new');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_no_new', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->paginate(20)->withQueryString();
        $isManager = auth()->check() && auth()->user()->isManager();

        return view('chart_of_accounts.index', compact('accounts', 'isManager'));
    }

    public function create()
    {
        $this->authorizeManager();
        return view('chart_of_accounts.create');
    }

    public function store(Request $request)
    {
        $this->authorizeManager();

        $validated = $this->validateAccount($request);

        ChartOfAccount::create($validated);

        return redirect()
            ->route('chart-of-accounts.index')
            ->with('success', 'Chart of accounts berhasil ditambahkan.');
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $isManager = auth()->check() && auth()->user()->isManager();
        return view('chart_of_accounts.show', compact('chartOfAccount', 'isManager'));
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $this->authorizeManager();
        return view('chart_of_accounts.edit', compact('chartOfAccount'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $this->authorizeManager();

        $validated = $this->validateAccount($request, $chartOfAccount);

        $chartOfAccount->update([
            'account_no_old_1' => $validated['account_no_old_1'] ?? null,
            'account_no_old_2' => $validated['account_no_old_2'] ?? null,
            'account_name' => $validated['account_name'],
            'is_header' => $validated['is_header'] ?? null,
            'account_type' => $validated['account_type'] ?? null,
        ]);

        return redirect()
            ->route('chart-of-accounts.show', $chartOfAccount)
            ->with('success', 'Chart of accounts berhasil diperbarui.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $this->authorizeManager();

        $isUsed = InvoiceItem::where('chart_of_account_no_new', $chartOfAccount->account_no_new)->exists();
        if ($isUsed) {
            return back()->with('error', 'Akun ini tidak dapat dihapus karena masih digunakan di invoice.');
        }

        $chartOfAccount->delete();

        return redirect()
            ->route('chart-of-accounts.index')
            ->with('success', 'Chart of accounts berhasil dihapus.');
    }

    private function validateAccount(Request $request, ?ChartOfAccount $chartOfAccount = null): array
    {
        $rules = [
            'account_no_new' => [
                'required',
                'string',
                'max:6',
                Rule::unique('chart_of_accounts', 'account_no_new')
                    ->ignore($chartOfAccount?->account_no_new, 'account_no_new'),
            ],
            'account_no_old_1' => ['nullable', 'string', 'max:4'],
            'account_no_old_2' => ['nullable', 'integer'],
            'account_name' => ['required', 'string', 'max:37'],
            'is_header' => ['nullable', 'string', 'max:1'],
            'account_type' => ['nullable', 'string', 'max:19'],
        ];

        $validated = $request->validate($rules);

        if ($chartOfAccount) {
            $validated['account_no_new'] = $chartOfAccount->account_no_new;
        }

        return $validated;
    }
}
