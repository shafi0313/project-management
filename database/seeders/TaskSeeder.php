<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'project_id' => 1,
                'name' => 'Task 1',
                'start_date' => '2024-07-03',
                'due_date' => '2024-07-03',
                'priority' => 'low',
                'status' => 1,
                'content' => 'Task 1 content',
                'created_by' => 1,
            ],
        ];
        Task::insert($tasks);

        Task::each(function ($task) {
            $task->users()->attach([1, 2]);
        });

    }
}
