<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dokumen Faktur</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 0; padding: 0; color:#111; }
        .page { padding: 28px; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom: 2px solid #111; padding-bottom: 14px; }
        .brand h1 { font-size: 18px; margin:0; }
        .brand p { margin:6px 0 0 0; font-size: 12px; line-height: 1.4; }
        .meta { text-align:right; font-size: 12px; }
        .meta .row { margin: 2px 0; }

        .content { margin-top: 18px; display:flex; gap: 16px; }
        .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; flex:1; }
        .box h2 { font-size: 13px; margin:0 0 10px 0; }

        .kv { width:100%; border-collapse: collapse; font-size: 12px; }
        .kv td { padding: 6px 0; vertical-align: top; }
        .kv td:first-child { width: 140px; color:#374151; }

        .qr-box { width: 260px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; }
        .qr-title { font-size: 12px; font-weight: 700; margin-bottom: 8px; }
        .qr-img { width: 180px; height: 180px; border: 1px solid #e5e7eb; }

        .total { margin-top: 14px; border-top: 1px solid #e5e7eb; padding-top: 12px; display:flex; justify-content:flex-end; }
        .total .amount { font-size: 18px; font-weight: 800; }
        .footer { margin-top: 22px; font-size: 11px; color:#6b7280; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div class="brand">
            <h1>EFaktur - Dokumen Faktur</h1>
            <p>
                QR otomatis dibuat dari payload yang tersimpan di database.<br>
                Silakan gunakan aplikasi pembaca QR untuk verifikasi.
            </p>
        </div>
        <div class="meta">
            <div class="row"><b>Nomor</b>: {{ $invoice->nomor }}</div>
            <div class="row"><b>Tanggal</b>: {{ $invoice->tanggal->format('Y-m-d') }}</div>
            <div class="row"><b>Currency</b>: {{ $invoice->currency }}</div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <h2>Data Pihak</h2>
            <table class="kv">
                <tr><td>NPWP</td><td>{{ $invoice->npwp }}</td></tr>
                <tr><td>Nama</td><td>{{ $invoice->nama }}</td></tr>
                <tr><td>Alamat</td><td>{{ $invoice->alamat }}</td></tr>
            </table>

            <div class="footer">
                <b>Payload QR (base64 JSON)</b><br>
                <span style="word-break:break-word;">{{ $invoice->qr_payload }}</span>
            </div>
        </div>

        <div class="box qr-box">
            <div class="qr-title">QR Code</div>
            <img class="qr-img" src="{{ route('invoices.qr', $invoice) }}" alt="QR">
        </div>
    </div>

    <div class="total">
        <div class="amount">
            {{ number_format((float)$invoice->total, 2, ',', '.') }} {{ $invoice->currency }}
        </div>
    </div>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem.
    </div>

</div>
</body>
</html>

