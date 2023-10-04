<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::find(3)->permissions()->attach([1,2,3]);
        Role::find(2)->permissions()->attach([4,5,6,7,8,9,10,11,12,13,14]);
    }
}
