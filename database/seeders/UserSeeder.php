<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Warehouse User',
            'email' => 'warehouse@example.com',
            'password' => bcrypt('password'),
            'role' => 'warehouse'
        ]);
        User::create([
            'name' => 'Delivery User',
            'email' => 'delivery@example.com',
            'password' => bcrypt('password'),
            'role' => 'delivery'
        ]);
    }
}