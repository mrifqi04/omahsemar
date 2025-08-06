<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantAdminSeeder extends Seeder
{
    public function run()
    {
        $tenantId = tenant('id');

        User::firstOrCreate(
            ['email' => "admin@{$tenantId}.test"],
            [
                'name' => ucfirst($tenantId) . ' Admin',
                'password' => Hash::make('password'),
                'is_active' => 1,
            ]
        );
    }
}
