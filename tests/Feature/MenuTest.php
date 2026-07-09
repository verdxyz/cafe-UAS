<?php

use App\Models\Menu;
use App\Models\User;
use function Pest\Laravel\getJson;

it('creates a menu as admin', function () {
    $admin = User::factory()->admin()->create();

    $response = actAsJwt($admin)->postJson('/api/menu', [
        'nama' => 'Kopi Hitam',
        'kategori' => 'coffee',
        'harga' => 15000,
        'stok' => 10,
    ]);

    $response->assertCreated();
    expect($response->json('menu.nama'))->toBe('Kopi Hitam');
});

it('fails to create menu without stock or price', function () {
    $admin = User::factory()->admin()->create();

    $response = actAsJwt($admin)->postJson('/api/menu', [
        'nama' => 'Kopi Hitam',
        'kategori' => 'coffee',
        // missing harga and stok
    ]);

    $response->assertStatus(422) // Laravel validation error
        ->assertJsonValidationErrors(['harga', 'stok']);
});

it('reads menu list with pagination', function () {
    Menu::factory()->count(15)->create();

    $response = getJson('/api/menu?limit=5');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(5)
        ->and($response->json('pagination.total'))->toBe(15);
});

it('filters menu by category', function () {
    Menu::factory()->count(3)->create(['kategori' => 'coffee']);
    Menu::factory()->count(2)->create(['kategori' => 'snack']);

    $response = getJson('/api/menu?category=coffee');

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(3);
});

it('updates a menu with valid data', function () {
    $admin = User::factory()->admin()->create();
    $menu = Menu::factory()->create(['nama' => 'Old Name']);

    $response = actAsJwt($admin)->putJson("/api/menu/{$menu->id}", [
        'nama' => 'New Name',
    ]);

    $response->assertSuccessful();
    expect($response->json('menu.nama'))->toBe('New Name');
});

it('returns 404 when updating non-existent menu', function () {
    $admin = User::factory()->admin()->create();

    $response = actAsJwt($admin)->putJson('/api/menu/999', [
        'nama' => 'New Name',
    ]);

    $response->assertNotFound();
});

it('deletes a menu as admin', function () {
    $admin = User::factory()->admin()->create();
    $menu = Menu::factory()->create();

    $response = actAsJwt($admin)->deleteJson("/api/menu/{$menu->id}");

    $response->assertSuccessful();
    $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
});

it('returns 404 when deleting non-existent menu', function () {
    $admin = User::factory()->admin()->create();

    $response = actAsJwt($admin)->deleteJson('/api/menu/999');

    $response->assertNotFound();
});
