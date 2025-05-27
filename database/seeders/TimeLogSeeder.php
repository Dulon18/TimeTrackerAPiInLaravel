<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeLog;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;

class TimeLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $project = Project::first();

        if (!$user || !$project) {
            $this->command->warn('No user or project found. Seed those first.');
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $start = Carbon::now()->subDays(rand(1, 10))->setTime(rand(8, 10), 0);
            $end = (clone $start)->addHours(rand(1, 4));
            $hours = $start->diffInMinutes($end) / 60;

            TimeLog::create([
                'user_id'     => $user->id,
                'project_id'  => $project->id,
                'start_time'  => $start,
                'end_time'    => $end,
                'hours'       => $hours,
                'description' => 'Seeded work log #' . ($i + 1),
            ]);
        }
        $this->command->info('Time log table seeded successfully!');
    }
}

