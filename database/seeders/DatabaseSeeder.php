<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // ===== USERS =====
        $this->command->info('👤 Creating admin user...');
        $admin = User::create([
            'nama' => 'Admin Cafe',
            'email' => 'admin@cafe.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->command->info("   ✓ Admin: {$admin->email} | Password: admin123");

        $this->command->info('👥 Creating regular users...');
        $regularUsers = [
            ['nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'password' => 'password'],
            ['nama' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'password' => 'password'],
            ['nama' => 'Andi Wijaya', 'email' => 'andi@example.com', 'password' => 'password'],
            ['nama' => 'Dewi Kusuma', 'email' => 'dewi@example.com', 'password' => 'password'],
            ['nama' => 'Rudi Hartono', 'email' => 'rudi@example.com', 'password' => 'password'],
        ];

        $users = collect();
        foreach ($regularUsers as $userData) {
            $user = User::create([
                'nama' => $userData['nama'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'role' => 'pengunjung',
                'email_verified_at' => now(),
            ]);
            $users->push($user);
            $this->command->info("   ✓ User: {$user->email}");
        }

        // Add random users
        $randomUsers = User::factory(10)->create();
        $users = $users->merge($randomUsers);
        $this->command->info("   ✓ Created 10 additional random users");

        // ===== MENU =====
        $this->command->info('🍽️  Creating menu items...');

        $menuData = [
            // Makanan
            ['nama' => 'Nasi Goreng Spesial', 'kategori' => 'Makanan', 'harga' => 25000, 'stok' => 50],
            ['nama' => 'Mie Goreng', 'kategori' => 'Makanan', 'harga' => 20000, 'stok' => 45],
            ['nama' => 'Ayam Bakar Madu', 'kategori' => 'Makanan', 'harga' => 35000, 'stok' => 30],
            ['nama' => 'Sate Ayam (10 tusuk)', 'kategori' => 'Makanan', 'harga' => 30000, 'stok' => 40],
            ['nama' => 'Rendang Daging', 'kategori' => 'Makanan', 'harga' => 40000, 'stok' => 25],
            ['nama' => 'Gado-Gado', 'kategori' => 'Makanan', 'harga' => 18000, 'stok' => 35],
            ['nama' => 'Soto Ayam', 'kategori' => 'Makanan', 'harga' => 22000, 'stok' => 40],
            ['nama' => 'Bakso Spesial', 'kategori' => 'Makanan', 'harga' => 20000, 'stok' => 50],
            ['nama' => 'Nasi Uduk Komplit', 'kategori' => 'Makanan', 'harga' => 25000, 'stok' => 30],
            ['nama' => 'Capcay', 'kategori' => 'Makanan', 'harga' => 23000, 'stok' => 35],

            // Minuman
            ['nama' => 'Es Teh Manis', 'kategori' => 'Minuman', 'harga' => 5000, 'stok' => 100],
            ['nama' => 'Kopi Hitam', 'kategori' => 'Minuman', 'harga' => 8000, 'stok' => 80],
            ['nama' => 'Cappuccino', 'kategori' => 'Minuman', 'harga' => 18000, 'stok' => 60],
            ['nama' => 'Cafe Latte', 'kategori' => 'Minuman', 'harga' => 20000, 'stok' => 55],
            ['nama' => 'Jus Jeruk', 'kategori' => 'Minuman', 'harga' => 12000, 'stok' => 70],
            ['nama' => 'Jus Alpukat', 'kategori' => 'Minuman', 'harga' => 15000, 'stok' => 50],
            ['nama' => 'Es Campur', 'kategori' => 'Minuman', 'harga' => 18000, 'stok' => 45],
            ['nama' => 'Teh Tarik', 'kategori' => 'Minuman', 'harga' => 10000, 'stok' => 65],
            ['nama' => 'Matcha Latte', 'kategori' => 'Minuman', 'harga' => 22000, 'stok' => 40],
            ['nama' => 'Americano', 'kategori' => 'Minuman', 'harga' => 15000, 'stok' => 70],

            // Snack
            ['nama' => 'Pisang Goreng', 'kategori' => 'Snack', 'harga' => 10000, 'stok' => 50],
            ['nama' => 'Tahu Crispy', 'kategori' => 'Snack', 'harga' => 12000, 'stok' => 45],
            ['nama' => 'Kentang Goreng', 'kategori' => 'Snack', 'harga' => 15000, 'stok' => 60],
            ['nama' => 'Roti Bakar Coklat', 'kategori' => 'Snack', 'harga' => 12000, 'stok' => 40],
            ['nama' => 'Croissant', 'kategori' => 'Snack', 'harga' => 18000, 'stok' => 30],
            ['nama' => 'Donat Coklat', 'kategori' => 'Snack', 'harga' => 10000, 'stok' => 50],
            ['nama' => 'Brownies', 'kategori' => 'Snack', 'harga' => 15000, 'stok' => 35],
            ['nama' => 'Cheesecake', 'kategori' => 'Snack', 'harga' => 25000, 'stok' => 20],
            ['nama' => 'Pancake', 'kategori' => 'Snack', 'harga' => 20000, 'stok' => 25],
            ['nama' => 'Waffle', 'kategori' => 'Snack', 'harga' => 22000, 'stok' => 25],
        ];

        $menus = collect();
        foreach ($menuData as $menu) {
            $menuItem = Menu::create($menu);
            $menus->push($menuItem);
        }
        $this->command->info("   ✓ Created {$menus->count()} menu items");

        // ===== ORDERS =====
        $this->command->info('📦 Creating sample orders...');
        $orderCount = 0;

        $users->random(min(10, $users->count()))->each(function (User $user) use ($menus, &$orderCount) {
            $numOrders = rand(1, 4);
            $orderCount += $numOrders;
            Order::factory($numOrders)
                ->recycle($user)
                ->recycle($menus->random(min(5, $menus->count())))
                ->create();
        });
        $this->command->info("   ✓ Created {$orderCount} orders");

        // ===== RESERVATIONS =====
        $this->command->info('📅 Creating reservations...');
        $reservationCount = 0;

        $users->random(min(8, $users->count()))->each(function (User $user) use (&$reservationCount) {
            $count = rand(1, 2);
            $reservationCount += $count;
            Reservation::factory($count)
                ->recycle($user)
                ->create();
        });
        $this->command->info("   ✓ Created {$reservationCount} reservations");

        // ===== REVIEWS =====
        $this->command->info('⭐ Creating reviews...');
        $reviewCount = 0;

        $users->random(min(12, $users->count()))->each(function (User $user) use ($menus, &$reviewCount) {
            $count = rand(1, 3);
            $reviewCount += $count;
            Review::factory($count)
                ->recycle($user)
                ->recycle($menus->random(min(5, $menus->count())))
                ->create();
        });
        $this->command->info("   ✓ Created {$reviewCount} reviews");

        // ===== SUMMARY =====
        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Users (Admin)', '1'],
                ['Users (Regular)', $users->count()],
                ['Menu Items', $menus->count()],
                ['Orders', $orderCount],
                ['Reservations', $reservationCount],
                ['Reviews', $reviewCount],
            ]
        );
        $this->command->newLine();
        $this->command->info('🔐 Admin Credentials:');
        $this->command->line("   Email: admin@cafe.com");
        $this->command->line("   Password: admin123");
        $this->command->newLine();
        $this->command->info('👥 Sample User Credentials (all use password: password):');
        foreach ($regularUsers as $user) {
            $this->command->line("   - {$user['email']}");
        }
        $this->command->newLine();
    }
}
