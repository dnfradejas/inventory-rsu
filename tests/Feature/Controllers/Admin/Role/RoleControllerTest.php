<?php

namespace Tests\Feature\Controllers\Admin\Role;


use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;

class RoleControllerTest extends TestCase
{


    /**
     * @dataProvider data
     */
    public function testCreateRole(array $data)
    {
        $this->json('POST', route('admin.role.post.create'), $data);

        $this->assertDatabaseHas('roles', [
            'name' => 'Superadmin',
            'slug' => 'superadmin'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testUpdateRole(array $data)
    {
        Role::factory()->create();
        $data['id'] = 1;
        $this->json('POST', route('admin.role.post.create'), $data);

        $this->assertDatabaseHas('roles', [
            'name' => 'Superadmin',
            'slug' => 'superadmin'
        ]);
        $this->assertEquals(1, Role::count());
    }

    /**
     * @dataProvider permission_role
     */
    public function testAttachRolesAndPermissions(array $permission_role)
    {
        Role::factory()->create();
        Permission::factory()->create();

        $this->json('POST', route('admin.post.attach.role.permission'), $permission_role);
        $this->assertDatabaseHas('permission_role', [
            'role_id' => 1,
            'permission_id' => 1,
        ]);

    }

    public function permission_role()
    {
        return [
            array(
                array(
                    'id' => 1,
                    'permissions' => [
                        1
                    ]
                )
            )
        ];
    }

    public function data()
    {
        return [
            array(
                array(
                    'name' => 'Superadmin'
                )
            )
        ];
    }
}