<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);

        $editArticlesPermission = Permission::create(['name' => 'edit articles']);
        $deleteArticlesPermission = Permission::create(['name' => 'delete articles']);

        $adminRole->givePermissionTo($editArticlesPermission);
        $adminRole->givePermissionTo($deleteArticlesPermission);

        $editorRole->givePermissionTo($editArticlesPermission);
    }
}
