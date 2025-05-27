<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TimeLogResource;

class TimeLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $timeLogs = TimeLog::whereHas('project.client', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with('project')->get();

        return response()->json(TimeLogResource::collection($timeLogs), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'tag' => 'nullable|in:billable,non-billable',
        ]);

        $project = Project::where('id', $data['project_id'])
            ->whereHas('client', fn($q) => $q->where('user_id', $request->user()->id))
            ->firstOrFail();

        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $data['hours'] = round(($end - $start) / 3600, 2);

        $timeLog = $project->timeLogs()->create($data);
        $timeLog->load('project');

        return response()->json(new TimeLogResource($timeLog), 201);
    }

    public function show(Request $request, TimeLog $timeLog): JsonResponse
    {
        $this->authorizeTimeLog($request, $timeLog);
        $timeLog->load('project');

        return response()->json(new TimeLogResource($timeLog), 200);
    }

    public function update(Request $request, TimeLog $timeLog): JsonResponse
    {
        $this->authorizeTimeLog($request, $timeLog);

        $data = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'tag' => 'nullable|in:billable,non-billable',
        ]);

        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $data['hours'] = round(($end - $start) / 3600, 2);

        $timeLog->update($data);
        $timeLog->load('project');

        return response()->json(new TimeLogResource($timeLog), 200);
    }

    public function destroy(Request $request, TimeLog $timeLog): JsonResponse
    {
        $this->authorizeTimeLog($request, $timeLog);
        $timeLog->delete();

        return response()->json(null, 204);
    }

    private function authorizeTimeLog(Request $request, TimeLog $timeLog): void
    {
        $ownerId = $timeLog->project->client->user_id ?? null;

        if ($ownerId !== $request->user()->id) {
            abort(403, 'Unauthorized to access this time log.');
        }
    }
}
