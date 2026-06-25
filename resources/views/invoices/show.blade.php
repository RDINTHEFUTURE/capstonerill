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
            @if($invoice->isPaid())
                <form method="POST" action="{{ route('invoices.mark-unpaid', $invoice) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-warning" type="submit">Tandai Belum Lunas</button>
                </form>
            @else
                <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success" type="submit">Tandai Lunas</button>
                </form>
            @endif
            <a class="btn btn-primary" href="{{ route('invoices.preview', $invoice) }}">Preview Faktur</a>
            <a class="btn btn-outline-secondary" href="{{ route('invoices.edit', $invoice) }}">Edit</a>
            <a class="btn btn-outline-info" href="{{ route('invoices.duplicate', $invoice) }}">Duplikat</a>
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
                    <div class="details-row"><span class="key">Nomor Seri Faktur Pajak</span><div class="value">{{ $invoice->nomor }}</div></div>
                    <div class="details-row"><span class="key">Tanggal</span><div class="value">{{ $invoice->tanggal->format('Y-m-d') }}</div></div>
                    <div class="details-row"><span class="key">Status</span><div class="value">
                        @if($invoice->isPaid())
                            <span class="badge bg-success">Lunas</span>
                            @if($invoice->paid_at)
                                <small class="text-muted ms-1">({{ $invoice->paid_at->format('d M Y H:i') }})</small>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                        @endif
                    </div></div>
                    @if($invoice->creator)
                    <div class="details-row"><span class="key">Dibuat Oleh</span><div class="value">{{ $invoice->creator->name }}</div></div>
                    @endif
                    <div class="details-row"><span class="key">NPWP Penjual</span><div class="value">{{ $invoice->npwp_penjual }}</div></div>
                    <div class="details-row"><span class="key">Nama Penjual</span><div class="value">{{ $invoice->nama_penjual }}</div></div>
                    <div class="details-row"><span class="key">Alamat Penjual</span><div class="value">{{ $invoice->alamat_penjual }}</div></div>

                    <div class="details-row"><span class="key">NPWP Pembeli</span><div class="value">{{ $invoice->npwp_pembeli }}</div></div>
                    <div class="details-row"><span class="key">Nama Pembeli</span><div class="value">{{ $invoice->nama_pembeli }}</div></div>
                    <div class="details-row"><span class="key">Alamat Pembeli</span><div class="value">{{ $invoice->alamat_pembeli }}</div></div>

                    <div class="details-row"><span class="key">Total</span><div class="value">{{ number_format((float)$invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</div></div>

                    @if($invoice->notes)
                    <div class="details-row"><span class="key">Catatan</span><div class="value">{{ $invoice->notes }}</div></div>
                    @endif

                    <div class="details-section">
                        <div class="key">Data faktur tersimpan (base64 JSON)</div>
                        <div class="details-value">
                            <pre>{{ $invoice->qr_payload }}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="mb-3">Items</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Akun</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->chartOfAccount ? $item->chartOfAccount->account_no_new . ' - ' . $item->chartOfAccount->account_name : '-' }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ number_format((float) $item->harga, 2, ',', '.') }}</td>
                                        <td>{{ number_format((float) $item->diskon, 2, ',', '.') }}</td>
                                        <td>{{ number_format((float) $item->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card qr-wrap">
                <div class="card-body text-center">
                    @php($signatureType = $invoice->signature_type ?? 'qr')
                    <div class="qr-header">{{ $signatureType === 'hand' ? 'Hand Signature' : 'QR Stamp' }}</div>

                    <!-- Hand signature kept in DB, but not displayed in UI anymore -->
                    @if($signatureType === 'hand')
                        <div class="qr-placeholder" style="width:280px; height:140px; margin: 0 auto;" aria-hidden="true"></div>
                        <div class="qr-note mt-2" style="visibility:hidden;">Tanda tangan digambar oleh user.</div>
                    @else
                        @if(!empty($invoice->qr_image))
                            <img src="{{ $invoice->qr_image }}" alt="QR Invoice" width="280" height="280">
                            <div class="qr-note mt-2">QR dari DJP (diunggah oleh user).</div>
                        @else
                            <div class="qr-placeholder" style="width:280px; height:280px; margin: 0 auto;"></div>
                            <div class="qr-note mt-2">QR belum diunggah — unggah saat membuat / mengedit invoice.</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
