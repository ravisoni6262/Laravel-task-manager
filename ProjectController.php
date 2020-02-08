<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $items = Project::get();

        return view('projects', compact('items'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
        ]);

        $type = $request->get('type');

        if ($type == 'edit') {
            $item = Project::find($request->get('id'));
            if ($item) {
                $item->name = $request->get('name');
                $item->save();
            }
        } elseif ($type == 'new') {
            $item = new Project();
            $item->name = $request->get('name');
            $item->save();
        }
    }

    public function delete(Request $request)
    {
        $item = Project::find($request->get('id'));
        if ($item) {
            $item->delete();
        }

        return response()->json([
            'result' => 1,
            'message' => 'Item deleted successfully!'
        ]);
    }

    public function restore(Request $request)
    {
        $item = Project::withTrashed()->where('id', $request->get('id'));
        if ($item) {
            $item->restore();
        }

        return response()->json([
            'result' => 1,
            'message' => 'Item restored successfully!'
        ]);
    }

    public function select(Request $request)
    {
        $item = Project::find($request->get('id'));

        $projects = Project::get();

        foreach ($projects as $project) {
            $project->selected = false;
            $project->save();
        }

        if ($item) {
            $item->selected = true;
            $item->save();
        }

        return response()->json([
            'result' => 1,
            'id' => $item->id,
            'name' => $item->name,
            'message' => 'Item selected successfully!'
        ]);
    }

}
