<?php
namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('role_slug', 'admin')->first()->id;

        User::updateOrCreate([
            'role_id'           => $adminRoleId,
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('1234'), // password
            'remember_token'    => Str::random(10),
        ]);

        $userRoleId = Role::where('role_slug', 'user')->first()->id;

        User::updateOrCreate([
            'role_id'           => $userRoleId,
            'name'              => 'User',
            'email'             => 'user@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('1234'), // password
            'remember_token'    => Str::random(10),
        ]);
    }
}
