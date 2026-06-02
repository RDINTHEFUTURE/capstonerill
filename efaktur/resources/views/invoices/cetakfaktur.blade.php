<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Faktur Penjualan</title>
    @php
        $pdfCssPath = public_path('css/invoice-cetakfaktur.css');
        $pdfCss = file_exists($pdfCssPath) ? file_get_contents($pdfCssPath) : '';
    @endphp
    <style>
        {!! $pdfCss !!}
    </style>
</head>
<body class="invoice-cetak-page">
    <div class="invoice-faktur-container">
        <div class="invoice-title">FAKTUR PENJUALAN</div>

        <table class="invoice-summary-table">
            <tr>
                <td class="invoice-label">Nomor Invoice:</td>
                <td class="invoice-value">{{ $invoice->nomor ?? '-' }}</td>
            </tr>
            <tr>
                <td class="invoice-label">Tanggal:</td>
                <td class="invoice-value">{{ $invoice->tanggal ? $invoice->tanggal->format('d M Y') : '-' }}</td>
            </tr>
        </table>

        <table class="invoice-table">
            <tr>
                <th colspan="2">PENJUAL</th>
            </tr>
            <tr>
                <td class="invoice-label">Nama:</td>
                <td class="invoice-value">{{ $invoice->nama_penjual ?? '-' }}</td>
            </tr>
            <tr>
                <td class="invoice-label">Alamat:</td>
                <td class="invoice-value">{{ $invoice->alamat_penjual ?? '-' }}</td>
            </tr>
            <tr>
                <td class="invoice-label">Identitas (NPWP):</td>
                <td class="invoice-value">{{ $invoice->npwp_penjual ?? '-' }}</td>
            </tr>

        </table>

        <table class="invoice-table">
            <tr>
                <th colspan="2">PEMBELI</th>
            </tr>
            <tr>
                <td class="invoice-label">Nama:</td>
                <td class="invoice-value">{{ $invoice->nama_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="invoice-label">Alamat:</td>
                <td class="invoice-value">{{ $invoice->alamat_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="invoice-label">Identitas (NPWP / NIK):</td>
                <td class="invoice-value">{{ $invoice->npwp_pembeli ?? '-' }}</td>
            </tr>

        </table>

        <table class="invoice-table invoice-detail-table">
            <thead>
                <tr>
                    <th class="invoice-col-no">No.</th>
                    <th class="invoice-col-product">Produk</th>
                    <th>Akun</th>
                    <th class="invoice-col-qty">Qty</th>
                    <th class="invoice-col-price">Harga (Rp)</th>
                    <th class="invoice-col-subtotal">Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php($items = $invoice->items ?? collect())
                @foreach($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $item->nama_produk ?? '-' }}</td>
                        <td>{{ $item->chartOfAccount?->account_no_new }}{{ $item->chartOfAccount ? ' - ' . $item->chartOfAccount->account_name : '' }}</td>
                        <td class="text-right">{{ $item->qty ?? 1 }}</td>
                        <td class="text-right">{{ number_format((float)($item->harga ?? 0), 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format((float)($item->subtotal ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="invoice-table">
            <tr>
                <td class="invoice-label">Total</td>
                <td class="invoice-value invoice-text-right">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="invoice-footer-section">
            <div class="invoice-qr-box invoice-qr-box-pdf">
                <div class="invoice-qr-inner">
                    <div class="invoice-qr-inner-label">[ QR CODE INVOICE ]</div>
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Invoice">
                </div>
            </div>

            <div class="invoice-signature-box">
                <div class="invoice-signature-date">{{ $invoice->tanggal ? $invoice->tanggal->format('d M Y') : '-' }}</div>
                <div>{{ $invoice->role_penandatangan ?? 'Role Penandatangan' }},</div>
                @if($invoice->signature_data)
                    <div class="invoice-signature-container">
                        <img class="invoice-signature-image" src="{{ $invoice->signature_data }}" alt="Tanda Tangan">
                    </div>
                @endif
                <div class="invoice-signature-name">{{ $invoice->signature_name ?? $invoice->pejabat ?? 'BUDI SANTOSO' }}</div>
            </div>

        </div>
    </div>
</body>
</html>
