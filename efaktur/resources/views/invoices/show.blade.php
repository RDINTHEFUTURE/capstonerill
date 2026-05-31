@extends('layouts.mazer')

@section('title', 'Detail Invoice')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/invoice-show.css') }}">
@endpush

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Detail Invoice</h3>
        <div>
            <a class="btn btn-secondary" href="{{ route('invoices.index') }}">&larr; Kembali</a>
            <a class="btn btn-primary" href="{{ route('invoices.preview', $invoice) }}">Preview Faktur</a>
            <a class="btn btn-outline-secondary" href="{{ route('invoices.edit', $invoice) }}">Edit</a>
            <a class="btn btn-success" href="{{ route('invoices.create') }}">+ Invoice Baru</a>
            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Hapus invoice ini?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Hapus</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="details-row"><span class="key">Nomor</span><div class="value">{{ $invoice->nomor }}</div></div>
                    <div class="details-row"><span class="key">Tanggal</span><div class="value">{{ $invoice->tanggal->format('Y-m-d') }}</div></div>
                    <div class="details-row"><span class="key">NPWP Penjual</span><div class="value">{{ $invoice->npwp_penjual }}</div></div>
                    <div class="details-row"><span class="key">Nama Penjual</span><div class="value">{{ $invoice->nama_penjual }}</div></div>
                    <div class="details-row"><span class="key">Alamat Penjual</span><div class="value">{{ $invoice->alamat_penjual }}</div></div>

                    <div class="details-row"><span class="key">NPWP Pembeli</span><div class="value">{{ $invoice->npwp_pembeli }}</div></div>
                    <div class="details-row"><span class="key">Nama Pembeli</span><div class="value">{{ $invoice->nama_pembeli }}</div></div>
                    <div class="details-row"><span class="key">Alamat Pembeli</span><div class="value">{{ $invoice->alamat_pembeli }}</div></div>

                    <div class="details-row"><span class="key">Total</span><div class="value">{{ number_format((float)$invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</div></div>

                    <div class="details-section">
                        <div class="key">Payload QR (base64 JSON)</div>
                        <div class="details-value">
                            <pre>{{ $invoice->qr_payload }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card qr-wrap">
                <div class="card-body text-center">
                    <div class="qr-header">QR Code</div>
                    <img src="{{ route('invoices.qr', $invoice) }}" alt="QR Invoice" width="280" height="280">
                    <div class="qr-note mt-2">QR dibuat dari payload yang tersimpan di database.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

