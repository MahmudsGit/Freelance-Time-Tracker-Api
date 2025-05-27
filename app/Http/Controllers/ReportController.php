<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'client_id' => 'nullable|exists:clients,id'
        ]);

        $query = TimeLog::with(['project.client'])
            ->whereBetween('start_time', [$request->from, $request->to]);

        if ($request->client_id) {
            $query->whereHas('project.client', function ($q) use ($request) {
                $q->where('id', $request->client_id);
            });
        }

        $logs = $query->get();

        $totalPerProject = $logs->groupBy('project_id')->map(function ($group) {
            return [
                'project_title' => $group->first()->project->title,
                'total_hours' => $group->sum('hours'),
            ];
        })->values();

        $totalPerDay = $logs->groupBy(function ($log) {
            return \Carbon\Carbon::parse($log->start_time)->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('hours');
        });

        $totalPerClient = $logs->groupBy(function ($log) {
            return $log->project->client->id;
        })->map(function ($group) {
            return [
                'client_name' => $group->first()->project->client->name,
                'total_hours' => $group->sum('hours'),
            ];
        })->values();

        return response()->json([
            'per_project' => $totalPerProject,
            'per_day' => $totalPerDay,
            'per_client' => $totalPerClient,
        ]);
    }
}
