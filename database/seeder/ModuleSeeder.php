<?php
namespace Database\Seeders;

use App\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleArray = [
            'Admin Dashboard',
            'Role Management',
            'User Management',
            'Permission Management',
        ];

        foreach ($moduleArray as $module) {
            Module::updateOrCreate([
                'module_name' => $module,
                'module_slug' => Str::slug($module),
            ]);
        }
    }
}
