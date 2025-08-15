<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = json_decode(File::get("database/data/permissions.json"));

        $admin = User::create([
            "name" => 'store',
            'email' => 'store@ajza.net',
            'full_mobile' => '+966553275003',
            'password' => '1Alqarawi1',
            'is_active' => true,
            'is_registered' => true,
        ]);

        $admin->assignRole(RoleEnum::ADMIN);

        foreach ($permissions as $permission) {
            $admin->syncPermissions([$permission->name]);
        }
    }
}
