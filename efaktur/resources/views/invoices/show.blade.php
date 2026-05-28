<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-show.css') }}">
</head>
<body class="invoice-show-page">
    <h1>Detail Invoice</h1>

        <div class="button-row">
        <a class="btn btn-secondary" href="{{ route('invoices.index') }}">&larr; Kembali</a>
        <a class="btn" href="{{ route('invoices.preview', $invoice) }}">Preview Faktur</a>
        <a class="btn" href="{{ route('invoices.edit', $invoice) }}">Edit</a>

        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Hapus invoice ini?');" class="inline-form">
            @csrf
            @method('DELETE')
            <button class="btn btn-secondary" type="submit">Hapus</button>
        </form>
        <a class="btn" href="{{ route('invoices.create') }}">+ Invoice Baru</a>
    </div>



    <div class="grid">
        <div class="card">
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

        <div class="card qr-wrap">
            <div class="qr-header">QR Code</div>
            <img
                src="{{ route('invoices.qr', $invoice) }}"
                alt="QR Invoice"
                width="280"
                height="280"
            >
            <div class="qr-note">QR dibuat dari payload yang tersimpan di database.</div>
        </div>
    </div>
</body>
</html>

