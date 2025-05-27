<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $projects = $request->user()->projects()->with('client')->get();

        return response()->json(ProjectResource::collection($projects), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required',
            'description' => 'sometimes',
            'status' => 'required|in:active,completed',
            'deadline' => 'required|date',
        ]);

        $client = $request->user()->clients()->findOrFail($data['client_id']);

        $project = $client->projects()->create($data);
        $project->load('client');

        return response()->json(new ProjectResource($project), 201);
    }

    public function show(Request $request, Project $project): JsonResponse
    {
        $this->authorizeProject($request, $project);

        $project->load('client');

        return response()->json(new ProjectResource($project), 200);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $this->authorizeProject($request, $project);

        $project->update($request->only(['title', 'description', 'status', 'deadline']));
        $project->load('client');

        return response()->json(new ProjectResource($project), 200);
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        $this->authorizeProject($request, $project);

        $project->delete();

        return response()->json(null, 204);
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        if ($request->user()->id !== $project->client->user_id) {
            abort(403, 'Unauthorized access to this project.');
        }
    }
}
