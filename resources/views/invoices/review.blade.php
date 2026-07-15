@extends('layouts.mazer')

@section('title', 'Review Invoice')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Review Invoice {{ $invoice->nomor }}</h3>
        <div>
            <a class="btn btn-secondary" href="{{ route('invoices.index') }}">&larr; Kembali</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Data Invoice</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nomor Faktur:</strong><br>
                            {{ $invoice->nomor }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal:</strong><br>
                            {{ $invoice->tanggal->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Penjual:</strong><br>
                            {{ $invoice->nama_penjual }}<br>
                            <small class="text-muted">NPWP: {{ $invoice->npwp_penjual }}</small><br>
                            <small class="text-muted">{{ $invoice->alamat_penjual }}</small>
                        </div>
                        <div class="col-md-6">
                            <strong>Pembeli:</strong><br>
                            {{ $invoice->nama_pembeli }}<br>
                            <small class="text-muted">NPWP: {{ $invoice->npwp_pembeli }}</small><br>
                            <small class="text-muted">{{ $invoice->alamat_pembeli }}</small>
                        </div>
                    </div>

                    @if($invoice->notes)
                    <div class="mb-3">
                        <strong>Catatan:</strong><br>
                        {{ $invoice->notes }}
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Akun</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Diskon</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->chartOfAccount ? $item->chartOfAccount->account_no_new . ' - ' . $item->chartOfAccount->account_name : '-' }}</td>
                                    <td class="text-end">{{ $item->qty }}</td>
                                    <td class="text-end">{{ number_format((float)$item->harga, 2, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format((float)$item->diskon, 2, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format((float)$item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format((float)$invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->creator)
                    <div class="mt-2">
                        <small class="text-muted">Dibuat oleh: {{ $invoice->creator->name }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Aksi Review</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invoices.approve', $invoice) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui invoice ini?')">
                            <i class="bi bi-check-circle"></i> Setujui Invoice
                        </button>
                    </form>

                    <hr>

                    <form method="POST" action="{{ route('invoices.reject', $invoice) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="revision_notes" class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                            <textarea name="revision_notes" id="revision_notes" class="form-control" rows="4" required placeholder="Jelaskan kesalahan atau yang perlu diperbaiki..."></textarea>
                            @error('revision_notes')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Kembalikan invoice untuk revisi?')">
                            <i class="bi bi-x-circle"></i> Tolak & Minta Revisi
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    @if($invoice->isApproved())
                        <a class="btn btn-outline-primary w-100 mb-2" href="{{ route('invoices.preview', $invoice) }}">
                            <i class="bi bi-eye"></i> Preview Faktur
                        </a>
                        <a class="btn btn-outline-secondary w-100" href="{{ route('invoices.pdf', $invoice) }}">
                            <i class="bi bi-download"></i> Download PDF
                        </a>
                    @else
                        <p class="text-muted text-center mb-0">
                            <i class="bi bi-info-circle"></i> PDF hanya tersedia setelah invoice disetujui
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
