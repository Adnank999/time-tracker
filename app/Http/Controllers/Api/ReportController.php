<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary(Request $request)
    {
        $userFreelancer = auth()->user()->hasRole('freelancer');

        if (!$userFreelancer) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $user = auth()->user();

        $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from'
        ]);

        $logs = TimeLog::with(['project.client'])
            ->whereHas('project', function ($query) use ($user, $request) {
                $query->where('freelancer_id', $user->id);

                if ($request->filled('client_id')) {
                    $query->where('client_id', $request->client_id);
                }

                if ($request->filled('project_id')) {
                    $query->where('id', $request->project_id);
                }
            });






        if ($request->filled('from') && $request->filled('to')) {
            $logs->whereBetween('start_time', [$request->from, $request->to]);
        }

        $logs = $logs->get();
        $totalHours = $logs->sum('hours');

        if ($request->query('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.summary', [
                'logs' => $logs,
                'totalHours' => $totalHours
            ]);

            return $pdf->download('time_log_report.pdf');
        }




        return response()->json([
            'total_hours' => $logs->sum('hours'),
            'entries' => $logs,
        ]);
    }
}
