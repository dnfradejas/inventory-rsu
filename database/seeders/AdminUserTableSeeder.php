<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'role_id' => 1,
            'fullname' => 'admin',
            'username' => 'admin',
            'password' => bcrypt(env('CMS_DEFAULT_PASSWORD')),
            'created_at' => now()->__toString(),
            'updated_at' => now()->__toString(),
        ]);
    }
}
