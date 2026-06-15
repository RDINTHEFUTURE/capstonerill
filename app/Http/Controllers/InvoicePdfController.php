<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
// QR generation is disabled for now (DJP-provided QR). Removed Endroid imports.


class InvoicePdfController extends Controller
{
    public function preview(Invoice $invoice)
    {
        $invoice->loadMissing('items.chartOfAccount');

        return view('invoices.preview', [
            'invoice' => $invoice,
        ]);
    }

    public function show(Invoice $invoice)
    {
        // Eager load items agar tersedia di view.
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





