<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // User::factory()->create(
        //     [
        //         'name'     => 'admin',
        //         'email'    => 'admin@example.com',
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        //         'remember_token'    => Str::random(10),
        //     ]);
        // создание 4 пользователей
        User::factory()->count(4)->create();
    }
}
