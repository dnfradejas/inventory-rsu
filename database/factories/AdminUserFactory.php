<?php

namespace Database\Factories;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => 1,
            'fullname' => 'John Doe',
            'username' => 'john',
            'password' => bcrypt('password'),
            'status' => AdminUser::ACTIVE,
        ];
    }
}
