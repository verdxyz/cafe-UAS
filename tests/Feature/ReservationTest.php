<?php

use App\Models\Reservation;
use App\Models\User;

it('creates a reservation as pengunjung', function () {
    $user = User::factory()->create();

    $response = actAsJwt($user)->postJson('/api/reservations', [
        'tanggal' => now()->addDays(2)->format('Y-m-d'),
        'jam' => '19:00',
        'jumlah_orang' => 4,
    ]);

    $response->assertCreated();
    expect($response->json('reservation.jumlah_orang'))->toBe(4);
});

it('fails to create a reservation with past date', function () {
    $user = User::factory()->create();

    $response = actAsJwt($user)->postJson('/api/reservations', [
        'tanggal' => now()->subDay()->format('Y-m-d'),
        'jam' => '19:00',
        'jumlah_orang' => 4,
    ]);

    $response->assertStatus(422);
});

it('reads reservation list with pagination', function () {
    $admin = User::factory()->admin()->create();
    Reservation::factory()->count(12)->create();

    $response = actAsJwt($admin)->getJson('/api/reservations?limit=5');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(5)
        ->and($response->json('pagination.total'))->toBe(12);
});

it('filters reservations by date and status', function () {
    $admin = User::factory()->admin()->create();
    Reservation::factory()->count(2)->create(['status' => 'confirmed', 'tanggal' => '2026-07-07']);
    Reservation::factory()->count(3)->create(['status' => 'pending', 'tanggal' => '2026-07-08']);

    $response = actAsJwt($admin)->getJson('/api/reservations?status=confirmed&date=2026-07-07');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(2);
});

it('updates reservation as owner', function () {
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create(['user_id' => $user->id, 'jumlah_orang' => 2]);

    $response = actAsJwt($user)->putJson("/api/reservations/{$reservation->id}", [
        'jumlah_orang' => 5,
    ]);

    $response->assertSuccessful();
    expect($response->json('reservation.jumlah_orang'))->toBe(5);
});

it('returns 403 when updating another user reservation', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $reservation = Reservation::factory()->create(['user_id' => $user2->id]);

    $response = actAsJwt($user1)->putJson("/api/reservations/{$reservation->id}", [
        'jumlah_orang' => 5,
    ]);

    $response->assertForbidden();
});

it('deletes a reservation as admin', function () {
    $admin = User::factory()->admin()->create();
    $reservation = Reservation::factory()->create();

    $response = actAsJwt($admin)->deleteJson("/api/reservations/{$reservation->id}");

    $response->assertSuccessful();
});

it('returns 403 when deleting a reservation as pengunjung', function () {
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create(['user_id' => $user->id]);

    $response = actAsJwt($user)->deleteJson("/api/reservations/{$reservation->id}");

    $response->assertForbidden();
});
