<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->whereNull('role')->update(['role' => 'customer']);
    }
}
