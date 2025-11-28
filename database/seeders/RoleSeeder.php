<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin' => [
                'access_dashboard',
                'manage_categories',
                'manage_posts',
                'manage_permissions',
                'manage_roles',
                'manage_users',
            ],
            'blogger' => [
                'access_dashboard',
                'manage_posts',
            ],

        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }


    }
}
