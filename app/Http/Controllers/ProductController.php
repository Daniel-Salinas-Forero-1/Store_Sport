<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Listar todos los productos del catálogo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'success' => true,
                'message' => 'Lista de productos recuperada con éxito.',
                'data' => $products,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recuperar la lista de productos.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear un nuevo producto en el catálogo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        try {
            $product = Product::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado con éxito.',
                'data' => $product,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar un producto específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Producto recuperado con éxito.',
                'data' => $product,
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
                'error' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recuperar el producto.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar un producto existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'category' => 'string|max:255',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado con éxito.',
                'data' => $product,
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
                'error' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un producto del catálogo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado con éxito.',
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
                'error' => $exception->getMessage(),
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Filtrar productos según criterios específicos.
     *
     * Este método permite filtrar los productos en la base de datos según los parámetros proporcionados
     * en la solicitud. Los criterios de filtrado incluyen nombre, categoría, precio, y cantidad en stock.
     * La función devuelve los productos que coincidan con los filtros especificados, o todos los productos 
     * si no se proporcionan filtros.
     *
     * Los filtros que se pueden aplicar son:
     * - `name`: Filtra los productos que contienen el nombre proporcionado.
     * - `category`: Filtra los productos que contienen la categoría proporcionada.
     * - `min_price`: Filtra los productos cuyo precio es mayor o igual al valor especificado.
     * - `max_price`: Filtra los productos cuyo precio es menor o igual al valor especificado.
     * - `min_stock`: Filtra los productos cuyo stock es mayor o igual al valor especificado.
     * - `max_stock`: Filtra los productos cuyo stock es menor o igual al valor especificado.
     *
     * Si algún producto no cumple con los filtros, no se incluirá en los resultados.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los parámetros de filtrado.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los productos filtrados o un mensaje de error.
     * 
     * La respuesta tendrá el siguiente formato:
     * - Si la operación es exitosa:
     *     - `success`: true
     *     - `data`: Un array de productos filtrados.
     * - Si ocurre un error durante el proceso de filtrado:
     *     - `success`: false
     *     - `message`: Un mensaje de error indicando que ocurrió un problema.
     */
    public function filter(Request $request)
    {
        // Validación de los parámetros de la solicitud.
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',     // El nombre del producto, puede ser nulo y debe ser una cadena.
            'category' => 'nullable|string|max:255', // La categoría del producto, puede ser nulo y debe ser una cadena.
            'min_price' => 'nullable|numeric',      // El precio mínimo del producto, puede ser nulo y debe ser numérico.
            'max_price' => 'nullable|numeric',      // El precio máximo del producto, puede ser nulo y debe ser numérico.
            'min_stock' => 'nullable|integer',      // El stock mínimo del producto, puede ser nulo y debe ser un número entero.
            'max_stock' => 'nullable|integer',      // El stock máximo del producto, puede ser nulo y debe ser un número entero.
        ]);

        try {
            // Inicia una consulta para obtener los productos.
            $products = Product::query();

            // Aplicar filtros según los parámetros proporcionados en la solicitud.

            // Filtrar por nombre, si se proporciona.
            if ($request->filled('name')) {
                $products->where('name', 'like', '%' . $validated['name'] . '%');
            }

            // Filtrar por categoría, si se proporciona.
            if ($request->filled('category')) {
                $products->where('category', 'like', '%' . $validated['category'] . '%');
            }

            // Filtrar por precio mínimo, si se proporciona.
            if ($request->filled('min_price')) {
                $products->where('price', '>=', $validated['min_price']);
            }

            // Filtrar por precio máximo, si se proporciona.
            if ($request->filled('max_price')) {
                $products->where('price', '<=', $validated['max_price']);
            }

            // Filtrar por stock mínimo, si se proporciona.
            if ($request->filled('min_stock')) {
                $products->where('stock', '>=', $validated['min_stock']);
            }

            // Filtrar por stock máximo, si se proporciona.
            if ($request->filled('max_stock')) {
                $products->where('stock', '<=', $validated['max_stock']);
            }

            // Ejecutar la consulta y devolver los productos filtrados.
            return response()->json([
                'success' => true,  // Indicamos que la operación fue exitosa.
                'data' => $products->get(), // Retornamos los productos filtrados.
            ]);
        } catch (\Exception $e) {
            // Si ocurre un error en el proceso de filtrado, devolvemos un mensaje de error.
            return response()->json([
                'success' => false, // Indicamos que la operación falló.
                'message' => 'Error al filtrar los productos.', // Mensaje de error general.
            ], 500); // Código de estado HTTP 500: Error interno del servidor.
        }
    }

}
