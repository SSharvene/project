<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // adjust email & password for local testing
        User::create([
            'name'=>'Admin ICT',
            'email'=>'admin@jakoa.local',
            'password'=>Hash::make('password123'),
            'role_id'=>1, // admin_ict
            'nama'=>'Admin ICT',
        ]);
    }
}
