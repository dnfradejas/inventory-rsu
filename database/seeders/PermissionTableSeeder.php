<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'View Reports',
                'slug' => sluggify('permission view reports'),
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ],
            [
                'name' => 'Create Sales Invoice',
                'slug' => sluggify('permission create sales invoice'),
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ],
            [
                'name' => 'Manage Users',
                'slug' => sluggify('permission manage users'),
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ],
            [
                'name' => 'Manage Inventory',
                'slug' => sluggify('permission manage inventory'),
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ]
        ];
        DB::table('permissions')->insert($data);
    }
}
