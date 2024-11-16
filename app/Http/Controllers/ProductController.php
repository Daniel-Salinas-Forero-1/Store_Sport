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
}
