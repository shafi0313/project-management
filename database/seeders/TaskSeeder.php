<?php

namespace Database\Seeders;

use App\Models\Task;
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
                'task_name' => 'Task 1',
                'task_description' => 'Task 1 content',
                'start_date' => '2024-07-10',
                'deadline' => '2024-07-20',
                'priority' => 'low',
                'status' => 1,
                'created_by' => 1,
            ],
        ];
        Task::insert($tasks);

        Task::each(function ($task) {
            $task->users()->attach([1, 2]);
        });

    }
}
