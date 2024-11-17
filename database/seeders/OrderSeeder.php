<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Ejecutar las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Crear algunas órdenes de ejemplo
        $user = User::first(); // Usamos el primer usuario para las órdenes, o crea nuevos usuarios si es necesario
        
        // Crear la primera orden
        $order1 = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $this->calculateTotal(['Balón de Fútbol', 'Raqueta de Tenis']), // Calcular total
        ]);

        $order1->products()->attach([
            Product::where('name', 'Balón de Fútbol')->first()->id => [
                'quantity' => 2,
                'price' => Product::where('name', 'Balón de Fútbol')->first()->price, // Agregar precio
            ],
            Product::where('name', 'Raqueta de Tenis')->first()->id => [
                'quantity' => 1,
                'price' => Product::where('name', 'Raqueta de Tenis')->first()->price, // Agregar precio
            ],
        ]);

        // Crear la segunda orden
        $order2 = Order::create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => $this->calculateTotal(['Zapatillas de Running', 'Pesas de Mano']), // Calcular total
        ]);

        $order2->products()->attach([
            Product::where('name', 'Zapatillas de Running')->first()->id => [
                'quantity' => 1,
                'price' => Product::where('name', 'Zapatillas de Running')->first()->price, // Agregar precio
            ],
            Product::where('name', 'Pesas de Mano')->first()->id => [
                'quantity' => 3,
                'price' => Product::where('name', 'Pesas de Mano')->first()->price, // Agregar precio
            ],
        ]);
    }

    /**
     * Calcular el total de la orden.
     *
     * @param array $productNames
     * @return float
     */
    private function calculateTotal(array $productNames)
    {
        $total = 0;

        foreach ($productNames as $productName) {
            $product = Product::where('name', $productName)->first();
            $total += $product->price; // Se suma el precio de cada producto
        }

        return $total;
    }
}
