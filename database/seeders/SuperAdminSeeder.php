<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($count)
    {
        \App\Models\User::factory($count)->withSuperAdmin()->create();
    }
}