<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ตรวจว่ามี admin อยู่แล้วหรือไม่
        if (!User::where('email', 'admin@livescore.com')->exists()) {
            User::create([
                'name' => 'Main Admin',
                'email' => 'admin@livescore.com',
                'password' => Hash::make('password'), // คุณเปลี่ยนรหัสนี้ได้
                'role' => 'admin',
                'status' => true,
            ]);
        }
    }
}
