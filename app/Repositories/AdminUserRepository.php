<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AdminUserRepository
{
    /**
     * Get users
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $users = DB::table('admin_users')
                   ->select(
                       'admin_users.*',
                       'roles.name as role',
                       'roles.slug as role_slug'
                   )
                   ->join('roles', 'admin_users.role_id', '=', 'roles.id')
                   ->get();

        return $users;
    }
}