<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        return TimeLog::with('project')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'tag' => 'in:billable,non-billable',
        ]);

        // Calculate hours
        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $data['hours'] = round(($end - $start) / 3600, 2);

        return TimeLog::create($data);
    }

    public function show(TimeLog $timeLog)
    {
        return $timeLog;
    }

    public function update(Request $request, TimeLog $timeLog)
    {
        $data = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'tag' => 'in:billable,non-billable',
        ]);

        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $data['hours'] = round(($end - $start) / 3600, 2);

        $timeLog->update($data);

        return $timeLog;
    }

    public function destroy(TimeLog $timeLog)
    {
        $timeLog->delete();

        return response()->noContent();
    }

    public function export(Request $request)
    {
        $logs = TimeLog::with('project.client')->get();
        $pdf = Pdf::loadView('pdf.logs', compact('logs'));

        return $pdf->download('timelogs.pdf');
    }
}
