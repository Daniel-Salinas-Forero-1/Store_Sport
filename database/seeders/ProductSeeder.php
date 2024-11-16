<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Ejecutar las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Crear productos de ejemplo
        Product::create([
            'name' => 'Balón de Fútbol',
            'description' => 'Balón de fútbol de alta calidad para entrenamiento y juegos.',
            'price' => 29.99,
            'stock' => 100,
            'category' => 'Fútbol',
        ]);

        Product::create([
            'name' => 'Raqueta de Tenis',
            'description' => 'Raqueta ligera y duradera, ideal para jugadores de todos los niveles.',
            'price' => 79.50,
            'stock' => 50,
            'category' => 'Tenis',
        ]);

        Product::create([
            'name' => 'Zapatillas de Running',
            'description' => 'Zapatillas cómodas y resistentes para carreras largas y entrenamiento.',
            'price' => 120.00,
            'stock' => 200,
            'category' => 'Running',
        ]);

        Product::create([
            'name' => 'Pesas de Mano',
            'description' => 'Pesas de mano para entrenamiento en casa o gimnasio.',
            'price' => 15.99,
            'stock' => 150,
            'category' => 'Gimnasio',
        ]);
    }
}
