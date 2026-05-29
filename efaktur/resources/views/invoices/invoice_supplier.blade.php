<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_no ?? 'INV APL 0164-5J 26' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media print {
            body { background: white; color: black; }
            .no-print { display: none; }
            .print-border { border: 1px solid #000 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans">

    <div class="max-w-4xl mx-auto mb-4 flex justify-end no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-md border border-gray-200 print-border">
        
        <div class="flex justify-between items-start border-b pb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-xl shadow">AP</div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-wide">{{ $supplier_name ?? 'PT. ARYA PRADANA INDONESIA' }}</h1>
                </div>
                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider">GENERAL TRADING, FABRICATION, MACHINING, MECHANICAL ELECTRICAL</p>
                <p class="text-xs text-gray-500 mt-1">Kp Rawa Bangkong RT 002 RW 006, Sertajaya, Cikarang Timur - Bekasi</p>
                <p class="text-xs text-gray-500">Hubungi: 081294724933 | Email: pt.aryapradanaindonesia@gmail.com</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-extrabold text-blue-600 tracking-tight">INVOICE</h2>
                <div class="mt-4 text-sm text-gray-600 space-y-1">
                    <p><span class="font-semibold">Invoice No:</span> {{ $invoice_no ?? 'INV APL 0164-5J 26' }}</p>
                    <p><span class="font-semibold">PO No:</span> {{ $po_no ?? '308/MCI/PO IV/2026' }}</p>
                    <p><span class="font-semibold">PO Date:</span> {{ $po_date ?? '13-Apr-2026' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 my-6 text-sm">
            <div class="bg-gray-50 p-4 rounded-md border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditujukan Kepada:</h3>
                <p class="font-bold text-gray-800">{{ $customer_name ?? 'PT. METAL CASTINDO INDUSTRITAMA' }}</p>
                <p class="text-gray-600 mt-1 leading-relaxed text-xs">
                    Jl. Pangeran Diponegoro No. 108 RW 02/01,
                    <br>
                    Setia Darma, Tambun Selatan, Bekasi 17510
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-md border border-gray-100 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Metode Pembayaran:</h3>
                    <p class="text-xs text-gray-600 font-medium">Mohon transfer penuh nominal ke rekening resmi berikut:</p>
                    <p class="font-bold text-blue-700 mt-1 text-base">Bank Mandiri</p>
                    <p class="font-mono text-sm tracking-wider font-bold text-gray-800">1560024343297</p>
                    <p class="text-xs text-gray-500 font-semibold">a.n. PT. Arya Pradana Indonesia</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto my-6">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-blue-600 text-white uppercase text-xs tracking-wider">
                        <th class="py-3 px-4 rounded-l-md w-12 text-center">No</th>
                        <th class="py-3 px-4">Deskripsi Barang / Jasa</th>
                        <th class="py-3 px-4 text-center w-20">Qty</th>
                        <th class="py-3 px-4 text-center w-20">Unit</th>
                        <th class="py-3 px-4 text-right w-36">Harga Satuan</th>
                        <th class="py-3 px-4 text-right rounded-r-md w-40">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- Loop data jika menggunakan Laravel --}}
                    {{-- @foreach($items as $index => $item) --}}
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-4 px-4 text-center font-medium text-gray-500">1</td>
                        <td class="py-4 px-4 font-semibold text-gray-800">
                            {{ $item['description'] ?? 'Material / Sparepart (Sesuai PO)' }}
                        </td>
                        <td class="py-4 px-4 text-center font-semibold text-gray-700">3</td>
                        <td class="py-4 px-4 text-center text-gray-600">Pcs</td>
                        <td class="py-4 px-4 text-right font-mono text-gray-700">Rp 1.150.000</td>
                        <td class="py-4 px-4 text-right font-mono font-bold text-gray-900">Rp 3.450.000</td>
                    </tr>
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-12 gap-4 mt-8 pt-4 border-t">
            <div class="col-span-7">
                <p class="text-xs text-gray-400 italic font-medium">* Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan tanpa perjanjian awal.</p>
            </div>
            <div class="col-span-5 text-sm text-gray-700 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-500">Sub Total</span>
                    <span class="font-mono text-gray-800">Rp 3.450.000</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-500">Potongan Harga</span>
                    <span class="font-mono text-gray-400">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-500">PPN (11%)</span>
                    <span class="font-mono text-gray-800">Rp 379.500</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-300">
                    <span class="font-bold text-gray-800 text-base">Grand Total</span>
                    <span class="font-mono font-extrabold text-blue-700 text-lg">Rp 3.829.500</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-12 pt-6">
            <div class="text-center w-40 text-xs text-gray-400">
                <p>Penerima,</p>
                <div class="h-16"></div>
                <p class="border-t border-gray-300 pt-1">( _________________ )</p>
            </div>
            <div class="text-center w-52 text-xs text-gray-700">
                <p>Bekasi, 14 April 2026</p>
                <p class="font-semibold text-gray-500 uppercase mt-0.5">PT. ARYA PRADANA INDONESIA</p>
                <div class="h-16 flex items-center justify-center">
                    <span class="text-blue-500/30 text-xs tracking-widest font-serif border border-dashed border-blue-300 p-1 rounded transform -rotate-3 font-bold">APPROVED &amp; STAMPED</span>
                </div>
                <p class="font-bold border-t border-gray-400 pt-1 text-gray-900">WIDIYANTO</p>
                <p class="text-gray-400 text-[10px]">Director</p>
            </div>
        </div>

    </div>
</body>
</html>
