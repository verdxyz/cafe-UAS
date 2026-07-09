<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('registers a user successfully', function () {
    $response = postJson('/api/auth/register', [
        'nama' => 'Verdy',
        'email' => 'verdy@example.com',
        'password' => '123456',
        'role' => 'pengunjung',
    ]);

    $response->assertCreated();
    expect($response->json('user.email'))->toBe('verdy@example.com');
});

it('fails to register with a duplicate email', function () {
    User::factory()->create(['email' => 'verdy@example.com']);

    $response = postJson('/api/auth/register', [
        'nama' => 'Verdy 2',
        'email' => 'verdy@example.com',
        'password' => '123456',
        'role' => 'pengunjung',
    ]);

    $response->assertStatus(422) // Bad Request / Unprocessable Entity
        ->assertJsonValidationErrors('email');
});

it('logs in a user successfully', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertSuccessful();
    expect($response->json('token'))->not->toBeNull();
});

it('fails to log in with wrong password', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
});

it('fails to log in with an unregistered email', function () {
    $response = postJson('/api/auth/login', [
        'email' => 'notfound@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(404); 
});
