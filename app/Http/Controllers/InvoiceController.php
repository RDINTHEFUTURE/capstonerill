<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\ActivityLog;
use App\Services\JournalService;
use App\Services\InvoiceNumberService;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('nama_penjual', 'like', "%{$search}%")
                  ->orWhere('nama_pembeli', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->where('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('tanggal', '<=', $request->to);
        }

        $invoices = $query->paginate(10)->withQueryString();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $chartOfAccounts = $this->chartOfAccounts();

        return view('invoices.create', compact('chartOfAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => ['required', 'string', 'max:255', 'unique:invoices,nomor'],
            'tanggal' => ['required', 'date'],

            'signature_type' => ['required', 'in:qr,hand'],

            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_data' => ['nullable', 'string'],

            // QR signature
            'qr_image' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],


            'pejabat' => ['nullable', 'string', 'max:255'],
            'role_penandatangan' => ['nullable', 'string', 'max:255'],

            // Seller
            'npwp_penjual' => ['nullable', 'string', 'max:32'],
            'nama_penjual' => ['nullable', 'string', 'max:255'],
            'alamat_penjual' => ['nullable', 'string', 'max:255'],

            // Buyer
            'npwp_pembeli' => ['nullable', 'string', 'max:32'],
            'nama_pembeli' => ['nullable', 'string', 'max:255'],
            'alamat_pembeli' => ['nullable', 'string', 'max:255'],

            'currency' => ['nullable', 'string', 'max:3'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_produk' => ['required', 'string', 'max:255'],
            'items.*.chart_of_account_no_new' => ['nullable', 'string', 'max:6', 'exists:chart_of_accounts,account_no_new'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
            'items.*.diskon' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);




        // Signature type determines which fields are required:
        //   'qr'   → requires uploaded DJP QR image (Indonesian tax authority stamp)
        //   'hand' → requires base64-encoded hand-drawn signature data
        // These are mutually exclusive — only one signature method per invoice.
        if (($validated['signature_type'] ?? null) === 'qr') {
            $request->validate([
                'qr_image' => ['required', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            ]);
            $validated['signature_data'] = null;
        }

        if (($validated['signature_type'] ?? null) === 'hand') {
            $request->validate([
                'signature_data' => ['required', 'string'],
            ]);
            $validated['qr_image'] = null;
        }



        $total = 0.0;
        $itemsData = [];

        // Subtotal per item = (harga × qty) - diskon.
        // Floor at 0 prevents negative subtotals when discount exceeds item value.
        foreach ($validated['items'] as $item) {
            $qty = (int) $item['qty'];
            $harga = (float) $item['harga'];
            $diskon = isset($item['diskon']) ? (float) $item['diskon'] : 0.0;

            $subtotal = ($harga * $qty) - $diskon;
            if ($subtotal < 0) {
                $subtotal = 0;
            }

            $total += $subtotal;

            $itemsData[] = [
                'chart_of_account_no_new' => $item['chart_of_account_no_new'] ?? null,
                'nama_produk' => $item['nama_produk'],
                'qty' => $qty,
                'harga' => $harga,
                'diskon' => $diskon,
                'subtotal' => $subtotal,
            ];
        }

        $validated['total'] = $total;

        $payload = $this->buildQrPayload($validated);

        $invoiceData = [
            'nomor' => $validated['nomor'],
            'tanggal' => $validated['tanggal'],
            'pejabat' => $validated['pejabat'] ?? null,
            'role_penandatangan' => $validated['role_penandatangan'] ?? null,
            'signature_type' => $validated['signature_type'],
            'signature_name' => $validated['signature_name'] ?? null,
            'signature_data' => $validated['signature_data'] ?? null,

            'npwp_penjual' => $validated['npwp_penjual'] ?? null,

            'nama_penjual' => $validated['nama_penjual'] ?? null,
            'alamat_penjual' => $validated['alamat_penjual'] ?? null,
            'npwp_pembeli' => $validated['npwp_pembeli'] ?? null,
            'nama_pembeli' => $validated['nama_pembeli'] ?? null,
            'alamat_pembeli' => $validated['alamat_pembeli'] ?? null,
            'total' => $validated['total'],
            'currency' => $validated['currency'] ?? 'IDR',
            'qr_payload' => $payload,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ];

        // handle QR upload (DJP provided image)
        if ($request->hasFile('qr_image')) {
            $file = $request->file('qr_image');
            $mime = $file->getMimeType();
            $contents = file_get_contents($file->getRealPath());
            $dataUri = 'data:' . $mime . ';base64,' . base64_encode($contents);
            $invoiceData['qr_image'] = $dataUri;
        }

        $invoice = Invoice::create($invoiceData);

        foreach ($itemsData as $row) {
            $invoice->items()->create($row);
        }

        $journalService = new JournalService();
        $journalService->postInvoice($invoice);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'subject_type' => Invoice::class,
            'subject_id' => $invoice->id,
            'description' => "Membuat invoice {$invoice->nomor}",
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice tersimpan dan payload QR dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->loadMissing('items.chartOfAccount', 'creator');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->loadMissing('items.chartOfAccount');
        $chartOfAccounts = $this->chartOfAccounts();

        return view('invoices.edit', compact('invoice', 'chartOfAccounts'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'nomor' => ['required', 'string', 'max:255', 'unique:invoices,nomor,' . $invoice->id],
            'tanggal' => ['required', 'date'],
            'role_penandatangan' => ['nullable', 'string', 'max:255'],
            'pejabat' => ['nullable', 'string', 'max:255'],
            'signature_type' => ['required', 'in:qr,hand'],

            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_data' => ['nullable', 'string'],
            'qr_image' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],

            // Seller
            'npwp_penjual' => ['nullable', 'string', 'max:32'],
            'nama_penjual' => ['nullable', 'string', 'max:255'],
            'alamat_penjual' => ['nullable', 'string', 'max:255'],

            // Buyer
            'npwp_pembeli' => ['nullable', 'string', 'max:32'],
            'nama_pembeli' => ['nullable', 'string', 'max:255'],
            'alamat_pembeli' => ['nullable', 'string', 'max:255'],

            'currency' => ['nullable', 'string', 'max:3'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_produk' => ['required', 'string', 'max:255'],
            'items.*.chart_of_account_no_new' => ['nullable', 'string', 'max:6', 'exists:chart_of_accounts,account_no_new'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
            'items.*.diskon' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $total = 0.0;
        $itemsData = [];
        foreach ($validated['items'] as $item) {
            $qty = (int) $item['qty'];
            $harga = (float) $item['harga'];
            $diskon = isset($item['diskon']) ? (float) $item['diskon'] : 0.0;

            $subtotal = ($harga * $qty) - $diskon;
            if ($subtotal < 0) {
                $subtotal = 0;
            }

            $total += $subtotal;

            $itemsData[] = [
                'chart_of_account_no_new' => $item['chart_of_account_no_new'] ?? null,
                'nama_produk' => $item['nama_produk'],
                'qty' => $qty,
                'harga' => $harga,
                'diskon' => $diskon,
                'subtotal' => $subtotal,
            ];
        }

        $validated['total'] = $total;

        $payload = $this->buildQrPayload($validated);

        $invoiceData = [
            'nomor' => $validated['nomor'],
            'tanggal' => $validated['tanggal'],
            'pejabat' => $validated['pejabat'] ?? null,
            'role_penandatangan' => $validated['role_penandatangan'] ?? null,
            'signature_type' => $validated['signature_type'],
            'signature_name' => $validated['signature_name'] ?? null,
            'signature_data' => $validated['signature_data'] ?? null,
            'npwp_penjual' => $validated['npwp_penjual'] ?? null,


            'nama_penjual' => $validated['nama_penjual'] ?? null,
            'alamat_penjual' => $validated['alamat_penjual'] ?? null,
            'npwp_pembeli' => $validated['npwp_pembeli'] ?? null,
            'nama_pembeli' => $validated['nama_pembeli'] ?? null,
            'alamat_pembeli' => $validated['alamat_pembeli'] ?? null,
            'total' => $validated['total'],
            'currency' => $validated['currency'] ?? 'IDR',
            'qr_payload' => $payload,
            'notes' => $validated['notes'] ?? null,
        ];

        if ($request->hasFile('qr_image')) {
            $file = $request->file('qr_image');
            $mime = $file->getMimeType();
            $contents = file_get_contents($file->getRealPath());
            $dataUri = 'data:' . $mime . ';base64,' . base64_encode($contents);
            $invoiceData['qr_image'] = $dataUri;
        }

        $invoice->update($invoiceData);

        // Replace all items (simpler than diffing — items are lightweight)
        $invoice->items()->delete();
        foreach ($itemsData as $row) {
            $invoice->items()->create($row);
        }

        $journalService = new JournalService();
        $journalService->postInvoice($invoice);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Invoice::class,
            'subject_id' => $invoice->id,
            'description' => "Memperbarui invoice {$invoice->nomor}",
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice diperbarui dan payload QR diupdate.');
    }


    public function destroy(Invoice $invoice)
    {
        $journalService = new JournalService();
        $journalService->reverseInvoice($invoice);

        $nomor = $invoice->nomor;
        $invoice->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'subject_type' => Invoice::class,
            'subject_id' => null,
            'description' => "Menghapus invoice {$nomor}",
        ]);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->markAsPaid();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Invoice::class,
            'subject_id' => $invoice->id,
            'description' => "Menandai invoice {$invoice->nomor} sebagai lunas",
        ]);

        return back()->with('success', 'Invoice ditandai sebagai lunas.');
    }

    public function markUnpaid(Invoice $invoice)
    {
        $invoice->markAsUnpaid();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Invoice::class,
            'subject_id' => $invoice->id,
            'description' => "Menandai invoice {$invoice->nomor} sebagai belum lunas",
        ]);

        return back()->with('success', 'Invoice ditandai sebagai belum lunas.');
    }

    /**
     * Creates a copy of an invoice with a new number, today's date, and unpaid status.
     * Signature and QR data are cleared — the duplicated invoice needs fresh
     * signature approval. Journal entries are posted for the new invoice.
     */
    public function duplicate(Invoice $invoice)
    {
        $invoiceNumberService = new InvoiceNumberService();
        $newNomor = $invoiceNumberService->generate();

        $newInvoice = Invoice::create([
            'nomor' => $newNomor,
            'tanggal' => now(),
            'pejabat' => $invoice->pejabat,
            'role_penandatangan' => $invoice->role_penandatangan,
            'signature_type' => 'qr',
            'signature_name' => null,
            'signature_data' => null,
            'npwp_penjual' => $invoice->npwp_penjual,
            'nama_penjual' => $invoice->nama_penjual,
            'alamat_penjual' => $invoice->alamat_penjual,
            'npwp_pembeli' => $invoice->npwp_pembeli,
            'nama_pembeli' => $invoice->nama_pembeli,
            'alamat_pembeli' => $invoice->alamat_pembeli,
            'total' => $invoice->total,
            'currency' => $invoice->currency,
            'status' => 'unpaid',
            'notes' => $invoice->notes,
            'created_by' => auth()->id(),
        ]);

        foreach ($invoice->items as $item) {
            $newInvoice->items()->create([
                'nama_produk' => $item->nama_produk,
                'qty' => $item->qty,
                'harga' => $item->harga,
                'diskon' => $item->diskon,
                'subtotal' => $item->subtotal,
                'chart_of_account_no_new' => $item->chart_of_account_no_new,
            ]);
        }

        $journalService = new JournalService();
        $journalService->postInvoice($newInvoice);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'subject_type' => Invoice::class,
            'subject_id' => $newInvoice->id,
            'description' => " Menduplikasi invoice {$invoice->nomor} menjadi {$newNomor}",
        ]);

        return redirect()->route('invoices.edit', $newInvoice)
            ->with('success', "Invoice berhasil diduplikasi sebagai {$newNomor}.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'action' => ['required', 'in:paid,unpaid,delete'],
        ]);

        $invoices = Invoice::whereIn('id', $request->ids)->get();
        $count = $invoices->count();

        foreach ($invoices as $invoice) {
            if ($request->action === 'paid') {
                $invoice->markAsPaid();
            } elseif ($request->action === 'unpaid') {
                $invoice->markAsUnpaid();
            } elseif ($request->action === 'delete') {
                $journalService = new JournalService();
                $journalService->reverseInvoice($invoice);
                $invoice->delete();
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'bulk_' . $request->action,
            'description' => "Bulk {$request->action} untuk {$count} invoice",
        ]);

        return back()->with('success', "{$count} invoice berhasil diproses.");
    }

    /**
     * Returns the QR code image for an invoice.
     *
     * Priority order:
     *   1. If a DJP-provided QR image was uploaded, return it directly
     *      (base64-decoded from the stored data URI).
     *   2. If no image exists, generate a QR code that links to the PDF URL
     *      (legacy fallback for invoices created before QR upload was added).
     */
    public function qr(Invoice $invoice)
    {
        $payload = $invoice->qr_payload;
        if (!$payload) {
            // Regenerate payload if missing (handles legacy invoices)
            $payload = $this->buildQrPayload([
                'nomor' => $invoice->nomor,
                'tanggal' => $invoice->tanggal ? $invoice->tanggal->format('Y-m-d') : null,
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



        // Return uploaded DJP QR image if available
        if (!empty($invoice->qr_image)) {
            $data = $invoice->qr_image;
            if (str_starts_with($data, 'data:')) {
                [$meta, $b64] = explode(',', $data, 2);
                preg_match('/data:(.*);base64/', $meta, $m);
                $mime = $m[1] ?? 'image/png';
                $binary = base64_decode($b64);

                return response($binary, 200)
                    ->header('Content-Type', $mime);
            }
        }

        // Fallback: generate QR linking to the invoice PDF
        $fakturUrl = route('invoices.pdf', $invoice);

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


    /**
     * Builds the QR payload as base64-encoded JSON.
     * Stored in the database for quick retrieval; decoded back to JSON
     * when the QR image is rendered or the payload is inspected.
     */
    private function buildQrPayload(array $data): string
    {
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

    private function chartOfAccounts()
    {
        return ChartOfAccount::query()
            ->where(function ($query) {
                $query->whereNull('is_header')
                    ->orWhere('is_header', '!=', 'H');
            })
            ->orderBy('account_no_new')
            ->get();
    }
}
