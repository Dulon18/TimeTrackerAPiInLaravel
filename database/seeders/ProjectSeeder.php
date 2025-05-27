<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Seed clients first
        $client1 = Client::firstOrCreate(
            ['email' => 'clienta@example.com'],
            ['name' => 'Client A', 'contact_person' => 'Alice']
        );

        $client2 = Client::firstOrCreate(
            ['email' => 'clientb@example.com'],
            ['name' => 'Client B', 'contact_person' => 'Bob']
        );

        $projects = [
            [
                'title'       => 'Website Redesign',
                'description' => 'Revamp the client website with modern UI/UX.',
                'client_id'   => $client1->id,
                'status'      => 'active',
                'deadline'    => now()->addDays(30),
            ],
            [
                'title'       => 'Mobile App Development',
                'description' => 'Build a cross-platform mobile app.',
                'client_id'   => $client2->id,
                'status'      => 'completed',
                'deadline'    => now()->subDays(10),
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['title' => $project['title']], $project);
        }

        $this->command->info('Projects table seeded successfully!');
    }
}
