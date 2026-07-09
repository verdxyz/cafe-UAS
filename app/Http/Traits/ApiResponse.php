<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    /**
     * Build a paginated response with consistent format:
     * { data, filters, pagination: { page, limit, total, totalPages } }
     *
     * @param  LengthAwarePaginator  $paginator
     * @param  class-string<JsonResource>  $resource
     * @param  array<string, mixed>  $filters
     */
    protected function paginatedResponse(
        LengthAwarePaginator $paginator,
        string $resource,
        array $filters = [],
    ): JsonResponse {
        return response()->json([
            'data' => $resource::collection($paginator->items()),
            'filters' => !empty($filters) ? $filters : (object) [],
            'pagination' => [
                'page' => $paginator->currentPage(),
                'limit' => $paginator->perPage(),
                'total' => $paginator->total(),
                'totalPages' => $paginator->lastPage(),
            ],
        ]);
    }
}
