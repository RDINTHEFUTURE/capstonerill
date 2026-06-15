<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Faktur Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-pdf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/invoice-preview.css') }}">
</head>
<body class="invoice-pdf-page">

    <div class="invoice-faktur-container">
        {{-- Reuse the same Faktur Pajak table model as preview to keep borders pixel-aligned in browser/print/PDF. --}}
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

        @php
            $items = $invoice->items ?? collect();
            $minItemLines = 5;
            $padCount = max(0, $minItemLines - $items->count());
            $padRows = $padCount;
        @endphp

        <table class="faktur-grid">
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-kode">Kode Barang/<br>Jasa</th>
                    <th class="col-nama">Nama Barang Kena Pajak / Jasa Kena Pajak</th>
                    <th class="col-harga">Harga Jual / Penggantian /<br>Uang Muka / Termin<br>(Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $item)
                    <tr>
                        <td class="code-cell text-center">{{ $i + 1 }}</td>
                        <td class="code-cell"></td>
                        <td>{{ $item->nama_produk ?? '-' }}</td>
                        <td class="price-cell">{{ number_format((float)($item->harga ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="code-cell text-center"></td>
                        <td class="code-cell"></td>
                        <td></td>
                        <td class="price-cell"></td>
                    </tr>
                @endforelse

                @for($j = 0; $j < $padRows; $j++)
                    <tr class="faktur-pad-row">
                        <td class="code-cell text-center"></td>
                        <td class="code-cell"></td>
                        <td></td>
                        <td class="price-cell"></td>
                    </tr>
                @endfor

                {{-- Summary rows, same 4-column grid to make borders intersect reliably. --}}
                <tr>
                    <td class="summary-label" colspan="3">Harga Jual / Penggantian / Uang Muka / Termin</td>
                    <td class="summary-value">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label" colspan="3">Dikurangi Potongan Harga</td>
                    <td class="summary-value"></td>
                </tr>
                <tr>
                    <td class="summary-label" colspan="3">Dikurangi Uang Muka yang telah diterima</td>
                    <td class="summary-value"></td>
                </tr>
                <tr>
                    <td class="summary-label" colspan="3">Dasar Pengenaan Pajak</td>
                    <td class="summary-value">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label" colspan="3">Jumlah PPN (Pajak Pertambahan Nilai)</td>
                    <td class="summary-value"></td>
                </tr>
                <tr>
                    <td class="summary-label" colspan="3">Jumlah PPnBM (Pajak Penjualan atas Barang Mewah)</td>
                    <td class="summary-value"></td>
                </tr>
            </tbody>
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

                        @if($signatureType === 'hand')
                            <div class="qr-placeholder" aria-hidden="true"></div>
                            <div style="visibility:hidden;">Hand signature</div>
                        @else
                            @php($qrFromOld = old('qr_image'))
                            @php($qrDataUri = null)

                            @if(!empty($qrFromOld) && is_string($qrFromOld))
                                @php($qrDataUri = $qrFromOld)
                            @endif

                            @if(!empty($qrDataUri))
                                <div class="qr-placeholder">
                                    <img src="{{ $qrDataUri }}" alt="QR Upload" style="max-width:100%; max-height:100%;" />
                                </div>
                            @elseif(!empty($invoice->qr_image))
                                <div class="qr-placeholder">
                                    <img src="{{ $invoice->qr_image }}" alt="QR Invoice" style="max-width:100%; max-height:100%;" />
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


