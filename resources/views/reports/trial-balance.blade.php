@extends('layouts.mazer')

@section('title', 'Neraca Saldo')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Neraca Saldo (Trial Balance)</h3>
        <div>
            <a class="btn btn-success" href="{{ route('reports.export-trial-balance') }}"><i class="bi bi-download me-1"></i>Export CSV</a>
            <a class="btn btn-secondary" href="{{ route('ledger.index') }}">&larr; Kembali</a>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $acc)
                            <tr>
                                <td>{{ $acc['account_no'] }}</td>
                                <td>{{ $acc['account_name'] }}</td>
                                <td>{{ $acc['account_type'] }}</td>
                                <td class="text-end">{{ $acc['debit'] > 0 ? number_format($acc['debit'], 2, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $acc['credit'] > 0 ? number_format($acc['credit'], 2, ',', '.') : '-' }}</td>
                                <td class="text-end {{ $acc['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($acc['balance'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-active fw-bold">
                            <td colspan="3">TOTAL</td>
                            <td class="text-end">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($totalCredit, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($totalDebit - $totalCredit, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
