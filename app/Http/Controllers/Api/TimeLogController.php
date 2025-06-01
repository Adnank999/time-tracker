<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendLogDurationAlertJob;
use App\Models\Project;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $logs = TimeLog::with('project')
            ->whereHas('project', function ($q) use ($user) {
                $q->where('freelancer_id', $user->id);
            })
            ->when($request->filled('client_id'), function ($q) use ($request) {
                $q->whereHas('project', fn($p) => $p->where('client_id', $request->client_id));
            })
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('from'), fn($q) => $q->where('start_time', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->where('start_time', '<=', $request->to))
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json($logs);
    }

  
    public function start(Request $request)
    {

        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $userFreelancer = auth()->user()->hasRole('freelancer');

        if (!$userFreelancer) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        $user = auth()->user();

         $projectAssignedFreelancer = Project::where('id', $request->project_id)
            ->where('freelancer_id', $user->id)
            ->first();

        if (!$projectAssignedFreelancer) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }


        $existing = TimeLog::where('project_id', $request->project_id)
            ->whereNull('end_time')
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Log already running.'], 409);
        }



        $log = TimeLog::create([
            'project_id' => $request->project_id,
            'start_time' => now(),
        ]);

   

        return response()->json($log, 201);
    }


    public function stop($id)
    {
        $log = TimeLog::findOrFail($id);

        if ($log->end_time) {
            return response()->json(['error' => 'Log already ended.'], 409);
        }

        $projectId = $log->project_id;

        $projectAssignedFreelancer = Project::where('id', $projectId)
            ->where('freelancer_id', auth()->user()->id)
            ->first();

        if (!$projectAssignedFreelancer) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        $end_time = Carbon::parse(now());



        $log->end_time = $end_time;

        $startTime = strtotime($log->start_time);
        $endTime = strtotime($end_time);



        $difference = $endTime - $startTime;
        $hours = $difference / 3600;

        $log->hours = $hours;

        if($hours >= 8){
             SendLogDurationAlertJob::dispatch($log);
        }
        $log->save();

        return response()->json($log);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string'
        ]);

        $startTime = strtotime($data['start_time']);
        $endTime = strtotime($data['end_time']);

        $difference = $endTime - $startTime;
        $hours = $difference / 3600;


        $data['hours'] = $hours;

        $log = TimeLog::create($data);
        return response()->json($log, 201);
    }




    public function update(Request $request, TimeLog $log)
    {
        $data = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string'
        ]);

        $data['hours'] = now()->parse($data['end_time'])->diffInMinutes(now()->parse($data['start_time'])) / 60;

        $log->update($data);
        return response()->json($log);
    }
}
