<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request) {
        $projects = Project::all();
        $tasks = Task::query();

        if ($request->has('project_id') && $request->project_id !== '') {
            $tasks->where('project_id', $request->project_id);
        }

        $tasks = $tasks->orderBy('priority')->get();

        return view('tasks.index', [
            'projects' => $projects,
            'tasks' => $tasks,
        ]);
    }

    public function create() {
        $projects = Project::all();
        return view('tasks.create', ['projects' => $projects]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $priority = Task::where('project_id', $request->project_id)->max('priority') + 1;
        Task::create(array_merge($request->all(), ['priority' => $priority]));

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task) {
        $projects = Project::all();
        return view('tasks.edit', [
            'task' => $task,
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, Task $task) {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task->update($request->all());
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task) {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function reorder(Request $request) {
        foreach ($request->tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }
        return redirect()->route('tasks.index');
    }
}


