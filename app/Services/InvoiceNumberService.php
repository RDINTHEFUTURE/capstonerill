<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    /**
     * Generates a unique sequential invoice number in the format INV-YYYY-NNNN.
     *
     * Uses lockForUpdate() to prevent race conditions: without the lock,
     * two concurrent requests could read the same counter value and generate
     * duplicate invoice numbers, violating the unique constraint.
     */
    public function generate(): string
    {
        $year = date('Y');

        $counter = DB::transaction(function () use ($year) {
            $counter = DB::table('invoice_counter')
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                DB::table('invoice_counter')->insert([
                    'year' => $year,
                    'last_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return (object) ['year' => $year, 'last_number' => 1];
            }

            DB::table('invoice_counter')
                ->where('year', $year)
                ->increment('last_number');

            $counter->last_number++;
            return $counter;
        });

        return 'INV-' . $year . '-' . str_pad($counter->last_number, 4, '0', STR_PAD_LEFT);
    }
}
