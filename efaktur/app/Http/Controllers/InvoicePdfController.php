<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;


class InvoicePdfController extends Controller
{
    public function show(Invoice $invoice)
    {
        // QR akan dirender sebagai data URI base64 supaya dompdf tidak melakukan request HTTP ke endpoint QR.
        $fakturUrl = route('invoices.pdf', $invoice);

        // Eager load items agar tersedia di view.
        $invoice->loadMissing('items');

        $qrCode = new QrCode(
            data: $fakturUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
            size: 280,
            margin: 10,
        );


        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrBase64 = base64_encode($result->getString());

        $pdf = app('dompdf.wrapper')
            ->loadView('invoices.cetakfaktur', [
                'invoice' => $invoice,
                'qrBase64' => $qrBase64,
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('faktur-' . $invoice->nomor . '.pdf');
    }
}






