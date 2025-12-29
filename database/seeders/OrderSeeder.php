<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $products = Product::where('is_active', true)->take(5)->get();

        if (!$customer || $products->isEmpty()) {
            $this->command->warn('No customer or products found. Skipping order seeder.');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $cities = ['Manila', 'Quezon City', 'Makati', 'Cebu City', 'Davao City'];
        $provinces = ['Metro Manila', 'Metro Manila', 'Metro Manila', 'Cebu', 'Davao del Sur'];

        // CREATE 5 SAMPLE ORDERS
        for ($i = 1; $i <= 5; $i++) {
            $orderProducts = $products->random(rand(1, 3));
            $total = 0;
            $itemsData = [];

            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
                $total += $product->price * $qty;
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => $customer->id,
                'status' => $statuses[array_rand($statuses)],
                'total' => $total,
                'currency' => 'PHP',
                'placed_at' => now()->subDays(rand(0, 14))->subHours(rand(1, 12)),
            ]);

            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            $cityIndex = array_rand($cities);
            Address::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'full_name' => 'Juan Dela Cruz',
                'phone' => '0917' . rand(1000000, 9999999),
                'line1' => rand(100, 999) . ' Sample Street',
                'line2' => 'Barangay ' . rand(1, 100),
                'city' => $cities[$cityIndex],
                'province' => $provinces[$cityIndex],
                'postal_code' => rand(1000, 9999),
            ]);
        }

        $this->command->info('Created 5 sample orders.');
    }
}
