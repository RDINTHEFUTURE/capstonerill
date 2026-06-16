@extends('layouts.mazer')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Laporan Penjualan</h3>
        <a class="btn btn-success" href="{{ route('reports.export-invoices', request()->query()) }}">Export CSV</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.sales') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                @if(request('from') || request('to') || request('status'))
                    <div class="col-md-1">
                        <a href="{{ route('reports.sales') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Invoice</h6>
                    <h3>{{ $summary['total_invoices'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Pendapatan</h6>
                    <h3>{{ number_format($summary['total_revenue'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Lunas</h6>
                    <h4 class="text-success">{{ $summary['paid_count'] }}</h4>
                    <small>{{ number_format($summary['paid_revenue'], 0, ',', '.') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Belum Lunas</h6>
                    <h4 class="text-warning">{{ $summary['unpaid_count'] }}</h4>
                    <small>{{ number_format($summary['unpaid_revenue'], 0, ',', '.') }}</small>
                </div>
            </div>
        </div>
    </div>

    @if($monthlyData->count())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Pendapatan per Bulan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-end">Jumlah Invoice</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Lunas</th>
                            <th class="text-end">Belum Lunas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $month)
                            <tr>
                                <td>{{ $month['month'] }}</td>
                                <td class="text-end">{{ $month['count'] }}</td>
                                <td class="text-end">{{ number_format($month['revenue'], 0, ',', '.') }}</td>
                                <td class="text-end text-success">{{ number_format($month['paid'], 0, ',', '.') }}</td>
                                <td class="text-end text-warning">{{ number_format($month['unpaid'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Invoice</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Penjual</th>
                            <th>Pembeli</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->nomor }}</a></td>
                                <td>{{ $inv->tanggal->format('d M Y') }}</td>
                                <td>{{ $inv->nama_penjual ?? '-' }}</td>
                                <td>{{ $inv->nama_pembeli ?? '-' }}</td>
                                <td class="text-end">{{ number_format((float)$inv->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($inv->isPaid())
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
