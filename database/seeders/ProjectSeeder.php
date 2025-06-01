<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::role('client')->get();
        $freelancers = User::role('freelancer')->get();

        
        for ($i = 0; $i < 5; $i++) {
            Project::create([
                'client_id' => $clients->random()->id,
                'freelancer_id' => $freelancers->random()->id,
                'title' => fake()->sentence,
                'description' => fake()->paragraph,
                'status' => ['active', 'completed'][rand(0, 1)],
                'deadline' => now()->addDays(rand(5, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
