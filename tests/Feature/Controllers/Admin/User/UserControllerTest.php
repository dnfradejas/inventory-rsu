<?php

namespace Tests\Feature\Controllers\Admin\User;


use Tests\TestCase;
use App\Models\Role;
use App\Models\AdminUser;
use App\Models\Permission;

class UserControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $role = Role::factory()->create();
        Permission::factory()->create();
        $role->permissions()->sync([1]);
    }

    /**
     * @dataProvider data
     */
    public function testCreateUser(array $data)
    {
        $response = $this->json('POST', route('admin.user.post.create'), $data);
        
        $this->assertDatabaseHas('admin_users', [
            'role_id' => 1,
            'fullname' => 'John Doe',
            'username' => 'john',
        ]);

        $this->assertSame([
            'version' => '1.0.0',
            'status' => 'success',
            'message' => 'OK',
            'code' => 200,
            'data' => [
                'results' => 'User successfully saved!'
            ]
        ], $response->original);
    }


    /**
     * @dataProvider data
     */
    public function testUpdateUser(array $data)
    {
        unset($data['password']);
        AdminUser::factory()->create();
        $data['id'] = 1;
        $this->json('POST', route('admin.user.post.create'), $data);
        $this->assertDatabaseHas('admin_users', [
            'role_id' => 1,
            'fullname' => 'John Doe',
            'username' => 'john',
        ]);
    }

    public function testLogoutUserWhenHeChangeHisOwnPassword()
    {
        AdminUser::factory()->create();
        $data['id'] = 1;
        $this->json('POST', route('admin.user.post.create'), $data);
        $this->assertNull(session('admin_session'));
    }

    public function testDeleteAdminUser()
    {
        AdminUser::factory()->create();
        $user2 = AdminUser::factory()->create([
            'username' => 'user2'
        ]);
        session()->put('admin_session', $user2);
        $response = $this->json('DELETE', route('admin.user.delete', ['id' => 1]));

        $this->assertSame('User has been deleted!', $response->original['data']['results']);
    }

    public function testCannotDeleteActiveAdminUser()
    {
        $user = AdminUser::factory()->create();
        session()->put('admin_session', $user);
        $response = $this->json('DELETE', route('admin.user.delete', ['id' => 1]));

        $this->assertSame('Cannot delete currently logged in user!', $response->original['data']['results']);
    }

    public function tearDown(): void
    {
        session()->forget('admin_session');
        parent::tearDown();
    }

    public function data()
    {
        return [
            array(
                array(
                    'role' => 1,
                    'fullname' => 'John Doe',
                    'username' => 'john',
                    'password' => 'password',
                    'status' => AdminUser::ACTIVE,
                )
            )
        ];
    }
}