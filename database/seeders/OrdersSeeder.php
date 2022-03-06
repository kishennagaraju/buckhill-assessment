<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatuses;
use App\Models\Payments;
use App\Models\Products;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Products::query()->first();
        $user = User::factory()->count(1)->create();
        $orderStatus = OrderStatuses::query()->first();
        $payment = Payments::query()->first();
        $faker = Factory::create();

        DB::table('orders')->insert([
            'uuid' => $faker->uuid(),
            'user_id' => '2',
            'order_status_id' => '1',
            'payment_id' => '1',
            'products' => json_encode([[
                'product' => $product->uuid,
                'quantity' => 2
            ]]),
            'address' => json_encode([
                'billing' => $faker->address(),
                'shipping' => $faker->address(),
            ]),
            'delivery_fee' => '20.00',
            'amount' => ($product->price * 2),
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }
}
