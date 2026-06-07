<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pajak Resmi Direktorat Jenderal Pajak</title>
    <style>
        /* Standarisasi Cetak Halaman Kerja A4 */
        @page {
            size: A4 portrait;
            margin: 15mm 10mm 15mm 10mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            color: #000000;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }
        .faktur-container {
            width: 190mm;
            border: 1.5px solid #000000;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 8px 8px 12px;
        }
        .faktur-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            padding: 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #000000;
        }
        .serial-bar {
            padding: 6px 8px;
            border-bottom: 1.5px solid #000000;
            font-size: 9pt;
        }
        .section-divider {
            font-weight: bold;
            font-size: 8.5pt;
            padding: 4px 8px;
            border-bottom: 1px solid #000000;
            margin-top: 8px;
        }
        .identity-table,
        .items-table,
        .summary-table,
        .footer-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .identity-table td,
        .summary-table td,
        .footer-grid td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .w-label { width: 35mm; }
        .w-colon { width: 3mm; text-align: center; }
        .w-value { width: calc(100% - 38mm); }
        .items-table th {
            font-size: 8.5pt;
            font-weight: normal;
            text-align: center;
            padding: 6px 4px;
            border-bottom: 1px solid #000000;
            border-right: 1px solid #000000;
            vertical-align: middle;
        }
        .items-table th:last-child {
            border-right: none;
        }
        .items-table td {
            padding: 6px 8px;
            border-right: 1px solid #000000;
            vertical-align: top;
        }
        .items-table td:last-child {
            border-right: none;
        }
        .items-table td.code-cell {
            text-align: center;
        }
        .items-table td.qty-cell,
        .items-table td.price-cell,
        .items-table td.subtotal-cell {
            text-align: right;
        }
        .blank-row-height {
            height: 80mm;
        }
        .summary-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #000000;
        }
        .summary-table tr:last-child td {
            border-bottom: none;
        }
        .summary-label {
            width: 135mm;
            border-right: 1px solid #000000;
        }
        .summary-value {
            width: 55mm;
            text-align: right;
        }
        .footer-block {
            width: 100%;
            padding: 10px 8px;
        }
        .footer-grid td {
            vertical-align: top;
            padding: 0;
        }
        .legal-notice {
            width: 125mm;
            font-size: 8pt;
            text-align: justify;
            line-height: 1.4;
            padding-right: 20px;
        }
        .signature-area {
            width: 65mm;
            text-align: center;
            padding-left: 10px;
        }
        .qr-placeholder,
        .invoice-qr-box {
            width: 25mm;
            height: 25mm;
            border: 1px dashed #999999;
            margin: 8px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .invoice-signature-image {
            max-width: 100%;
            max-height: 40mm;
        }
        .warning-text {
            margin-top: 15px;
            font-size: 7pt;
            border-top: 1px dashed #000000;
            padding-top: 5px;
            text-align: justify;
            line-height: 1.3;
        }
        .invoice-text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="faktur-container">
        <div class="faktur-title">Faktur Pajak</div>
        <div class="serial-bar">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 55mm; font-weight: bold; padding: 0;">Kode dan Nomor Seri Faktur Pajak:</td>
                    <td style="padding: 0;">{{ $invoice->nomor ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="section-divider">Pengusaha Kena Pajak</div>
        <table class="identity-table">
            <tr>
                <td class="w-label">Nama</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->nama_penjual ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-label">Alamat</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->alamat_penjual ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-label">NPWP</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->npwp_penjual ?? '-' }}</td>
            </tr>
        </table>
        <div class="section-divider">Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak</div>
        <table class="identity-table">
            <tr>
                <td class="w-label">Nama</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->nama_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-label">Alamat</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->alamat_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-label">NPWP</td>
                <td class="w-colon">:</td>
                <td class="w-value">{{ $invoice->npwp_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-label">Nomor Paspor</td>
                <td class="w-colon">:</td>
                <td class="w-value"></td>
            </tr>
            <tr>
                <td class="w-label">Identitas Lain</td>
                <td class="w-colon">:</td>
                <td class="w-value"></td>
            </tr>
        </table>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 12mm;">No.</th>
                    <th style="width: 25mm;">Kode Barang/<br>Jasa</th>
                    <th style="width: 98mm;">Nama Barang Kena Pajak / Jasa Kena Pajak</th>
                    <th style="width: 55mm;">Harga Jual / Penggantian /<br>Uang Muka / Termin<br>(Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php($items = $invoice->items ?? collect())
                @forelse($items as $i => $item)
                    <tr>
                        <td class="code-cell">{{ $i + 1 }}</td>
                        <td class="code-cell"></td>
                        <td>{{ $item->nama_produk ?? '-' }}</td>
                        <td class="price-cell">{{ number_format((float)($item->harga ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr class="blank-row-height">
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td></td>
                        <td style="text-align: right;"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <table class="summary-table">
            <tr>
                <td class="summary-label">Harga Jual / Penggantian / Uang Muka / Termin</td>
                <td class="summary-value">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-label">Dikurangi Potongan Harga</td>
                <td class="summary-value"></td>
            </tr>
            <tr>
                <td class="summary-label">Dikurangi Uang Muka yang telah diterima</td>
                <td class="summary-value"></td>
            </tr>
            <tr>
                <td class="summary-label">Dasar Pengenaan Pajak</td>
                <td class="summary-value">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-label">Jumlah PPN (Pajak Pertambahan Nilai)</td>
                <td class="summary-value"></td>
            </tr>
            <tr>
                <td class="summary-label">Jumlah PPnBM (Pajak Penjualan atas Barang Mewah)</td>
                <td class="summary-value"></td>
            </tr>
        </table>
        <div class="footer-block">
            <table class="footer-grid">
                <tr>
                    <td class="legal-notice">
                        Sesuai dengan ketentuan yang berlaku, Direktorat Jenderal Pajak mengatur bahwa Faktur Pajak ini telah ditandatangani secara elektronik sehingga tidak memerlukan tanda tangan basah pada Faktur Pajak ini.
                    </td>
                        <td class="signature-area">
                            <div>, </div>

                            @php($signatureType = $invoice->signature_type ?? 'qr')
                            <div style="font-size:7.5pt; color:#333; margin-bottom:3px;">Bukti Tanda Tangan (QR)</div>

                            <!-- Hand signature kept in DB, but not displayed in UI anymore -->
                            @if($signatureType === 'hand')
                                <div class="qr-placeholder" aria-hidden="true"></div>
                            @else
                                @if(!empty($invoice->qr_image))
                                    <div class="qr-placeholder">
                                        <img src="{{ $invoice->qr_image }}" alt="QR Bukti" style="max-width:100%; max-height:100%;" />
                                    </div>
                                @else
                                    <div class="qr-placeholder"></div>
                                @endif
                            @endif

                            <div style="font-weight: bold; min-height: 14px; margin-top: 5px;">{{ $invoice->signature_name ?? $invoice->pejabat ?? 'Nama Penandatangan' }}</div>

                            <div style="font-size: 7.5pt; color: #444444; border-top: 0.5px solid #999999; width: 85%; margin: 3px auto 0 auto; padding-top: 2px;">Nama Penandatangan</div>
                        </td>
                </tr>
            </table>
            <div class="warning-text">
                PERINGATAN: PKP yang membuat Faktur Pajak yang tidak sesuai dengan keadaan yang sebenarnya dan/atau sesungguhnya sebagaimana dimaksud dalam Pasal 13 ayat (9) UU PPN dikenai sanksi sesuai dengan Pasal 14 ayat (4) UU KUP.
            </div>
        </div>
    </div>
</body>
</html>
