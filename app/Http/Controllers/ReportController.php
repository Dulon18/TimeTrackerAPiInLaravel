<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use App\Models\TimeLog;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected ResponseService  $response;
    public function __construct(ResponseService  $response)
    {
        $this->response = $response;
    }
    public function index(Request $request)
{
    $request->validate([
        'client_id' => ['required', 'exists:clients,id'],
        'project_id' => ['nullable', 'exists:projects,id'],
        'from' => ['required', 'date', 'date_format:Y-m-d'],
        'to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
    ]);

    // Check project-client relationship
    if ($request->filled('project_id')) {
        $project = Project::where('id', $request->project_id)
            ->where('client_id', $request->client_id)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'The selected project does not belong to the given client.'
            ], 422);
        }
    }

    $from = Carbon::parse($request->from)->startOfDay();
    $to = Carbon::parse($request->to)->endOfDay();

    $logsQuery = TimeLog::with(['project.client'])
        ->whereHas('project', fn($q) => $q->where('client_id', $request->client_id));

    if ($request->filled('project_id')) {
        $logsQuery->where('project_id', $request->project_id);
    }

    $logs = $logsQuery
        ->whereBetween('start_time', [$from, $to])
        ->get()
        ->map(function ($log) {
            return [
                'log_id' => $log->id,
                'user_id' => $log->user_id,
                'user_name' => $log->user->name,
                'project_id' => $log->project_id,
                'project_title' => $log->project->title,
                'client_name' => $log->project->client->name,
                'start_time' => $log->start_time->format('Y-m-d H:i'),
                'end_time' => $log->end_time->format('Y-m-d H:i'),
                'hours' => $log->hours,
                'description' => $log->description,
            ];
        });

    return $this->response->successResponse($logs, "Report from {$from->toDateString()} to {$to->toDateString()}");
}

    public function summary()
    {
        $userId = Auth::user()->id;

        $todayHours = TimeLog::where('user_id', $userId)
            ->whereDate('start_time', today())
            ->sum('hours');

        $weekHours = TimeLog::where('user_id', $userId)
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('hours');

            $data=[
            'today_hours' => $todayHours,
            'this_week_hours' => $weekHours,
        ];
        return $this->response->successResponse($data, 'Report Summary.');
    }

}

