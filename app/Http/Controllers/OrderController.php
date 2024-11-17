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
     * Filtra las órdenes según criterios específicos enviados en la solicitud.
     *
     * Esta función permite filtrar las órdenes de la base de datos utilizando varios parámetros opcionales:
     * - `start_date`: Filtra las órdenes cuyo `created_at` sea mayor o igual a esta fecha.
     * - `end_date`: Filtra las órdenes cuyo `created_at` sea menor o igual a esta fecha.
     * - `status`: Filtra las órdenes según su estado. Los valores permitidos son `pending`, `completed` o `canceled`.
     * - `user_id`: Filtra las órdenes asociadas a un usuario específico, mediante su `id`.
     *
     * Los filtros se aplican solo si los parámetros correspondientes son enviados en la solicitud.
     * Si no se envían algunos de los parámetros, esos filtros no se aplicarán.
     *
     * Si la solicitud se procesa correctamente, se devolverá un objeto JSON con las órdenes filtradas junto con los productos relacionados.
     * Si ocurre un error en el proceso de filtrado, se devolverá un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request Los criterios de filtrado proporcionados en la solicitud.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta en formato JSON con el resultado del filtrado.
     * 
     * @throws \Exception Si ocurre un error durante el filtrado de las órdenes, se maneja y retorna un error genérico.
     */
    public function filter(Request $request)
    {
        // Validación de los parámetros de la solicitud
        $validated = $request->validate([
            'start_date' => 'nullable|date',       // Fecha de inicio, formato de fecha válido
            'end_date' => 'nullable|date',         // Fecha de fin, formato de fecha válido
            'status' => 'nullable|string|in:pending,completed,canceled', // Estado de la orden (pendiente, completada, cancelada)
            'user_id' => 'nullable|exists:users,id' // ID de usuario, debe existir en la tabla de usuarios
        ]);

        try {
            // Inicia una consulta a la base de datos para las órdenes
            $orders = Order::query();

            // Aplica el filtro por `start_date` si se ha enviado
            if ($request->filled('start_date')) {
                $orders->where('created_at', '>=', $validated['start_date']);
            }

            // Aplica el filtro por `end_date` si se ha enviado
            if ($request->filled('end_date')) {
                $orders->where('created_at', '<=', $validated['end_date']);
            }

            // Aplica el filtro por `status` si se ha enviado
            if ($request->filled('status')) {
                $orders->where('status', $validated['status']);
            }

            // Aplica el filtro por `user_id` si se ha enviado
            if ($request->filled('user_id')) {
                $orders->where('user_id', $validated['user_id']);
            }

            // Devuelve las órdenes filtradas junto con los productos asociados en formato JSON
            return response()->json([
                'success' => true,
                'message' => 'Órdenes filtradas exitosamente.',
                'data' => $orders->with('products')->get() // Incluye los productos relacionados
            ], 200);
        } catch (\Exception $e) {
            // En caso de error, devuelve un mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar las órdenes.'
            ], 500);
        }
    }

}

