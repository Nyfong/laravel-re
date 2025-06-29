<?php
namespace Database\Seeders;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'iPhone 15 Pro Max', 'category' => 'Electronics', 'stock' => 120],
            ['name' => 'Samsung Galaxy S24 Ultra', 'category' => 'Electronics', 'stock' => 85],
            ['name' => 'MacBook Air M3', 'category' => 'Electronics', 'stock' => 45],
            ['name' => 'Dell XPS 13 Plus', 'category' => 'Electronics', 'stock' => 34],
            ['name' => 'Logitech MX Master 4', 'category' => 'Accessories', 'stock' => 210],
            ['name' => 'Sony WH-1000XM6', 'category' => 'Electronics', 'stock' => 67],
            ['name' => 'Apple AirPods Pro 3', 'category' => 'Electronics', 'stock' => 95],
            ['name' => 'Kindle Paperwhite 11th Gen', 'category' => 'Electronics', 'stock' => 140],
            ['name' => 'Dyson V15 Detect Absolute', 'category' => 'Home Appliances', 'stock' => 25],
            ['name' => 'Philips Hue Starter Kit', 'category' => 'Home Appliances', 'stock' => 60],
            ['name' => 'Fitbit Charge 6', 'category' => 'Wearables', 'stock' => 88],
            ['name' => 'Garmin Fenix 8X', 'category' => 'Wearables', 'stock' => 47],
            ['name' => 'Xiaomi Smart Band 8', 'category' => 'Wearables', 'stock' => 130],
            ['name' => 'ASUS ROG Strix Gaming Laptop', 'category' => 'Electronics', 'stock' => 22],
            ['name' => 'Sony PlayStation 5 Slim', 'category' => 'Gaming', 'stock' => 58],
            ['name' => 'Xbox Series X 1TB', 'category' => 'Gaming', 'stock' => 40],
            ['name' => 'Nintendo Switch OLED', 'category' => 'Gaming', 'stock' => 75],
            ['name' => 'Bose SoundLink Flex', 'category' => 'Electronics', 'stock' => 110],
            ['name' => 'GoPro Hero 12 Black', 'category' => 'Electronics', 'stock' => 28],
            ['name' => 'Canon EOS R8', 'category' => 'Electronics', 'stock' => 19],
            ['name' => 'Instant Pot Duo 7-in-1', 'category' => 'Home Appliances', 'stock' => 63],
            ['name' => 'Nespresso Vertuo Next', 'category' => 'Home Appliances', 'stock' => 53],
            ['name' => 'JBL Flip 7 Bluetooth Speaker', 'category' => 'Electronics', 'stock' => 95],
            ['name' => 'Samsung 55" QLED TV', 'category' => 'Electronics', 'stock' => 15],
            ['name' => 'LG 65" OLED evo TV', 'category' => 'Electronics', 'stock' => 9],
            ['name' => 'Apple iPad Pro 12.9" 2024', 'category' => 'Electronics', 'stock' => 50],
            ['name' => 'Lenovo Tab P12 Pro', 'category' => 'Electronics', 'stock' => 60],
            ['name' => 'Anker PowerCore 20,000mAh', 'category' => 'Accessories', 'stock' => 240],
            ['name' => 'Belkin 3-in-1 MagSafe Charger', 'category' => 'Accessories', 'stock' => 150],
            ['name' => 'Tile Mate Tracker (2024)', 'category' => 'Accessories', 'stock' => 90],
            ['name' => 'Sony ZV-E1 Vlogging Camera', 'category' => 'Electronics', 'stock' => 18],
            ['name' => 'Razer DeathAdder V3 Pro', 'category' => 'Gaming', 'stock' => 80],
            ['name' => 'SteelSeries Apex Pro TKL', 'category' => 'Gaming', 'stock' => 65],
            ['name' => 'HyperX Cloud III Headset', 'category' => 'Gaming', 'stock' => 77],
            ['name' => 'IKEA Bekant Desk', 'category' => 'Furniture', 'stock' => 25],
            ['name' => 'Secretlab Titan Evo Chair', 'category' => 'Furniture', 'stock' => 40],
            ['name' => 'Casper Original Mattress', 'category' => 'Furniture', 'stock' => 30],
            ['name' => 'Samsung Galaxy Watch 6', 'category' => 'Wearables', 'stock' => 55],
            ['name' => 'Apple Watch Ultra 2', 'category' => 'Wearables', 'stock' => 35],
            ['name' => 'Braun Series 9 Pro Shaver', 'category' => 'Personal Care', 'stock' => 70],
            ['name' => 'Oral-B iO Series 10', 'category' => 'Personal Care', 'stock' => 100],
            ['name' => 'Thermos Stainless King 1.2L', 'category' => 'Home', 'stock' => 120],
            ['name' => 'NutriBullet Pro 1000W', 'category' => 'Home Appliances', 'stock' => 52],
            ['name' => 'HP Envy Inspire 7955e', 'category' => 'Electronics', 'stock' => 42],
            ['name' => 'Logitech StreamCam Plus', 'category' => 'Accessories', 'stock' => 66],
            ['name' => 'DJI Mini 4 Pro Drone', 'category' => 'Electronics', 'stock' => 13],
            ['name' => 'Samsung T7 Shield 2TB SSD', 'category' => 'Accessories', 'stock' => 77],
            ['name' => 'Sandisk Extreme Pro 128GB', 'category' => 'Accessories', 'stock' => 300],
            ['name' => 'LIFX Smart Light Strip', 'category' => 'Home Appliances', 'stock' => 85],
            ['name' => 'Ember Smart Mug 2', 'category' => 'Home Appliances', 'stock' => 72],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            Product::create([
                'name' => $productData['name'],
                'category_id' => $category->id,
                'stock' => $productData['stock'],
                'low_stock_threshold' => 20, // Default threshold
                'last_import_date' => now()->toDateString(), // June 29, 2025
            ]);
        }
    }
}