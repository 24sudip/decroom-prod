<?php
namespace Database\Seeders;

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPermissionArray = [
            'Access Dashboard',
        ];

        $rolePermissionArray = [
            'Index Role',
            'Create Role',
            'Update Role',
            'Delete Role',
        ];

        $userPermissionArray = [
            'Index User',
            'Create User',
            'Update User',
            'Delete User',
        ];

        $module = Module::where('module_name', 'Admin Dashboard')->select('id')->first();

        Permission::updateOrCreate([
            'module_id'       => $module->id,
            'permission_name' => $adminPermissionArray[0],
            'permission_slug' => Str::slug($adminPermissionArray[0]),
        ]);

        //Role Permission
        $roleModule = Module::where('module_name', 'Role Management')->select('id')->first();

        for ($i = 0; $i < count($rolePermissionArray); $i++) {
            Permission::updateOrCreate([
                'module_id'       => $roleModule->id,
                'permission_name' => $rolePermissionArray[$i],
                'permission_slug' => Str::slug($rolePermissionArray[$i]),
            ]);
        }

        //User Permission
        $userModule = Module::where('module_name', 'User Management')->select('id')->first();

        for ($i = 0; $i < count($userPermissionArray); $i++) {
            Permission::updateOrCreate([
                'module_id'       => $userModule->id,
                'permission_name' => $userPermissionArray[$i],
                'permission_slug' => Str::slug($userPermissionArray[$i]),
            ]);
        }

    }
}
