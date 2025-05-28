<!DOCTYPE html>
<html>
<head>
    <title>Time Log Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th{ background-color: #f8a0a0; }
        h2{text-align: center}

    </style>
</head>
<body>
    <h2>Time Log Report</h2>
    <p><strong>Client:</strong> {{ $clientName }}</p>
    <p><strong>Date Range:</strong> {{ $from }} to {{ $to }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Project</th>
                <th>Description</th>
                <th>Start</th>
                <th>End</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->project->title }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->start_time->format('Y-m-d H:i') }}</td>
                    <td>{{ $log->end_time->format('Y-m-d H:i') }}</td>
                    <td>{{ $log->hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
