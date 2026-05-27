<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Invoice</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; max-width: 1000px; }
        .grid { display:grid; grid-template-columns: 1fr 320px; gap: 16px; margin-top: 12px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
        .key { font-weight: 700; color:#111827; }
        .value { margin-top: 6px; }
        .btn { display: inline-block; padding: 10px 14px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; }
        .btn-secondary { background: #6b7280; }
        .qr-wrap { display:flex; flex-direction: column; align-items:center; gap: 10px; }
        pre { white-space: pre-wrap; word-break: break-word; background:#f9fafb; padding:12px; border-radius:10px; border:1px solid #e5e7eb; }
    </style>
</head>
<body>
    <h1>Detail Invoice</h1>

        <div style="display:flex; gap:10px; margin-top: 8px;">
        <a class="btn btn-secondary" href="{{ route('invoices.index') }}">&larr; Kembali</a>
        <a class="btn" href="{{ route('invoices.pdf', $invoice) }}">Cetak PDF</a>
        <a class="btn" href="{{ route('invoices.edit', $invoice) }}">Edit</a>

        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Hapus invoice ini?');" style="margin:0;">
            @csrf
            @method('DELETE')
            <button class="btn btn-secondary" type="submit" style="cursor:pointer; border:none;">Hapus</button>
        </form>
        <a class="btn" href="{{ route('invoices.create') }}">+ Invoice Baru</a>
    </div>



    <div class="grid">
        <div class="card">
            <div><span class="key">Nomor</span><div class="value">{{ $invoice->nomor }}</div></div>
            <div style="margin-top: 12px;"><span class="key">Tanggal</span><div class="value">{{ $invoice->tanggal->format('Y-m-d') }}</div></div>
            <div style="margin-top: 12px;"><span class="key">NPWP Penjual</span><div class="value">{{ $invoice->npwp_penjual }}</div></div>
            <div style="margin-top: 12px;"><span class="key">Nama Penjual</span><div class="value">{{ $invoice->nama_penjual }}</div></div>
            <div style="margin-top: 12px;"><span class="key">Alamat Penjual</span><div class="value">{{ $invoice->alamat_penjual }}</div></div>

            <div style="margin-top: 12px;"><span class="key">NPWP Pembeli</span><div class="value">{{ $invoice->npwp_pembeli }}</div></div>
            <div style="margin-top: 12px;"><span class="key">Nama Pembeli</span><div class="value">{{ $invoice->nama_pembeli }}</div></div>
            <div style="margin-top: 12px;"><span class="key">Alamat Pembeli</span><div class="value">{{ $invoice->alamat_pembeli }}</div></div>

            <div style="margin-top: 12px;"><span class="key">Total</span><div class="value">{{ number_format((float)$invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</div></div>

            <div style="margin-top: 16px;">
                <div class="key">Payload QR (base64 JSON)</div>
                <div class="value" style="margin-top:8px;">
                    <pre>{{ $invoice->qr_payload }}</pre>
                </div>
            </div>
        </div>

        <div class="card qr-wrap">
            <div style="font-weight:700;">QR Code</div>
            <img
                src="{{ route('invoices.qr', $invoice) }}"
                alt="QR Invoice"
                width="280"
                height="280"
            >
            <div style="font-size: 12px; color:#6b7280; text-align:center;">QR dibuat dari payload yang tersimpan di database.</div>
        </div>
    </div>
</body>
</html>

