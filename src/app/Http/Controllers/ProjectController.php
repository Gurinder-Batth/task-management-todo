<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::paginate(10);
        return view('projects.index')->with(['projects' => $projects]);
    }

    public function create() {
        return view('projects.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Project::create($request->all());
        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function edit(Project $project) {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project) {
        $request->validate(['name' => 'required|string|max:255']);

        $project->update($request->only(['name']));

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project) {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
