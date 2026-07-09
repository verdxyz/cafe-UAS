<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comments = [
            'Makanan enak sekali!',
            'Pelayanan ramah dan cepat.',
            'Tempatnya nyaman.',
            'Harga terjangkau.',
            'Menu favorit saya!',
            'Recommended banget!',
            'Akan datang lagi.',
            'Porsi pas dan rasa oke.',
        ];
        
        return [
            'user_id' => User::factory(),
            'menu_id' => Menu::factory(),
            'rating' => rand(1, 5),
            'komentar' => $comments[array_rand($comments)],
            'tanggal' => now()->subDays(rand(0, 60)),
        ];
    }
}
