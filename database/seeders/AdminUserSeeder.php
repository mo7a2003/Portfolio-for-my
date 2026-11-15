<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'mohamhwaty@gmail.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Moh2oo3$$');
        $adminName = env('ADMIN_NAME', 'Mohammed Alhwaiti');

        $adminExists = User::where('email', $adminEmail)->exists();

        if (! $adminExists) {
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]);
        }
    }
}
