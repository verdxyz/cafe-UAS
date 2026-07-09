<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Traits\ApiResponse;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of reviews with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['user', 'menu']);

        $filters = [];

        if ($request->filled('menu_id')) {
            $query->where('menu_id', $request->integer('menu_id'));
            $filters['menu_id'] = $request->integer('menu_id');
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
            $filters['rating'] = $request->integer('rating');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
            $filters['user_id'] = $request->integer('user_id');
        }

        $limit = $request->integer('limit', 10);
        $reviews = $query->orderBy('id', 'desc')->paginate($limit);

        return $this->paginatedResponse($reviews, ReviewResource::class, $filters);
    }

    /**
     * Store a newly created review.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
            'tanggal' => now(),
        ]);

        return response()->json([
            'message' => 'Review created',
            'review' => new ReviewResource($review),
        ], 201);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): ReviewResource
    {
        $review->load(['user', 'menu']);

        return new ReviewResource($review);
    }

    /**
     * Update the specified review.
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden. You can only edit your own reviews.'], 403);
        }

        $review->update($request->validated());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => new ReviewResource($review),
        ]);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
