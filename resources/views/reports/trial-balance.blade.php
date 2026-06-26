@extends('layouts.mazer')

@section('title', 'Neraca Saldo')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <circle cx="16" cy="16" r="15" stroke="#435ee0" stroke-width="2" fill="#f0f2ff"/>
                <line x1="16" y1="6" x2="16" y2="22" stroke="#435ee0" stroke-width="2" stroke-linecap="round"/>
                <line x1="8" y1="12" x2="24" y2="12" stroke="#435ee0" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 12 L6 18 Q6 20 8 20 L10 20 Q12 20 12 18 Z" fill="#435ee0" opacity="0.8"/>
                <path d="M24 12 L22 18 Q22 20 24 20 L26 20 Q28 20 28 18 Z" fill="#435ee0" opacity="0.8"/>
                <line x1="14" y1="22" x2="18" y2="22" stroke="#435ee0" stroke-width="2" stroke-linecap="round"/>
                <line x1="16" y1="22" x2="16" y2="26" stroke="#435ee0" stroke-width="2" stroke-linecap="round"/>
                <line x1="12" y1="26" x2="20" y2="26" stroke="#435ee0" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <h3>Neraca Saldo (Trial Balance)</h3>
        </div>
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
