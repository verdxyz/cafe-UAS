<?php

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;

it('creates an order as pengunjung', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['stok' => 10]);

    $response = actAsJwt($user)->postJson('/api/orders', [
        'menu_id' => $menu->id,
        'jumlah' => 2,
    ]);

    $response->assertCreated();
    expect($response->json('order.jumlah'))->toBe(2);
    
    // Check stock was decremented
    expect($menu->fresh()->stok)->toBe(8);
});

it('fails to create an order if stock is insufficient', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['stok' => 5]);

    $response = actAsJwt($user)->postJson('/api/orders', [
        'menu_id' => $menu->id,
        'jumlah' => 10,
    ]);

    // The code returns 422 if stock < jumlah
    $response->assertStatus(422);
});

it('reads order list with pagination', function () {
    $admin = User::factory()->admin()->create();
    Order::factory()->count(15)->create();

    $response = actAsJwt($admin)->getJson('/api/orders?limit=5');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(5)
        ->and($response->json('pagination.total'))->toBe(15);
});

it('filters orders by status', function () {
    $admin = User::factory()->admin()->create();
    Order::factory()->count(4)->create(['status' => 'pending']);
    Order::factory()->count(3)->create(['status' => 'selesai']);

    $response = actAsJwt($admin)->getJson('/api/orders?status=selesai');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(3);
});

it('updates order as owner (if pending)', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'jumlah' => 1]);

    $response = actAsJwt($user)->putJson("/api/orders/{$order->id}", [
        'jumlah' => 2,
    ]);

    $response->assertSuccessful();
    expect($response->json('order.jumlah'))->toBe(2);
});

it('returns 403 when updating another user order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user2->id, 'status' => 'pending']);

    $response = actAsJwt($user1)->putJson("/api/orders/{$order->id}", [
        'jumlah' => 2,
    ]);

    $response->assertForbidden();
});

it('deletes an order as admin and restores stock', function () {
    $admin = User::factory()->admin()->create();
    $menu = Menu::factory()->create(['stok' => 10]);
    $order = Order::factory()->create(['menu_id' => $menu->id, 'jumlah' => 2, 'status' => 'pending']);

    $response = actAsJwt($admin)->deleteJson("/api/orders/{$order->id}");

    $response->assertSuccessful();
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    
    // Check stock was restored
    expect($menu->fresh()->stok)->toBe(12);
});

it('returns 404 when deleting non-existent order', function () {
    $admin = User::factory()->admin()->create();

    $response = actAsJwt($admin)->deleteJson('/api/orders/999');

    $response->assertNotFound();
});

it('generates monthly report correctly', function () {
    $admin = User::factory()->admin()->create();
    
    $menu1 = Menu::factory()->create(['harga' => 10000]);
    $menu2 = Menu::factory()->create(['harga' => 20000]);

    // Create 2 completed orders this month
    Order::factory()->create(['menu_id' => $menu1->id, 'jumlah' => 2, 'status' => 'selesai', 'tanggal' => now()]);
    Order::factory()->create(['menu_id' => $menu2->id, 'jumlah' => 1, 'status' => 'selesai', 'tanggal' => now()]);

    $response = actAsJwt($admin)->getJson('/api/orders/report?period=monthly');

    $response->assertSuccessful();
    expect($response->json('total_orders'))->toBe(2)
        ->and($response->json('total_income'))->toBe(40000); // (2*10000) + (1*20000)
});
