<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BulkTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;
        
        $items = Item::all();
        if ($items->count() < 1) return;

        $faker = Faker::create('id_ID');

        // Create 20 Customers first
        for ($i = 0; $i < 20; $i++) {
            DB::table('customers')->insert([
                'name' => $faker->company,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }
        $customers = Customer::all();

        // Generate 25 records for each transaction type
        for ($i = 0; $i < 25; $i++) {
            $item = $items->random();
            $date = Carbon::now()->subDays(rand(1, 90));
            $isReturned = rand(0, 1);

            // 1. Lending
            $lendingId = DB::table('lendings')->insertGetId([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'name' => $faker->name,
                'total' => rand(1, 3),
                'notes' => $faker->sentence,
                'return_date' => $isReturned ? $date->copy()->addDays(rand(1, 5)) : null,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // 2. Item Return
            if ($isReturned) {
                DB::table('item_returns')->insert([
                    'lending_id' => $lendingId,
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'quantity' => rand(1, 3),
                    'returned_at' => $date->copy()->addDays(rand(1, 5)),
                    'notes' => $faker->sentence,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            // 3. Stock In
            $stockInDate = Carbon::now()->subDays(rand(1, 90));
            DB::table('stock_ins')->insert([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'quantity' => rand(5, 50),
                'received_at' => $stockInDate,
                'notes' => 'Restock ' . $faker->word,
                'created_at' => $stockInDate,
                'updated_at' => $stockInDate,
            ]);

            // 4. Stock Opname
            $opnameDate = Carbon::now()->subDays(rand(1, 90));
            DB::table('stock_opnames')->insert([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'system_stock' => rand(10, 50),
                'actual_stock' => rand(8, 52),
                'notes' => $faker->sentence,
                'opname_date' => $opnameDate,
                'created_at' => $opnameDate,
                'updated_at' => $opnameDate,
            ]);

            // 5. Maintenance
            $mainDate = Carbon::now()->subDays(rand(1, 90));
            DB::table('maintenances')->insert([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'title' => 'Service ' . $faker->word,
                'status' => rand(0, 1) ? 'completed' : 'pending',
                'cost' => rand(50, 500) * 1000,
                'notes' => $faker->sentence,
                'started_at' => $mainDate,
                'completed_at' => rand(0, 1) ? $mainDate->copy()->addDays(rand(1, 10)) : null,
                'created_at' => $mainDate,
                'updated_at' => $mainDate,
            ]);

            // 6. Damage
            $dmgDate = Carbon::now()->subDays(rand(1, 90));
            DB::table('damages')->insert([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'type' => rand(0, 1) ? 'ringan' : 'berat',
                'quantity' => rand(1, 3),
                'notes' => $faker->sentence,
                'reported_at' => $dmgDate,
                'created_at' => $dmgDate,
                'updated_at' => $dmgDate,
            ]);

            // 7. Order
            if ($customers->count() > 0) {
                $cust = $customers->random();
                $orderDate = Carbon::now()->subDays(rand(1, 90));
                $amount = rand(500, 5000) * 1000;
                $orderId = DB::table('orders')->insertGetId([
                    'order_number' => 'ORD-' . $orderDate->format('YmdHis') . rand(10, 99),
                    'customer_id' => $cust->id,
                    'customer_name' => $cust->name,
                    'customer_phone' => $cust->phone,
                    'total_amount' => $amount,
                    'status' => ['Menunggu Pembayaran', 'Pesanan Diproses', 'Pesanan Dikirim', 'Pesanan Selesai', 'Dibatalkan'][rand(0, 4)],
                    'payment_method' => ['cash', 'transfer', 'credit'][rand(0, 2)],
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'item_id' => $item->id,
                    'quantity' => rand(1, 5),
                    'price' => $amount,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }

            // 8. Stock History
            DB::table('stock_histories')->insert([
                'item_id' => $item->id,
                'user_id' => $user->id,
                'type' => ['stock_in', 'stock_opname', 'order', 'damage'][rand(0, 3)],
                'quantity' => rand(-5, 50),
                'notes' => $faker->sentence,
                'transaction_at' => Carbon::now()->subDays(rand(1, 90)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
