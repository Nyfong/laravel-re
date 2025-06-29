<?php
namespace Database\Seeders;
use App\Models\Product;
use App\Models\StockImport;
use Illuminate\Database\Seeder;

class StockImportSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'iPhone 15 Pro Max', 'stock' => 120],
            ['name' => 'Samsung Galaxy S24 Ultra', 'stock' => 85],
            ['name' => 'MacBook Air M3', 'stock' => 45],
            ['name' => 'Dell XPS 13 Plus', 'stock' => 34],
            ['name' => 'Logitech MX Master 4', 'stock' => 210],
            ['name' => 'Sony WH-1000XM6', 'stock' => 67],
            ['name' => 'Apple AirPods Pro 3', 'stock' => 95],
            ['name' => 'Kindle Paperwhite 11th Gen', 'stock' => 140],
            ['name' => 'Dyson V15 Detect Absolute', 'stock' => 25],
            ['name' => 'Philips Hue Starter Kit', 'stock' => 60],
            ['name' => 'Fitbit Charge 6', 'stock' => 88],
            ['name' => 'Garmin Fenix 8X', 'stock' => 47],
            ['name' => 'Xiaomi Smart Band 8', 'stock' => 130],
            ['name' => 'ASUS ROG Strix Gaming Laptop', 'stock' => 22],
            ['name' => 'Sony PlayStation 5 Slim', 'stock' => 58],
            ['name' => 'Xbox Series X 1TB', 'stock' => 40],
            ['name' => 'Nintendo Switch OLED', 'stock' => 75],
            ['name' => 'Bose SoundLink Flex', 'stock' => 110],
            ['name' => 'GoPro Hero 12 Black', 'stock' => 28],
            ['name' => 'Canon EOS R8', 'stock' => 19],
            ['name' => 'Instant Pot Duo 7-in-1', 'stock' => 63],
            ['name' => 'Nespresso Vertuo Next', 'stock' => 53],
            ['name' => 'JBL Flip 7 Bluetooth Speaker', 'stock' => 95],
            ['name' => 'Samsung 55" QLED TV', 'stock' => 15],
            ['name' => 'LG 65" OLED evo TV', 'stock' => 9],
            ['name' => 'Apple iPad Pro 12.9" 2024', 'stock' => 50],
            ['name' => 'Lenovo Tab P12 Pro', 'stock' => 60],
            ['name' => 'Anker PowerCore 20,000mAh', 'stock' => 240],
            ['name' => 'Belkin 3-in-1 MagSafe Charger', 'stock' => 150],
            ['name' => 'Tile Mate Tracker (2024)', 'stock' => 90],
            ['name' => 'Sony ZV-E1 Vlogging Camera', 'stock' => 18],
            ['name' => 'Razer DeathAdder V3 Pro', 'stock' => 80],
            ['name' => 'SteelSeries Apex Pro TKL', 'stock' => 65],
            ['name' => 'HyperX Cloud III Headset', 'stock' => 77],
            ['name' => 'IKEA Bekant Desk', 'stock' => 25],
            ['name' => 'Secretlab Titan Evo Chair', 'stock' => 40],
            ['name' => 'Casper Original Mattress', 'stock' => 30],
            ['name' => 'Samsung Galaxy Watch 6', 'stock' => 55],
            ['name' => 'Apple Watch Ultra 2', 'stock' => 55],
            ['name' => 'Braun Series 9 Pro Shaver', 'stock' => 70],
            ['name' => 'Oral-B iO Series 10', 'stock' => 100],
            ['name' => 'Thermos Stainless King 1.2L', 'stock' => 120],
            ['name' => 'NutriBullet Pro 1000W', 'stock' => 52],
            ['name' => 'HP Envy Inspire 7955e', 'stock' => 42],
            ['name' => 'Logitech StreamCam Plus', 'stock' => 66],
            ['name' => 'DJI Mini 4 Pro Drone', 'stock' => 13],
            ['name' => 'Samsung T7 Shield 2TB SSD', 'stock' => 77],
            ['name' => 'Sandisk Extreme Pro 128GB', 'stock' => 300],
            ['name' => 'LIFX Smart Light Strip', 'stock' => 85],
            ['name' => 'Ember Smart Mug 2', 'stock' => 72],
        ];

        foreach ($products as $productData) {
            $product = Product::where('name', $productData['name'])->first();
            if ($product) {
                StockImport::create([
                    'product_id' => $product->id,
                    'quantity' => $productData['stock'],
                    'import_date' => now()->toDateString(), // June 29, 2025
                ]);
            }
        }
    }
}