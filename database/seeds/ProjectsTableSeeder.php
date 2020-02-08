<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Project::class, 3)->create()->each(function ($project) {
            factory(App\Task::class, 5)->make()->each(function ($task, $key) use ($project) {
                $task->project_id = $project->id;
                $task->priority = $key + 1;
                $task->save();
            });
        });
    }
}
