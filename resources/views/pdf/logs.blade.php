<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Time Logs</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Time Logs Report</h2>
    <table>
        <thead>
            <tr>
                <th>Project</th>
                <th>Client</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hours</th>
                <th>Tag</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->project->title ?? 'N/A' }}</td>
                    <td>{{ $log->project->client->name ?? 'N/A' }}</td>
                    <td>{{ $log->start_time }}</td>
                    <td>{{ $log->end_time }}</td>
                    <td>{{ $log->hours }}</td>
                    <td>{{ ucfirst($log->tag ?? '-') }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
