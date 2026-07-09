<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use App\Http\Traits\ApiResponse;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of menu items with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Menu::query();

        $filters = [];

        if ($request->filled('category')) {
            $query->where('kategori', $request->string('category'));
            $filters['category'] = $request->string('category')->toString();
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->string('search').'%');
            $filters['search'] = $request->string('search')->toString();
        }

        if ($request->filled('min_price')) {
            $query->where('harga', '>=', $request->float('min_price'));
            $filters['min_price'] = $request->float('min_price');
        }

        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->float('max_price'));
            $filters['max_price'] = $request->float('max_price');
        }

        // Sorting: sort_by (nama, harga, kategori, stok) & sort_order (asc, desc)
        $allowedSorts = ['nama', 'harga', 'kategori', 'stok', 'id'];
        $sortBy = in_array($request->input('sort_by'), $allowedSorts)
            ? $request->input('sort_by')
            : 'id';
        $sortOrder = $request->input('sort_order') === 'asc' ? 'asc' : 'desc';

        if ($request->filled('sort_by')) {
            $filters['sort_by'] = $sortBy;
            $filters['sort_order'] = $sortOrder;
        }

        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->integer('limit', 5);
        $menus = $query->paginate($limit);

        return $this->paginatedResponse($menus, MenuResource::class, $filters);
    }

    /**
     * Store a newly created menu item.
     */
    public function store(StoreMenuRequest $request): JsonResponse
    {
        $menu = Menu::create($request->validated());

        return response()->json([
            'message' => 'Menu created successfully',
            'menu' => new MenuResource($menu),
        ], 201);
    }

    /**
     * Display the specified menu item.
     */
    public function show(Menu $menu): MenuResource
    {
        return new MenuResource($menu);
    }

    /**
     * Update the specified menu item.
     */
    public function update(UpdateMenuRequest $request, Menu $menu): JsonResponse
    {
        $menu->update($request->validated());

        return response()->json([
            'message' => 'Menu updated successfully',
            'menu' => new MenuResource($menu),
        ]);
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();

        return response()->json([
            'message' => 'Menu deleted successfully',
        ]);
    }
}
