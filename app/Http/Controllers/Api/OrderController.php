<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with relations.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user:id,name,email', 'orderItems.product:id,name,price']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $orders = $query->latest()->get();

        return response()->json([
            'success' => true,
            'count' => $orders->count(),
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order in storage.
     * Validates foreign key to users, products existence, and calculates totals transaccionalmente.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                // 1. Crear encabezado de la orden inicialmente con 0
                $order = Order::create([
                    'user_id' => $validated['user_id'],
                    'notes' => $validated['notes'] ?? null,
                    'status' => 'pending',
                    'total_amount' => 0,
                ]);

                $totalAmount = 0;

                // 2. Procesar ítems y calcular subtotales
                foreach ($validated['items'] as $itemData) {
                    $product = Product::findOrFail($itemData['product_id']);
                    $quantity = $itemData['quantity'];
                    $unitPrice = $product->price;
                    $subtotal = $unitPrice * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    $totalAmount += $subtotal;
                }

                // 3. Actualizar total calculado
                $order->update(['total_amount' => $totalAmount]);

                return $order;
            });

            // Cargar datos relacionados para la respuesta
            $order->load(['user:id,name,email', 'orderItems.product:id,name,price']);

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado satisfactoriamente con sus ítems asociados.',
                'data' => $order
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order with full relationships.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::with(['user:id,name,email', 'orderItems.product:id,name,description,price'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Pedido no encontrado con ID {$id}"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified order (e.g. status, notes).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Pedido no encontrado con ID {$id}"
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update($validated);
        $order->load(['user:id,name,email', 'orderItems.product:id,name,price']);

        return response()->json([
            'success' => true,
            'message' => 'Pedido actualizado con éxito.',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Pedido no encontrado con ID {$id}"
            ], 404);
        }

        // Al eliminar el pedido, la clave foránea en order_items con onDelete('cascade') garantiza la integridad
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => "Pedido #{$id} y sus detalles eliminados correctamente con integridad referencial."
        ]);
    }
}
