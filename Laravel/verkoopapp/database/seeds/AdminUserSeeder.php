<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::create([
            'username' => 'verkoopadmin',
            'email' => 'admin@verkoop.com',
            'password' => Hash::make('Verkoop!1'),
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'login_type' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        JWTAuth::fromUser($admin, ['exp' => Carbon::now()->addDays(7)->timestamp]);
    }
}
