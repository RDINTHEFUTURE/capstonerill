@extends('layouts.mazer')

@section('title', 'Daftar Invoice')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/invoice-index.css') }}">
@endpush

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Daftar Invoice</h3>
        <a class="btn btn-primary" href="{{ route('invoices.create') }}">+ Buat Invoice</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari Nomor/Nama</label>
                    <input type="text" name="search" class="form-control" placeholder="Nomor atau nama..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
                @if(request('search') || request('status') || request('from') || request('to'))
                    <div class="col-md-1">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($invoices as $inv)
                        <tr>
                            <td>{{ $inv->nomor }}</td>
                            <td>{{ $inv->tanggal->format('Y-m-d') }}</td>
                            <td>{{ $inv->nama_penjual ?? '-' }}</td>
                            <td>{{ number_format((float)$inv->total, 2, ',', '.') }} {{ $inv->currency }}</td>
                            <td>
                                @if($inv->isPaid())
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('invoices.show', $inv) }}">Detail</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('invoices.edit', $inv) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
