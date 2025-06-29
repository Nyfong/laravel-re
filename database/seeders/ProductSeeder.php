<?php
namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'name' => $faker->word . ' Product ' . $i,
                'category_id' => rand(1, 5),
                'stock' => rand(5, 100),
                'low_stock_threshold' => rand(10, 20),
                'last_import_date' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
    }
}