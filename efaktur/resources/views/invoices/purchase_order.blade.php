<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $po_number ?? '308/MCI/PO-IV/2026' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-md border border-gray-200">
        
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $company_customer ?? 'PT. METAL CASTINDO INDUSTRITAMA' }}</h1>
                <p class="text-xs text-gray-600 mt-1">Pangeran Diponegoro No. 108 Rw 02/01, Setia Darma</p>
                <p class="text-xs text-gray-600">Tambun Selatan, Bekasi 17510 - INDONESIA</p>
                <p class="text-xs text-gray-500">Phone: (021) 88368880 | Fax: (021)-88368881</p>
                <p class="text-xs font-semibold text-gray-700 mt-1">NPWP: 02.107.954.6-431-000</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800 tracking-wide border-2 border-gray-800 px-3 py-1 inline-block uppercase bg-gray-50">Purchase Order</h2>
                <div class="mt-3 text-xs space-y-1 text-gray-600">
                    <p><span class="font-bold">PO No:</span> {{ $po_number ?? '308/MCI/PO-IV/2026' }}</p>
                    <p><span class="font-bold">Date:</span> 13 April 2026</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 my-6 text-xs">
            <div class="border p-3 rounded-md">
                <span class="font-bold text-gray-500 block mb-1 uppercase tracking-wider text-[10px]">VENDOR / SUPPLIER:</span>
                <p class="font-bold text-sm text-gray-800">PT. ARYA PRADANA INDONESIA</p>
                <p class="text-gray-600 mt-0.5">Kp Rawa Bangkong RT 002 RW 006, Sertajaya</p>
                <p class="text-gray-600">Cikarang Timur - Bekasi</p>
                <p class="font-medium text-gray-700 mt-2">UP: Bapak Widiyanto</p>
            </div>
            <div class="border p-3 rounded-md grid grid-cols-2 gap-2 bg-gray-50/50">
                <div>
                    <span class="font-bold text-gray-400 block text-[9px] uppercase">Syarat Pembayaran:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">50% DP, 50% After Invoice</p>
                </div>
                <div>
                    <span class="font-bold text-gray-400 block text-[9px] uppercase">Pengiriman:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">CIF (Segera)</p>
                </div>
            </div>
        </div>

        <table class="w-full text-left border text-xs">
            <thead>
                <tr class="bg-gray-800 text-white font-bold uppercase tracking-wider text-[11px]">
                    <th class="p-2.5 w-10 text-center border-r border-gray-700">No</th>
                    <th class="p-2.5 border-r border-gray-700">Deskripsi Detail Pesanan Barang</th>
                    <th class="p-2.5 text-center w-16 border-r border-gray-700">Qty</th>
                    <th class="p-2.5 text-center w-16 border-r border-gray-700">Unit</th>
                    <th class="p-2.5 text-right w-32 border-r border-gray-700">Harga Satuan</th>
                    <th class="p-2.5 text-right w-36">Total (IDR)</th>
                </tr>
            </thead>
            <tbody class="divide-y border-b">
                <tr class="align-top">
                    <td class="p-3 text-center border-r text-gray-500 font-medium">1</td>
                    <td class="p-3 border-r font-semibold text-gray-800">
                        Material Sparepart Komponen Mesin Industri (Sesuai Spesifikasi Teknis Pekerjaan)
                    </td>
                    <td class="p-3 text-center border-r font-bold text-gray-700">3</td>
                    <td class="p-3 text-center border-r text-gray-600">pcs</td>
                    <td class="p-3 text-right border-r font-mono">Rp 1.150.000</td>
                    <td class="p-3 text-right font-mono font-bold">Rp 3.450.000</td>
                </tr>
            </tbody>
        </table>

        <div class="grid grid-cols-12 gap-4 mt-4 text-xs">
            <div class="col-span-7 border p-3 rounded-md bg-yellow-50/40 text-gray-600 space-y-1">
                <p class="font-bold text-yellow-800 uppercase tracking-wide text-[10px]">Catatan Penting Pelaksanaan PO:</p>
                <p>1. Mohon cantumkan Nomor PO ini pada invoice, Surat Jalan, dan seluruh dokumen pengiriman.</p>
                <p>2. Hanya dokumen PO Asli / Berstempel resmi yang dianggap valid untuk proses penagihan keuangan.</p>
                <p class="text-[10px] text-gray-400 mt-2">Form Ref: F-037 rev.00 | Effective: 24/05/2010</p>
            </div>
            <div class="col-span-5 space-y-1.5 font-medium text-gray-700">
                <div class="flex justify-between">
                    <span>Sub Total:</span>
                    <span class="font-mono">Rp 3.450.000</span>
                </div>
                <div class="flex justify-between">
                    <span>PPN (11%):</span>
                    <span class="font-mono">Rp 379.500</span>
                </div>
                <div class="flex justify-between pt-1 border-t text-sm font-bold text-gray-900">
                    <span>Total Keseluruhan:</span>
                    <span class="font-mono text-gray-900">Rp 3.829.500</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-end mt-12 text-xs">
            <div class="text-center w-48">
                <p class="mb-14 text-gray-500">Disetujui Oleh Vendor,</p>
                <p class="border-t font-bold text-gray-800 pt-1 uppercase">WIDIYANTO</p>
            </div>
            <div class="text-center w-48">
                <p class="mb-14 text-gray-500">Dibuat &amp; Disahkan Oleh,</p>
                <p class="border-t font-bold text-gray-900 pt-1 uppercase">NUR LUKMAN YUDIANTO</p>
                <p class="text-[10px] text-gray-400 font-semibold">Authorized Purchasing Officer</p>
            </div>
        </div>

    </div>
</body>
</html>
