<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Insert some products
        DB::table('products')->insert([
            [
                'name' => 'Monitor',
                'uuid' => 'monitor-1',
                'description' => '144hz Monitor',
                'price' => '11000.00',
                'quantity' => '3',
                'enable' => true
            ],
            [
                'name' => 'Keyboard',
                'uuid' => 'keyboard-1',
                'description' => 'Mechanical Keyboard',
                'price' => '6000.00',
                'quantity' => '5',
                'enable' => true
            ],
            [
                'name' => 'Mouse',
                'uuid' => 'mouse-1',
                'description' => '1up to 16k dpi rgb mouse',
                'price' => '2500.00',
                'quantity' => '15',
                'enable' => true
            ],
            [
                'name' => 'Headset',
                'uuid' => 'headset-1',
                'description' => 'Headset with noise cancellation and RGB Lighting',
                'price' => '4500.00',
                'quantity' => '8',
                'enable' => true
            ],
            [
                'name' => 'Earphone',
                'uuid' => 'earphone-1',
                'description' => 'Earphone with noise cancellation',
                'price' => '1500.00',
                'quantity' => '10',
                'enable' => true
            ],
            [
                'name' => 'Mousepad',
                'uuid' => 'mousepad-1',
                'description' => 'S/M/L/XL/XXL size mousepad',
                'price' => '500.00',
                'quantity' => '20',
                'enable' => true
            ],
        ]);


        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => 'psswd'
            ]
        ]);
    }
}
