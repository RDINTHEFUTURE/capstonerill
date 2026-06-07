<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InvoiceFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_create_invoice_with_seller_items_and_computed_total(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('invoices.store'), [
            'nomor' => 'INV-001',
            'tanggal' => '2026-06-02',
            'npwp_penjual' => '01.234.567.8-999.000',
            'nama_penjual' => 'PT Penjual Sejahtera',
            'alamat_penjual' => 'Jl. Penjual No. 1',
            'npwp_pembeli' => '09.876.543.2-111.000',
            'nama_pembeli' => 'PT Pembeli Makmur',
            'alamat_pembeli' => 'Jl. Pembeli No. 2',
'role_penandatangan' => 'Admin Supplier',
            'signature_type' => 'qr',
            'signature_name' => 'Budi Santoso',
            'qr_image' => null,
            'currency' => 'IDR',


            'items' => [
                [
                    'nama_produk' => 'Produk A',
                    'qty' => 2,
                    'harga' => 100000,
                    'diskon' => 10000,
                ],
                [
                    'nama_produk' => 'Produk B',
                    'qty' => 1,
                    'harga' => 50000,
                    'diskon' => 0,
                ],
            ],
        ]);

        if ($response->getStatusCode() === 302 && str_contains((string)$response->headers->get('Location'), 'login')) {
            // Session/auth redirect prevents invoice creation; fail with response for debugging.
            $this->fail('Unexpected redirect to login during invoice creation. Status: ' . $response->getStatusCode());
        }

        $created = Invoice::where('nomor', 'INV-001')->first();
        if (! $created) {
            $this->fail('Invoice not created. Response status: '.$response->getStatusCode().'. Location: '.($response->headers->get('Location') ?? '-').'. Body: '.substr((string)$response->getContent(),0,400));
        }

        $invoice = Invoice::with('items')->where('nomor', 'INV-001')->firstOrFail();

        if (! $response->isRedirect()) {

            $this->fail('Invoice create did not redirect as expected. Status: '.$response->getStatusCode().' Body: '.substr((string)$response->getContent(),0,300));
        }

        $response->assertRedirect(route('invoices.show', $invoice));

        $this->assertSame('PT Penjual Sejahtera', $invoice->nama_penjual);
        $this->assertSame('01.234.567.8-999.000', $invoice->npwp_penjual);
        $this->assertEquals('240000.00', $invoice->total);
        $this->assertCount(2, $invoice->items);
        $this->assertNotEmpty($invoice->qr_payload);

        $payload = json_decode(base64_decode($invoice->qr_payload), true);

        $this->assertSame('INV-001', $payload['nomor']);
        $this->assertSame('PT Penjual Sejahtera', $payload['nama_penjual']);
        $this->assertEquals(240000.0, $payload['total']);
    }

    public function test_invoice_qr_route_returns_png(): void
    {
        $user = User::factory()->create();
        $invoice = $this->createInvoice();

        $response = $this->actingAs($user)->get(route('invoices.qr', $invoice));

        $response->assertOk();
        $this->assertSame('image/png', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith("\x89PNG", $response->getContent());
    }

    public function test_invoice_pdf_route_returns_download(): void
    {
        $user = User::factory()->create();
        $invoice = $this->createInvoice([
            'nomor' => 'INV-PDF-001',
        ]);

        $invoice->items()->create([
            'nama_produk' => 'Produk PDF',
            'qty' => 1,
            'harga' => 100000,
            'diskon' => 0,
            'subtotal' => 100000,
        ]);

        $response = $this->actingAs($user)->get(route('invoices.pdf', $invoice));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertDownload('faktur-INV-PDF-001.pdf');
    }

    private function createInvoice(array $attributes = []): Invoice
    {
        return Invoice::create(array_merge([
            'nomor' => 'INV-QR-001',
            'tanggal' => '2026-06-02',
            'npwp_penjual' => '01.234.567.8-999.000',
            'nama_penjual' => 'PT Penjual Sejahtera',
            'alamat_penjual' => 'Jl. Penjual No. 1',
            'npwp_pembeli' => '09.876.543.2-111.000',
            'nama_pembeli' => 'PT Pembeli Makmur',
            'alamat_pembeli' => 'Jl. Pembeli No. 2',
            'total' => 100000,
            'currency' => 'IDR',
            'qr_payload' => base64_encode(json_encode([
                'app' => 'efaktur-laravel',
                'nomor' => 'INV-QR-001',
                'tanggal' => '2026-06-02',
                'total' => 100000.0,
                'currency' => 'IDR',
            ])),
        ], $attributes));
    }
}
