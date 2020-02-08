<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{   
    /**
    View Main file
    @param: OBJECT $request
    @return Blade template
    */
    public function index(Request $request)
    {
        return view('index');
    }

    /**
    Get all tasks
    @param: OBJECT $request
    @return Blade template
    */
    public function getTasks(Request $request)
    {
        $request->validate([
            'project_id' => 'required'
        ]);

        $project = Project::find($request->get('project_id'));

        if ($project) {
            $tasks = $project->tasks;
        } else {
            $tasks = [];
        }

        return view('tasks', compact('project', 'tasks'));
    }

    /**
    Save Task Data
    @param: OBJECT $request
    @return: Void
    */
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
        ]);

        $type = $request->get('type');

        if ($type == 'edit') {
            $item = Task::find($request->get('id'));
            if ($item) {
                $item->name = $request->get('name');
                $item->save();
            }
        } elseif ($type == 'new') {
            $max = Task::max('priority');
            $item = new Task();
            $item->name = $request->get('name');
            $item->project_id = $request->get('project_id');

            if ($max) {
                $item->priority = $max + 1;
            } else {
                $item->priority = 0;
            }

            $item->save();
        }
    }

    /**
    Save Task Data
    @param: OBJECT $request
    @return: JSON
    */
    public function sort(Request $request)
    {
        parse_str($request->get('items'), $items);

        $i = 1;
        if (array_key_exists('task', $items)) {
            foreach ($items['task'] as $item) {
                $task = Task::find($item);
                if ($task) {
                    $task->priority = $i;
                    $task->save();
                    $i++;
                }
            }
        }

        return response()->json([
            'result' => 1,
            'message' => 'Item moved successfully!'
        ]);
    }

    /**
    Delete Task Data
    @param: OBJECT $request
    @return: JSON
    */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $item = Task::find($request->get('id'));
        if ($item) {
            $item->delete();
        }

        return response()->json([
            'result' => 1,
            'message' => 'Item deleted successfully!'
        ]);
    }
    /**
    Restore Task Data
    @param: OBJECT $request
    @return: JSON
    */
    public function restore(Request $request)
    {
        $item = Task::withTrashed()->where('id', $request->get('id'));
        if ($item) {
            $item->restore();
        }

        return response()->json([
            'result' => 1,
            'message' => 'Item restored successfully!'
        ]);
    }
}
