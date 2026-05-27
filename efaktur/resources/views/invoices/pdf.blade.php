<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample E-Faktur Pajak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .faktur-container {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ccc;
            box-sizing: border-box;
            position: relative;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: -1px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .footer-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            text-align: center;
        }

        .ttd-box {
            text-align: center;
            width: 250px;
        }

        /* Print/PDF layout */
        @media print {
            body { background: none; padding: 0; }
            .faktur-container {
                border: none;
                margin: 0;
                padding: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
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
                <td style="width: 70%;">{{ $invoice->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td>{{ $invoice->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPWP:</td>
                <td>{{ $invoice->npwp ?? '-' }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2">PEMBELI</th>
            </tr>

            <tr>
                <td style="width: 30%;">Nama:</td>
                <td style="width: 70%;">{{ $invoice->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td>{{ $invoice->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPWP / NIK:</td>
                <td>{{ $invoice->npwp ?? '-' }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 55%;">Uraian</th>
                    <th style="width: 40%;">Harga (Rp)</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ $invoice->nomor ? ('Invoice ' . $invoice->nomor) : '-' }}</td>
                    <td class="text-right">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>-</td>
                    <td class="text-right">0</td>
                </tr>
                <tr>
                    <td class="text-center">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-right">&nbsp;</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tr>
                <td style="width: 60%;">Harga Jual / Penggantian</td>
                <td style="width: 40%;" class="text-right">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dikurangi Potongan Harga</td>
                <td class="text-right">0</td>
            </tr>
            <tr>
                <td>Dikurangi Uang Muka yang telah diterima</td>
                <td class="text-right">0</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format((float)$invoice->total, 0, ',', '.') }}</td>
            </tr>

            </tr>
        </table>

        <div class="footer-section">
            <div class="qr-code">
                <div style="display:flex; flex-direction:column; align-items:center; gap:6px;">
                    <div style="font-weight:bold; font-size:10px;">[ QR CODE INVOICE ]</div>

                    <img
                        src="{{ route('invoices.qr', $invoice) }}"
                        alt="QR Invoice"
                        style="width: 76px; height: 76px; object-fit: contain; border: none;"
                    >
                </div>
            </div>

            <div class="ttd-box">
                <div style="margin-bottom: 6px;">{{ $invoice->tanggal ? $invoice->tanggal->format('d M Y') : '-' }}</div>
                <div>Direktur / Pejabat yang Ditunjuk,</div>
                <br><br><br>
                <div style="text-decoration: underline; font-weight: bold;">BUDI SANTOSO</div>
            </div>
        </div>
    </div>
</body>
</html>


