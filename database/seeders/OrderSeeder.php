<?php
namespace Database\Seeders;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 100; $i++) {
            $product = Product::inRandomOrder()->first();
            Order::create([
                'product_id' => $product->id,
                'quantity' => rand(1, 5),
                'order_date' => Carbon::now()->subDays(rand(1, 90)),
            ]);
        }
    }
}