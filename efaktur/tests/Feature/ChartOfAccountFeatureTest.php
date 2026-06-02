<?php

namespace Tests\Feature;

use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChartOfAccountFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_create_chart_of_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('chart-of-accounts.store'), [
            'account_no_new' => '9-9999',
            'account_no_old_1' => '9999',
            'account_no_old_2' => 99999,
            'account_name' => 'Test Account',
            'is_header' => '',
            'account_type' => 'Expense',
        ]);

        $response->assertRedirect(route('chart-of-accounts.index'));

        $this->assertDatabaseHas('chart_of_accounts', [
            'account_no_new' => '9-9999',
            'account_name' => 'Test Account',
        ]);
    }

    public function test_invoice_items_can_store_chart_of_account_reference(): void
    {
        ChartOfAccount::create([
            'account_no_new' => '4-4000',
            'account_no_old_1' => '4000',
            'account_no_old_2' => 40000,
            'account_name' => 'Sales Revenue',
            'is_header' => '',
            'account_type' => 'Income',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)->post(route('invoices.store'), [
            'nomor' => 'INV-COA-001',
            'tanggal' => '2026-06-02',
            'npwp_penjual' => '01.234.567.8-999.000',
            'nama_penjual' => 'PT Penjual Sejahtera',
            'alamat_penjual' => 'Jl. Penjual No. 1',
            'npwp_pembeli' => '09.876.543.2-111.000',
            'nama_pembeli' => 'PT Pembeli Makmur',
            'alamat_pembeli' => 'Jl. Pembeli No. 2',
            'currency' => 'IDR',
            'items' => [
                [
                    'nama_produk' => 'Produk COA',
                    'chart_of_account_no_new' => '4-4000',
                    'qty' => 1,
                    'harga' => 150000,
                    'diskon' => 0,
                ],
            ],
        ])->assertRedirect();

        $invoice = Invoice::with('items.chartOfAccount')->where('nomor', 'INV-COA-001')->firstOrFail();

        $this->assertSame('4-4000', $invoice->items->first()->chart_of_account_no_new);
        $this->assertSame('Sales Revenue', $invoice->items->first()->chartOfAccount?->account_name);
    }
}
