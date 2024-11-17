<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/**
 * --------------------------------------------------------------------------
 * Routes for Product Catalog Service
 * --------------------------------------------------------------------------
 *
 * Estas rutas manejan las operaciones de CRUD relacionadas con el catálogo de productos
 * de la tienda deportiva. Cada ruta incluye su correspondiente acción
 * en el controlador y está documentada con la información necesaria
 * para su uso y funcionamiento.
 */
Route::prefix('products')->group(function () {

    /**
     * @route   GET /api/products
     * @name    products.index
     * @desc    Obtiene una lista de todos los productos disponibles en el catálogo.
     * @acces   Público (dependiendo de los requisitos de autenticación).
     * @returns JSON con los detalles de los productos.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: lista de productos.
     * @errors  500: Error al recuperar los productos.
     */
    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    /**
     * @route   POST /api/products
     * @name    products.store
     * @desc    Crea un nuevo producto en el catálogo.
     * @acces   Privado (requiere autenticación y permisos adecuados).
     * @body    JSON con los datos del producto a crear.
     *          - name: nombre del producto (obligatorio).
     *          - price: precio del producto (obligatorio).
     *          - description: descripción del producto (opcional).
     * @returns JSON con el producto creado.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: detalles del producto creado.
     * @errors  400: Datos de entrada no válidos.
     *          500: Error al crear el producto.
     */
    Route::post('/', [ProductController::class, 'store'])->name('products.store');

    /**
     * @route   GET /api/products/{id}
     * @name    products.show
     * @desc    Muestra los detalles de un producto específico.
     * @acces   Público (dependiendo de los requisitos de autenticación).
     * @param   id: ID del producto a consultar.
     * @returns JSON con los detalles del producto.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: detalles del producto solicitado.
     * @errors  404: Producto no encontrado.
     *          500: Error al recuperar el producto.
     */
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');

    /**
     * @route   PUT /api/products/{id}
     * @name    products.update
     * @desc    Actualiza un producto existente en el catálogo.
     * @acces   Privado (requiere autenticación y permisos adecuados).
     * @param   id: ID del producto a actualizar.
     * @body    JSON con los datos del producto a actualizar.
     *          - name: nombre del producto (opcional).
     *          - price: precio del producto (opcional).
     *          - description: descripción del producto (opcional).
     * @returns JSON con el producto actualizado.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: detalles del producto actualizado.
     * @errors  400: Datos de entrada no válidos.
     *          404: Producto no encontrado.
     *          500: Error al actualizar el producto.
     */
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');

    /**
     * @route   DELETE /api/products/{id}
     * @name    products.destroy
     * @desc    Elimina un producto del catálogo.
     * @acces   Privado (requiere autenticación y permisos adecuados).
     * @param   id: ID del producto a eliminar.
     * @returns JSON con el resultado de la operación.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     * @errors  404: Producto no encontrado.
     *          500: Error al eliminar el producto.
     */
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');





    /**
     * --------------------------------------------------------------------------
     * Routes for Order Management Service
     * --------------------------------------------------------------------------
     *
     * Estas rutas manejan las operaciones de CRUD relacionadas con las órdenes
     * de la tienda deportiva. Cada ruta incluye su correspondiente acción
     * en el controlador y está documentada con la información necesaria
     * para su uso y funcionamiento.
     *
     */

    // Listar todas las órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    /**
     * @route   GET /api/orders
    * @name    orders.index
    * @desc    Obtiene una lista de todas las órdenes existentes en el sistema, junto
    *          con sus productos asociados.
    * @acces  Público (dependiendo de los requisitos de autenticación).
    * @returns JSON con los detalles de las órdenes.
    *          - success: indica si la operación fue exitosa.
    *          - message: mensaje descriptivo del resultado.
    *          - data: lista de órdenes con información asociada.
    * @errors  500: Error al recuperar las órdenes.
    */
    
    // Crear una nueva orden
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    /**
     * @route   POST /api/orders
    * @name    orders.store
    * @desc    Crea una nueva orden en el sistema con los productos seleccionados
    *          y calcula el total basado en los precios de los productos.
    * @acces  Público (dependiendo de los requisitos de autenticación).
    * @body    - user_id (int): ID del usuario que realiza la orden.
    *          - products (array): Lista de productos en la orden.
    *            - id (int): ID del producto.
    *            - quantity (int): Cantidad de ese producto.
    * @returns JSON con los detalles de la orden creada.
    *          - success: indica si la operación fue exitosa.
    *          - message: mensaje descriptivo del resultado.
    *          - data: detalles de la orden recién creada.
    * @errors  400: Validación fallida en los campos.
    *          500: Error al crear la orden.
    */
    


    // Obtener los detalles de una orden específica
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    /**
     * @route   GET /api/orders/{id}
     * @name    orders.show
     * @desc    Obtiene los detalles de una orden específica.
     * @acces  Público (o autenticado según los requisitos).
     * @param   {int} id - ID de la orden a buscar.
     * @returns JSON con los detalles de la orden.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: detalles de la orden encontrada.
     * @errors  404: Orden no encontrada.
     *          500: Error interno del servidor.
     */

    // Actualizar una orden existente
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    /**
     * @route   PUT /api/orders/{id}
     * @name    orders.update
     * @desc    Actualiza los detalles de una orden, como su estado o productos.
     * @acces  Público (o autenticado según los requisitos).
     * @body    - status (string): Nuevo estado de la orden.
     *          - products (array): Lista de productos actualizados.
     *            - id (int): ID del producto.
     *            - quantity (int): Cantidad del producto.
     * @returns JSON con los detalles de la orden actualizada.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: detalles de la orden actualizada.
     * @errors  400: Validación fallida en los campos.
     *          404: Orden no encontrada.
     *          500: Error interno del servidor.
     */

    // Eliminar una orden
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    /**
     * @route   DELETE /api/orders/{id}
     * @name    orders.destroy
     * @desc    Elimina una orden existente del sistema.
     * @acces  Público (o autenticado según los requisitos).
     * @param   {int} id - ID de la orden a eliminar.
     * @returns JSON confirmando la eliminación.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     * @errors  404: Orden no encontrada.
     *          500: Error interno del servidor.
     */

    // Filtrar órdenes
    Route::get('/orders/filter', [OrderController::class, 'filter'])->name('orders.filter');
    /**
     * @route   GET /api/orders/filter
     * @name    orders.filter
     * @desc    Filtra las órdenes según criterios específicos (fechas, estado, usuario).
     * @acces  Público (o autenticado según los requisitos).
     * @query   - start_date (string): Fecha de inicio del rango.
     *          - end_date (string): Fecha de fin del rango.
     *          - status (string): Estado de la orden ('pending', 'completed', 'canceled').
     *          - user_id (int): ID del usuario asociado.
     * @returns JSON con la lista de órdenes que cumplen los filtros.
     *          - success: indica si la operación fue exitosa.
     *          - message: mensaje descriptivo del resultado.
     *          - data: lista de órdenes filtradas.
     * @errors  500: Error interno del servidor.
     */
});
