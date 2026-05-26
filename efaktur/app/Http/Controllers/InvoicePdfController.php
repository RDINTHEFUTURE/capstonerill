<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;

class InvoicePdfController extends Controller
{
    public function show(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('faktur-{$invoice->nomor}.pdf');
    }
}

