<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Makanan', 'Minuman', 'Snack'];
        $menuItems = [
            'Makanan' => ['Nasi Goreng', 'Mie Goreng', 'Ayam Bakar', 'Sate Ayam', 'Rendang', 'Gado-Gado', 'Soto Ayam', 'Nasi Uduk', 'Bakso', 'Nasi Campur'],
            'Minuman' => ['Es Teh Manis', 'Kopi Hitam', 'Cappuccino', 'Latte', 'Jus Jeruk', 'Jus Alpukat', 'Es Campur', 'Teh Tarik', 'Matcha Latte', 'Americano'],
            'Snack' => ['Pisang Goreng', 'Tahu Crispy', 'Kentang Goreng', 'Roti Bakar', 'Croissant', 'Donat', 'Brownies', 'Cheesecake', 'Pancake', 'Waffle'],
        ];

        $kategori = $categories[array_rand($categories)];
        $nama = $menuItems[$kategori][array_rand($menuItems[$kategori])];

        return [
            'nama' => $nama,
            'kategori' => $kategori,
            'harga' => rand(5000, 150000) / 100 * 100, // Round to nearest 100
            'stok' => rand(0, 100),
        ];
    }

    /**
     * Indicate the menu item is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok' => 0,
        ]);
    }
}
