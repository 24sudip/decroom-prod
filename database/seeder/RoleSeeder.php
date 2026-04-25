<?php
namespace Database\Seeders;

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPermissions = Permission::select('id')->get();

        //Create Admin Roll
        Role::updateOrCreate([
            'role_name'    => 'Admin',
            'role_slug'    => 'admin',
            'role_note'    => 'Admin Has All Permission',
            'is_deletable' => false,
        ])->permissions()->sync($adminPermissions->pluck('id'));

        //Create User Roll
        Role::updateOrCreate([
            'role_name'    => 'User',
            'role_slug'    => 'user',
            'role_note'    => 'User Has Limited Permission',
            'is_deletable' => true,
        ]);
    }
}
