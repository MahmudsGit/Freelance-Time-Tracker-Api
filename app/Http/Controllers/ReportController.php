<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $user = $request->user();

        $timeLogs = TimeLog::query()
            ->whereHas('project.client', function ($q) use ($user, $request) {
                $q->where('user_id', $user->id);

                if ($request->filled('client_id')) {
                    $q->where('id', $request->client_id);
                }
            })
            ->when($request->filled('project_id'), function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('start_time', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('end_time', '<=', $request->to);
            });

        // Grouped totals
        $perProject = (clone $timeLogs)
            ->select('project_id', DB::raw('SUM(hours) as total_hours'))
            ->groupBy('project_id')
            ->with('project')
            ->get();

        $perDay = (clone $timeLogs)
            ->select(DB::raw('DATE(start_time) as date'), DB::raw('SUM(hours) as total_hours'))
            ->groupBy(DB::raw('DATE(start_time)'))
            ->orderBy('date')
            ->get();

        $perClient = (clone $timeLogs)
            ->join('projects', 'time_logs.project_id', '=', 'projects.id')
            ->join('clients', 'projects.client_id', '=', 'clients.id')
            ->select('clients.id as client_id', 'clients.name', DB::raw('SUM(time_logs.hours) as total_hours'))
            ->groupBy('clients.id', 'clients.name')
            ->get();

        return response()->json([
            'per_project' => $perProject,
            'per_day' => $perDay,
            'per_client' => $perClient,
        ], 200);
    }

    public function export(Request $request)
    {
        $logs = TimeLog::whereHas('project.client', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('project.client')->get();

        $pdf = Pdf::loadView('pdf.logs', compact('logs'));

        return $pdf->download('timelogs.pdf');
    }
}
