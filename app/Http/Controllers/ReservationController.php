<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Traits\ApiResponse;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of reservations with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::with('user');

        /** @var \App\Models\User $user */
        $user = $request->user();

        // Pengunjung can only see their own reservations
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $filters = [];

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->string('date'));
            $filters['date'] = $request->string('date')->toString();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
            $filters['status'] = $request->string('status')->toString();
        }

        $limit = $request->integer('limit', 10);
        $reservations = $query->orderBy('id', 'desc')->paginate($limit);

        return $this->paginatedResponse($reservations, ReservationResource::class, $filters);
    }

    /**
     * Store a newly created reservation.
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Reservation created',
            'reservation' => new ReservationResource($reservation),
        ], 201);
    }

    /**
     * Display the specified reservation.
     */
    public function show(Request $request, Reservation $reservation): ReservationResource|JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->isAdmin() && $reservation->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $reservation->load('user');

        return new ReservationResource($reservation);
    }

    /**
     * Update the specified reservation.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->isAdmin()) {
            if ($reservation->user_id !== $user->id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            // Pengunjung cannot change status
            if ($request->has('status')) {
                return response()->json(['message' => 'You cannot change reservation status'], 403);
            }
        }

        $reservation->update($request->validated());

        return response()->json([
            'message' => 'Reservation updated successfully',
            'reservation' => new ReservationResource($reservation),
        ]);
    }

    /**
     * Remove the specified reservation.
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully',
        ]);
    }
}
