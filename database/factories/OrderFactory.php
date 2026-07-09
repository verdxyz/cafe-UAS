<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'selesai', 'dibatalkan'];
        
        return [
            'user_id' => User::factory(),
            'menu_id' => Menu::factory(),
            'jumlah' => rand(1, 10),
            'status' => $statuses[array_rand($statuses)],
            'tanggal' => now()->subDays(rand(0, 30)),
        ];
    }

    /**
     * Indicate the order is completed.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }

    /**
     * Indicate the order is cancelled.
     */
    public function dibatalkan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'dibatalkan',
        ]);
    }
}
