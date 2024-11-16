<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // clave primaria
            $table->string('name'); // nombre del producto
            $table->text('description'); // descripción del producto
            $table->decimal('price', 8, 2); // precio del producto (máximo 999,999.99)
            $table->integer('stock'); // cantidad en stock
            $table->string('category'); // categoría del producto
            $table->timestamps(); // timestamps de Laravel
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
