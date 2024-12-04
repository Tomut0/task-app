<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some users and tasks
        $users = User::factory(20)->create();
        $tasks = Task::factory(50)->create();

        // Create some users and tasks
        $users = User::factory(20)->create();
        $tasks = Task::factory(50)->create();

        // Associate tasks with unique users
        foreach ($tasks as $task) {
            // Ensure no more users than exist
            $uniqueUsers = $users->random(min(10, $users->count()));
            foreach ($uniqueUsers as $user) {
                TaskUser::factory()->create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
