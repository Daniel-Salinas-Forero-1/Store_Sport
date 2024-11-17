<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    /**
     * Listar todas las órdenes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $orders = Order::with('products')->get();

            return response()->json([
                'success' => true,
                'message' => 'Lista de órdenes recuperada con éxito.',
                'data' => $orders,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recuperar la lista de órdenes.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear una nueva orden.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'total' => 0,  // Calcularemos el total más tarde.
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($validatedData['products'] as $productData) {
                $product = Product::find($productData['id']);
                $total += $product->price * $productData['quantity'];

                // Agregar el producto a la orden
                $order->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ]);
            }

            // Actualizar el total de la orden
            $order->total = $total;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Orden creada con éxito.',
                'data' => $order,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Muestra los detalles de una orden específica.
     *
     * @param int $id ID de la orden a consultar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $order = Order::with('products')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detalles de la orden obtenidos exitosamente.',
                'data' => $order
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los detalles de la orden.'
            ], 500);
        }
    }

    /**
     * Actualiza una orden existente.
     *
     * @param \Illuminate\Http\Request $request Datos de la orden a actualizar.
     * @param int $id ID de la orden a actualizar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,completed,canceled',
            'products' => 'nullable|array',
            'products.*.id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1'
        ]);

        try {
            $order = Order::findOrFail($id);

            if ($request->has('status')) {
                $order->status = $validated['status'];
            }

            if ($request->has('products')) {
                $products = collect($validated['products'])->mapWithKeys(function ($product) {
                    return [$product['id'] => ['quantity' => $product['quantity']]];
                });

                $order->products()->sync($products);
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizada exitosamente.',
                'data' => $order->load('products')
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors()
            ], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la orden.'
            ], 500);
        }
    }

    /**
     * Elimina una orden específica.
     *
     * @param int $id ID de la orden a eliminar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Orden eliminada exitosamente.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la orden.'
            ], 500);
        }
    }

    /**
     * Filtra las órdenes según criterios específicos.
     *
     * @param \Illuminate\Http\Request $request Criterios de filtrado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,completed,canceled',
            'user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $orders = Order::query();

            if ($request->filled('start_date')) {
                $orders->where('created_at', '>=', $validated['start_date']);
            }

            if ($request->filled('end_date')) {
                $orders->where('created_at', '<=', $validated['end_date']);
            }

            if ($request->filled('status')) {
                $orders->where('status', $validated['status']);
            }

            if ($request->filled('user_id')) {
                $orders->where('user_id', $validated['user_id']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Órdenes filtradas exitosamente.',
                'data' => $orders->with('products')->get()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar las órdenes.'
            ], 500);
        }
    }
}

