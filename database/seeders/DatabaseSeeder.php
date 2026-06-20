<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $superAdmin = User::create([
            'name' => 'NNT',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        $clinicHanoi = 1;
        $clinicHCM   = 2;

        setPermissionsTeamId($clinicHanoi);
        $superAdmin->assignRole('admin');

        setPermissionsTeamId($clinicHCM);
        $superAdmin->assignRole('admin');

        setPermissionsTeamId(null);
    }
}
