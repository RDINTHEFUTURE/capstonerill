<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::query()->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => ['required', 'string', 'max:255', 'unique:invoices,nomor'],
            'tanggal' => ['required', 'date'],

            // Seller
            'npwp_penjual' => ['nullable', 'string', 'max:32'],
            'nama_penjual' => ['nullable', 'string', 'max:255'],
            'alamat_penjual' => ['nullable', 'string', 'max:255'],

            // Buyer
            'npwp_pembeli' => ['nullable', 'string', 'max:32'],
            'nama_pembeli' => ['nullable', 'string', 'max:255'],
            'alamat_pembeli' => ['nullable', 'string', 'max:255'],

            'total' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
        ]);

        $payload = $this->buildQrPayload($validated);

        $invoice = Invoice::create([
            ...$validated,
            'currency' => $validated['currency'] ?? 'IDR',
            'qr_payload' => $payload,
        ]);


        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice tersimpan dan payload QR dibuat.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'nomor' => ['required', 'string', 'max:255', 'unique:invoices,nomor,' . $invoice->id],
            'tanggal' => ['required', 'date'],

            // Seller
            'npwp_penjual' => ['nullable', 'string', 'max:32'],
            'nama_penjual' => ['nullable', 'string', 'max:255'],
            'alamat_penjual' => ['nullable', 'string', 'max:255'],

            // Buyer
            'npwp_pembeli' => ['nullable', 'string', 'max:32'],
            'nama_pembeli' => ['nullable', 'string', 'max:255'],
            'alamat_pembeli' => ['nullable', 'string', 'max:255'],

            'total' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
        ]);


        $payload = $this->buildQrPayload($validated);

        $invoice->update([
            ...$validated,
            'currency' => $validated['currency'] ?? 'IDR',
            'qr_payload' => $payload,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice diperbarui dan payload QR diupdate.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function qr(Invoice $invoice)
    {
        // Pastikan payload QR selalu ada dan sesuai data terbaru.
        $payload = $invoice->qr_payload;
        if (!$payload) {
            $payload = $this->buildQrPayload([
                'nomor' => $invoice->nomor,
                'tanggal' => $invoice->tanggal?->format('Y-m-d'),
                'npwp_penjual' => $invoice->npwp_penjual,
                'nama_penjual' => $invoice->nama_penjual,
                'alamat_penjual' => $invoice->alamat_penjual,

                'npwp_pembeli' => $invoice->npwp_pembeli,
                'nama_pembeli' => $invoice->nama_pembeli,
                'alamat_pembeli' => $invoice->alamat_pembeli,

                'total' => $invoice->total,
                'currency' => $invoice->currency,
            ]);
            $invoice->update(['qr_payload' => $payload]);
        }



        // QR akan diarahkan ke PDF faktur.
        // Jadi isi QR adalah URL PDF invoice ini, bukan JSON payload.
        $fakturUrl = route('invoices.pdf', $invoice);


        // endroid/qr-code versi terpasang (lihat vendor/endroid/qr-code/src/QrCode.php)
        // QrCode dibuat via constructor tanpa setter fluent (class bersifat readonly).
        $qrCode = new QrCode(
            data: $fakturUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
            size: 280,
            margin: 10,
        );





        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), 200)
            ->header('Content-Type', 'image/png');

    }


    private function buildQrPayload(array $data): string
    {
        // Simpan di database sebagai base64(JSON) agar ringkas dan aman disimpan.
        // Saat QR dirender, akan di-decode kembali menjadi JSON plain.
        $payload = [
            'app' => 'efaktur-laravel',
            'nomor' => $data['nomor'],
            'tanggal' => $data['tanggal'],

            // Seller
            'npwp_penjual' => $data['npwp_penjual'] ?? null,
            'nama_penjual' => $data['nama_penjual'] ?? null,
            'alamat_penjual' => $data['alamat_penjual'] ?? null,

            // Buyer
            'npwp_pembeli' => $data['npwp_pembeli'] ?? null,
            'nama_pembeli' => $data['nama_pembeli'] ?? null,
            'alamat_pembeli' => $data['alamat_pembeli'] ?? null,

            'total' => (float) $data['total'],
            'currency' => $data['currency'] ?? 'IDR',
        ];


        return base64_encode(
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}

