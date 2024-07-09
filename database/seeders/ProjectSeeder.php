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
                'job_name' => 'Project 1',
                'start_date' => '2024-06-30',
                'deadline' => '2024-07-30',
                'job_description' => 'Project 1 content',
                'status' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'job_name' => 'Project 2',
                'start_date' => '2024-06-30',
                'deadline' => '2024-07-30',
                'job_description' => 'Project 2 content',
                'status' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'job_name' => 'Project 3',
                'start_date' => '2024-06-30',
                'deadline' => '2024-07-30',
                'job_description' => 'Project 3 content',
                'status' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
            ],
        ];

        Project::insert($projects);

        Project::each(function ($project) {
            $project->users()->attach([1, 2]);
        });
    }
}
