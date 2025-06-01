<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Time Log Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            padding: 20px;
        }
        h2 {
            font-size: 3rem;
            font-weight: 600;
            color: #1f2937;
        }
        p {
            font-size: 2rem;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 10px;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .tag {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }
        .billable {
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 15px;
        }
        .non-billable {
            background-color: #fee2e2;
            color: #991b1b;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <h2>Time Log Report</h2>
    <p><strong>Total Hours:</strong> {{ $totalHours }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Client</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->project->title }}</td>
                    <td>{{ $log->project->client->name ?? 'n/a' }}</td>
                    <td>{{ $log->start_time }}</td>
                    <td>{{ $log->end_time }}</td>
                    <td>{{ $log->hours }}</td>
                    <td>
                        @if ($log->billable)
                            <span class="tag billable">Billable</span>
                        @else
                            <span class="tag non-billable">Non-Billable</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
