<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Faktur {{ $invoice->nomor }}</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-preview.css') }}">
</head>
<body class="invoice-preview-page">
    <div class="invoice-toolbar">
        <a class="btn btn-secondary" href="{{ route('invoices.show', $invoice) }}">&larr; Kembali</a>
        <a class="btn" href="{{ route('invoices.pdf', $invoice) }}" target="_blank" rel="noopener">Unduh PDF</a>
        <button class="btn" type="button" onclick="window.print()">PRINT</button>
    </div>

    <div class="invoice-preview-panel">
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
                            @if(!empty($invoice->qr_image))
                                <div class="qr-placeholder">
                                    <img src="{{ $invoice->qr_image }}" alt="QR Invoice" style="max-width:100%; max-height:100%;" />
                                </div>
                            @else
                                <div class="qr-placeholder"></div>
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
    </div>
</body>
</html>
