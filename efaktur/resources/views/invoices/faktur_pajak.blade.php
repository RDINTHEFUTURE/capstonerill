<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pajak Elektronik</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-md border border-gray-300 text-xs text-gray-800 leading-relaxed">
        
        <div class="text-center border-b pb-3 mb-4">
            <h1 class="text-lg font-bold uppercase tracking-wider text-gray-900">Faktur Pajak</h1>
            <p class="font-mono text-sm font-bold tracking-widest mt-1">Kode &amp; Nomor Seri: 040.026-00.126646818</p>
        </div>

        <div class="border p-3 rounded-md mb-4 bg-gray-50/50">
            <h2 class="font-bold text-gray-900 border-b pb-1 mb-2 uppercase tracking-wide text-[10px] text-blue-800">Pengusaha Kena Pajak (Penjual)</h2>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-2 font-semibold">Nama:</div>
                <div class="col-span-10 font-bold">ARYA PRADANA INDONESIA</div>
                
                <div class="col-span-2 font-semibold">Alamat:</div>
                <div class="col-span-10 text-gray-600">PERUM PERMATA CIKARANG TIMUR BLOK R5/18, RT 000 RW 000, JATIREJA, CIKARANG TIMUR, KAB. BEKASI, JAWA BARAT 17822</div>
                
                <div class="col-span-2 font-semibold">NPWP:</div>
                <div class="col-span-10 font-mono font-bold tracking-wider">00.374.203.1-241.4000</div>
            </div>
        </div>

        <div class="border p-3 rounded-md mb-4 bg-gray-50/50">
            <h2 class="font-bold text-gray-900 border-b pb-1 mb-2 uppercase tracking-wide text-[10px] text-green-800">Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak</h2>
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-2 font-semibold">Nama:</div>
                <div class="col-span-10 font-bold">METAL CASTINDO INDUSTRITAMA</div>
                
                <div class="col-span-2 font-semibold">Alamat:</div>
                <div class="col-span-10 text-gray-600">JL PANGERAN DIPONEGORO NO 108, RT 00- RW 00-, SETIADARMA, TAMBUN SELATAN, KAB. BEKASI, JAWA BARAT 17513</div>
                
                <div class="col-span-2 font-semibold">NPWP:</div>
                <div class="col-span-10 font-mono font-bold tracking-wider">00.210.795.4-643.1000</div>
            </div>
        </div>

        <table class="w-full text-left border mb-4">
            <thead>
                <tr class="bg-gray-100 font-bold border-b text-gray-700">
                    <th class="p-2 w-12 text-center border-r">No.</th>
                    <th class="p-2 border-r">Nama Barang Kena Pajak / Jasa Kena Pajak</th>
                    <th class="p-2 text-right w-44">Harga Jual / Penggantian (IDR)</th>
                </tr>
            </thead>
            <tbody class="divide-y font-mono">
                <tr>
                    <td class="p-2.5 text-center border-r align-top">1</td>
                    <td class="p-2.5 border-r">
                        <span class="font-sans font-semibold text-gray-800 block">Material Industri Pendukung Operasional Mesin Utama</span>
                        <div class="text-[10px] text-gray-500 mt-1">
                            Rp 1.150.000,00 x 3,00 Piece<br>
                            Potongan Harga: Rp 0,00 | PPnBM (0,00%): Rp 0,00
                        </div>
                    </td>
                    <td class="p-2.5 text-right font-bold align-top">3.450.000,00</td>
                </tr>
            </tbody>
        </table>

        <div class="border rounded-md p-3 bg-gray-50/30 font-mono space-y-1.5 max-w-md ml-auto">
            <div class="flex justify-between">
                <span class="font-sans text-gray-600">Harga Jual/Penggantian/Termin:</span>
                <span class="font-bold">3.450.000,00</span>
            </div>
            <div class="flex justify-between">
                <span class="font-sans text-gray-600">Dikurangi Potongan Harga:</span>
                <span class="text-gray-400">0,00</span>
            </div>
            <div class="flex justify-between">
                <span class="font-sans text-gray-600">Dikurangi Uang Muka yang diterima:</span>
                <span class="text-gray-400">0,00</span>
            </div>
            <div class="flex justify-between pt-1 border-t border-dashed font-sans font-bold text-gray-900">
                <span>Dasar Pengenaan Pajak (DPP):</span>
                <span class="font-mono">3.450.000,00</span>
            </div>
            <div class="flex justify-between text-blue-800 font-bold font-sans">
                <span>Jumlah PPN (Pajak Pertambahan Nilai 11%):</span>
                <span class="font-mono">379.500,00</span>
            </div>
            <div class="flex justify-between text-gray-400">
                <span class="font-sans">Jumlah PPnBM (Barang Mewah):</span>
                <span>0,00</span>
            </div>
        </div>

        <div class="mt-6 border-t pt-4 text-[10px] text-gray-400 italic space-y-1">
            <p>Sesuai dengan ketentuan yang berlaku, Direktorat Jenderal Pajak mengatur bahwa Faktur Pajak ini telah ditandatangani secara elektronik sehingga tidak diperlukan tanda tangan basah pada Faktur Pajak ini.</p>
            <p>Pemberitahuan Faktur Pajak telah dilaporkan ke Direktorat Jenderal Pajak dan telah memperoleh persetujuan resmi sistem e-Faktur.</p>
            <div class="text-right text-gray-600 font-sans font-semibold not-italic mt-4">
                <p>KAB. BEKASI, 14 April 2026</p>
                <p class="font-bold uppercase tracking-wider text-gray-800 mt-1">WIDIYANTO</p>
            </div>
        </div>

    </div>
</body>
</html>
