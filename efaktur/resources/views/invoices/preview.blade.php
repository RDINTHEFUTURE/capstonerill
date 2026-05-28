<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Faktur {{ $invoice->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f3f4f6;
            color: #111;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .toolbar a,
        .toolbar button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            color: #fff;
            background: #2563eb;
            cursor: pointer;
        }
        .toolbar a.secondary {
            background: #6b7280;
        }
        .preview-panel {
            max-width: 900px;
            margin: 0 auto;
        }
        .faktur-container {
            background: #fff;
            border: 1px solid #d1d5db;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            padding: 18px;
            width: 100%;
            box-sizing: border-box;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 18px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: -1px;
        }
        th, td {
            border: 1px solid #111;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer-section {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
        }
        .qr-code {
            width: 120px;
            min-height: 120px;
            border: 1px solid #111;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            background: #fff;
        }
        .ttd-box {
            text-align: center;
            width: 260px;
        }
        .note {
            margin-top: 14px;
            font-size: 13px;
            color: #4b5563;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .faktur-container { box-shadow: none; border-color: #000; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a class="secondary" href="{{ route('invoices.show', $invoice) }}">&larr; Kembali</a>
        <a href="{{ route('invoices.pdf', $invoice) }}">Unduh PDF</a>
        <button type="button" onclick="window.print()">PRINT</button>
    </div>

    <div class="preview-panel">
        <div class="faktur-container">
            <div class="title">FAKTUR PENJUALAN</div>

            <table>
                <tr>
                    <td style="width: 30%; font-weight: bold;">Nomor Invoice:</td>
                    <td style="width: 70%; font-weight: bold;">{{ $invoice->nomor ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 30%; font-weight: bold;">Tanggal:</td>
                    <td style="width: 70%; font-weight: bold;">{{ $invoice->tanggal ? $invoice->tanggal->format('d M Y') : '-' }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="2">PENJUAL</th>
                </tr>
                <tr>
                    <td style="width: 30%;">Nama:</td>
                    <td style="width: 70%;">{{ $invoice->nama_penjual ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat:</td>
                    <td>{{ $invoice->alamat_penjual ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NPWP:</td>
                    <td>{{ $invoice->npwp_penjual ?? '-' }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="2">PEMBELI</th>
                </tr>
                <tr>
                    <td style="width: 30%;">Nama:</td>
                    <td style="width: 70%;">{{ $invoice->nama_pembeli ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat:</td>
                    <td>{{ $invoice->alamat_pembeli ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NPWP / NIK:</td>
                    <td>{{ $invoice->npwp_pembeli ?? '-' }}</td>
                </tr>
            </table>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 45%;">Produk</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 20%;">Harga (Rp)</th>
                        <th style="width: 20%;">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php($items = $invoice->items ?? collect())
                    @foreach($items as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $item->nama_produk ?? '-' }}</td>
                            <td class="text-right">{{ $item->qty ?? 1 }}</td>
                            <td class="text-right">{{ number_format((float)($item->harga ?? 0), 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format((float)($item->subtotal ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table>
                <tr>
                    <td style="width: 60%;">Total</td>
                    <td style="width: 40%;" class="text-right">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="footer-section">
                <div class="qr-code">
                    <img
                        src="{{ route('invoices.qr', $invoice) }}"
                        alt="QR Invoice"
                        style="width: 100%; height: auto; object-fit: contain; border: none;"
                    >
                </div>

                <div class="ttd-box">
                    <div style="margin-bottom: 8px;">{{ $invoice->tanggal ? $invoice->tanggal->format('d M Y') : '-' }}</div>
                    <div>{{ $invoice->role_penandatangan ?? 'Admin Supplier Perusahaan' }},</div>
                    <br><br><br>
                    <div style="text-decoration: underline; font-weight: bold;">{{ $invoice->pejabat ?? 'BUDI SANTOSO' }}</div>
                </div>
            </div>

            <div class="note">
                Ini adalah preview faktur sebelum dicetak atau diunduh sebagai PDF.
            </div>
        </div>
    </div>
</body>
</html>
