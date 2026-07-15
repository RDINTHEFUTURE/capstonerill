<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoicePdfController extends Controller
{
    public function preview(Invoice $invoice)
    {
        if (!$invoice->isApproved()) {
            abort(403, 'Hanya invoice yang sudah disetujui yang bisa di-preview.');
        }

        $invoice->loadMissing('items.chartOfAccount');

        return view('invoices.preview', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Generates a downloadable PDF of the Indonesian tax invoice (Faktur Pajak).
     * isRemoteEnabled allows DomPDF to load external CSS/fonts from CDNs
     * used in the cetakfaktur template.
     */
    public function show(Invoice $invoice)
    {
        if (!$invoice->isApproved()) {
            abort(403, 'Hanya invoice yang sudah disetujui yang bisa diunduh.');
        }

        $invoice->loadMissing('items.chartOfAccount');

        $pdf = app('dompdf.wrapper')
            ->loadView('invoices.cetakfaktur', [
                'invoice' => $invoice,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('faktur-' . $invoice->nomor . '.pdf');
    }
}
