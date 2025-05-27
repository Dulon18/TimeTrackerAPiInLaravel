<?php

namespace Database\Seeders;

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
        $projects = [
            [
                'title'       => 'Website Redesign',
                'description' => 'Revamp the client website with modern UI/UX.',
                'client_id'   => 1,
                'status'      => 'active',
                'deadline'    => now()->addDays(30),
            ],
            [
                'title'       => 'Mobile App Development',
                'description' => 'Build a cross-platform mobile app for the customer portal.',
                'client_id'   => 2,
                'status'      => 'completed',
                'deadline'    => now()->subDays(10),
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['title' => $project['title']],
                $project
            );
        }

        $this->command->info('Projects table seeded successfully!');
    }
}
