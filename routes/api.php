<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/**
 * Rutas para la gestión del catálogo de productos.
 */
Route::prefix('products')->group(function () {
    /**
     * GET /api/products
     * Listar todos los productos del catálogo.
     */
    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    /**
     * POST /api/products
     * Crear un nuevo producto.
     */
    Route::post('/', [ProductController::class, 'store'])->name('products.store');

    /**
     * GET /api/products/{id}
     * Mostrar un producto específico.
     */
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');

    /**
     * PUT /api/products/{id}
     * Actualizar un producto existente.
     */
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');

    /**
     * DELETE /api/products/{id}
     * Eliminar un producto del catálogo.
     */
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
