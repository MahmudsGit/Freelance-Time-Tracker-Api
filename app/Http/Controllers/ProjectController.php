<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with('client')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:active,completed',
            'deadline' => 'required|date',
        ]);

        return Project::create($data);
    }

    public function show(Project $project)
    {
        return $project;
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->only(['title', 'description', 'status', 'deadline']));

        return $project;
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }
}
