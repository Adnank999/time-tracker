<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Time Log Duration Alert</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h1 { color: #cc0000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Time Log Duration Alert</h1>

    <p>Hi {{ $log->project->freelancer->name ?? 'Freelancer' }},</p>

    <p>
        You have a time log entry that is <strong>{{ number_format($log->hours, 2) }} hours long</strong>, 
        which meets or exceeds the allowed daily limit of 8 hours.
    </p>

    <h3>Details:</h3>
    <table>
        <tr><th>Log ID</th><td>{{ $log->id }}</td></tr>
        <tr><th>Project</th><td>{{ $log->project->title }}</td></tr>
        <tr><th>Start Time</th><td>{{ $log->start_time }}</td></tr>
        <tr><th>End Time</th><td>{{ $log->end_time }}</td></tr>
        <tr><th>Logged Hours</th><td>{{ number_format($log->hours, 2) }}</td></tr>
    </table>

    <p>Please review this log for accuracy.</p>

    
</body>
</html>
