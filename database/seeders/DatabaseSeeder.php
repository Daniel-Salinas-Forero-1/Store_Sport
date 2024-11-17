<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Llamar a los seeders
        $this->call([
            ProductSeeder::class,  // Seeder de productos
            OrderSeeder::class,    // Seeder de órdenes
        ]);
    }
}
