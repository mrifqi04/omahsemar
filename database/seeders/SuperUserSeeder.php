<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenantId = tenant('id');

        $user = User::updateOrCreate(
            ['email' => "admin@{$tenantId}.test"], // kunci unik
            [
                'name' => 'Admin ' . ucfirst($tenantId),
                'password' => Hash::make('12345678'),
                'is_active' => 1,
            ]
        );

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $user->assignRole($superAdmin);
    }
}
