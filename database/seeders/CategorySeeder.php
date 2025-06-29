<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Electronics',
            'Accessories',
            'Home Appliances',
            'Wearables',
            'Gaming',
            'Furniture',
            'Home',
            'Personal Care',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}