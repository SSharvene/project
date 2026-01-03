<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name'=>'admin_ict','label'=>'Admin ICT','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'admin_hr','label'=>'Admin HR','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'staff','label'=>'Staff','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
