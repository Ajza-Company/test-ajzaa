<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = json_decode(File::get("database/data/roles.json"));

        foreach ($roles as $value) {
            Role::create([
                "name" => $value->name,
                "guard_name" => $value->guard_name
            ]);
        }
    }
}
