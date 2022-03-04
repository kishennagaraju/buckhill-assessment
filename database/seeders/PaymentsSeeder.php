<?php

namespace Database\Seeders;

use App\Models\Payments;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payments::factory()->count(3)->creditCard()->create();
        Payments::factory()->count(3)->cashOnDelivery()->create();
        Payments::factory()->count(3)->bankTransfer()->create();
    }
}
