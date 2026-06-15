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
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('invoices.show', $inv) }}">Detail</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('invoices.edit', $inv) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada data.</td></tr>
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
