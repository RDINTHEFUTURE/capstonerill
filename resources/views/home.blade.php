@extends('layouts.mazer')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Invoice</h6>
                    <h3>{{ $totalInvoices }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Pendapatan</h6>
                    <h3>{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Lunas</h6>
                    <h3 class="text-success">{{ $paidCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Belum Lunas</h6>
                    <h3 class="text-warning">{{ $unpaidCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Invoice Terbaru</h5>
                    <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $inv)
                                    <tr>
                                        <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->nomor }}</a></td>
                                        <td>{{ $inv->tanggal->format('d M Y') }}</td>
                                        <td>{{ $inv->nama_pembeli ?? '-' }}</td>
                                        <td>{{ number_format((float)$inv->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if($inv->isPaid())
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Belum ada invoice.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Saldo Akun Teratas</h5>
                </div>
                <div class="card-body">
                    @forelse($topAccounts as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">{{ $item['account']->account_no_new }}</small>
                                <div>{{ $item['account']->account_name }}</div>
                            </div>
                            <span class="{{ $item['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format(abs($item['balance']), 0, ',', '.') }}
                            </span>
                        </div>
                        <hr class="my-1">
                    @empty
                        <p class="text-center text-muted">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
