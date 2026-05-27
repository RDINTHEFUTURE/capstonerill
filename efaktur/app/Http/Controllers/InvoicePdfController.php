<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\DomPDF;


class InvoicePdfController extends Controller
{
    public function show(Invoice $invoice)
    {
        $pdf = app('dompdf.wrapper')
            ->loadView('invoices.cetakfaktur', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);




        return $pdf->download('faktur-' . $invoice->nomor . '.pdf');
    }
}




