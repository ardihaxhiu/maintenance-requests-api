<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enums\UserRole;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            UserRole::ADMIN->value => 'admin@gmail.com',
            UserRole::USER->value => 'user@gmail.com',
            UserRole::TECHNICIAN->value => 'technician@gmail.com',
        ];

        foreach (UserRole::cases() as $role) {
            User::create([
                'name' => $role->value,
                'email' => $emails[$role->value],
                'password' => Hash::make('123secret'),
                'role' => $role,
            ]);
        }
    }
}
