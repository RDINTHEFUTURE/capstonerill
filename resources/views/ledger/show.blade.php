@extends('layouts.mazer')

@section('title', 'Buku Besar - ' . $account->account_name)

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Buku Besar: {{ $account->account_no_new }} - {{ $account->account_name }}</h3>
        <a class="btn btn-secondary" href="{{ route('ledger.index') }}">Kembali</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ledger.show', $account->account_no_new) }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                @if(request('from') || request('to'))
                    <div class="col-md-2">
                        <a href="{{ route('ledger.show', $account->account_no_new) }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Debit</h6>
                    <h4>{{ number_format($totalDebit, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Kredit</h6>
                    <h4>{{ number_format($totalCredit, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Saldo</h6>
                    <h4 class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format(abs($balance), 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Ref</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($entries as $entry)
                            <tr>
                                <td>{{ $entry->date->format('d M Y') }}</td>
                                <td>{{ $entry->description ?: '-' }}</td>
                                <td>
                                    @if($entry->reference_type && $entry->reference_id)
                                        @if($entry->reference_type === 'App\Models\Invoice')
                                            <a href="{{ route('invoices.show', $entry->reference_id) }}">Invoice #{{ $entry->reference_id }}</a>
                                        @else
                                            {{ class_basename($entry->reference_type) }} #{{ $entry->reference_id }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">{{ $entry->debit > 0 ? number_format($entry->debit, 2, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $entry->credit > 0 ? number_format($entry->credit, 2, ',', '.') : '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $entries->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
