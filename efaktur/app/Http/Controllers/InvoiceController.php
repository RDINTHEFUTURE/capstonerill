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
            'npwp' => ['nullable', 'string', 'max:32'],
            'nama' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
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
            'npwp' => ['nullable', 'string', 'max:32'],
            'nama' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
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
                'tanggal' => $invoice->tanggal->format('Y-m-d'),
                'npwp' => $invoice->npwp,
                'nama' => $invoice->nama,
                'alamat' => $invoice->alamat,
                'total' => $invoice->total,
                'currency' => $invoice->currency,
            ]);
            $invoice->update(['qr_payload' => $payload]);
        }

        // Perbaikan QR:
        // Payload QR sebelumnya disimpan base64(JSON) dan langsung dipakai sebagai input QR.
        // Ubah agar QR berisi JSON UTF-8 plain (tanpa base64) supaya lebih kompatibel
        // dengan pembaca QR yang mengharapkan teks JSON.
        $decoded = json_decode(base64_decode($payload, true), true);
        if (is_array($decoded)) {
            $payloadForQr = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            // jika format payload tidak base64, fallback langsung
            $payloadForQr = $payload;
        }

        $qrCode = QrCode::create($payloadForQr)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->setSize(280)
            ->setMargin(10);

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
            'npwp' => $data['npwp'] ?? null,
            'nama' => $data['nama'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'total' => (float) $data['total'],
            'currency' => $data['currency'] ?? 'IDR',
        ];

        return base64_encode(
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}

