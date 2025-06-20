<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::factory()->count(2)->create()->each(function ($user) {
            $user->assignRole('freelancer');
        });

        
         User::factory()->count(3)->create()->each(function ($user) {
            $user->assignRole('client');
        });
    }
}
