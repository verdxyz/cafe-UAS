<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiResponse;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of orders with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'menu']);

        /** @var \App\Models\User $user */
        $user = $request->user();

        // Pengunjung can only see their own orders
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $filters = [];

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
            $filters['status'] = $request->string('status')->toString();
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->string('date'));
            $filters['date'] = $request->string('date')->toString();
        }

        $limit = $request->integer('limit', 10);
        $orders = $query->orderBy('id', 'desc')->paginate($limit);

        return $this->paginatedResponse($orders, OrderResource::class, $filters);
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $menu = Menu::findOrFail($validated['menu_id']);

        if ($menu->stok < $validated['jumlah']) {
            return response()->json([
                'message' => 'Insufficient stock. Available: ' . $menu->stok,
            ], 422);
        }

        $menu->decrement('stok', $validated['jumlah']);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'menu_id' => $validated['menu_id'],
            'jumlah' => $validated['jumlah'],
            'status' => 'pending',
            'tanggal' => now(),
        ]);

        return response()->json([
            'message' => 'Order created',
            'order' => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order): OrderResource|JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->isAdmin() && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $order->load(['user', 'menu']);

        return new OrderResource($order);
    }

    /**
     * Update the specified order.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Pengunjung can only update their own pending orders
        if (! $user->isAdmin()) {
            if ($order->user_id !== $user->id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            if ($order->status !== 'pending') {
                return response()->json(['message' => 'Only pending orders can be modified'], 422);
            }
        }

        $order->update($request->validated());

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => new OrderResource($order),
        ]);
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Request $request, Order $order): JsonResponse
    {
        // Restore stock if order was not cancelled
        if ($order->status !== 'dibatalkan') {
            $order->menu?->increment('stok', $order->jumlah);
        }

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }

    /**
     * Generate order report by period.
     */
    public function report(Request $request): JsonResponse
    {
        $period = $request->string('period', 'monthly')->toString();

        $query = Order::where('status', 'selesai');

        switch ($period) {
            case 'daily':
                $query->whereDate('tanggal', today());
                $periodLabel = today()->format('d F Y');
                break;
            case 'weekly':
                $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                $periodLabel = now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y');
                break;
            case 'monthly':
            default:
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
                $periodLabel = now()->format('F Y');
                break;
        }

        $totalOrders = $query->count();

        $totalIncome = (clone $query)
            ->join('menus', 'orders.menu_id', '=', 'menus.id')
            ->selectRaw('SUM(orders.jumlah * menus.harga) as total')
            ->value('total') ?? 0;

        return response()->json([
            'total_orders' => $totalOrders,
            'total_income' => (float) $totalIncome,
            'period' => $periodLabel,
        ]);
    }
}
