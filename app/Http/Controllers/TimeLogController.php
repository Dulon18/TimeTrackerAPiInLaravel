<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TimeLog;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
class TimeLogController extends Controller
{

    protected ResponseService  $response;
    public function __construct(ResponseService  $response)
    {
        $this->response = $response;
    }
   public function start($projectId)
   {
        try {
            $projectIdExist = Project::where('id',$projectId)->exists();
            if(!$projectIdExist)
            {
                $errors = 'The specified project ID is invalid.';
                return $this->response->validationError($errors);
            }
            $userID = Auth::user()->id;
            $existing = TimeLog::where('user_id', $userID)
                               ->whereNull('end_time')
                               ->first();

            if ($existing) {
                $message = 'You already have a running timer. Finish or Stop that';
                return $this->response->errorResponse($message,$existing, 400);
            }
            $project = Project::find($projectId);
            $title = $project->title;
            $timeLog = TimeLog::create([
                'user_id' => $userID,
                'project_id' => $projectId,
                'description' => $title.' has been started.',
                'start_time' => now(),
            ]);
            $data = [
                'user' => $timeLog->user->name,
                'project' => $timeLog->project->title,
                'start_time' => $timeLog->start_time,
            ];
            return $this->response->successResponse($data, 'Timer started..');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getMessage(), 500);
        }
   }
    //stop function start
    public function stop($projectId)
    {
        try {
            $projectIdExist = Project::where('id',$projectId)->exists();

            if(!$projectIdExist)
            {
                $errors = 'The specified project ID is invalid.';
                return $this->response->validationError($errors);
            }
            $userID = Auth::user()->id;

            $timeLog = TimeLog::where('user_id', $userID)
                            ->where('project_id',$projectId)
                            ->whereNull('end_time')
                            ->latest()
                            ->first();

            if (!$timeLog) {
                $message = 'No active timer found. Please make sure you have started a timer for a valid project.';
                return $this->response->errorResponse($message,404);
            }

            $endTime = Carbon::now();
            $startTime = Carbon::parse($timeLog->start_time);

            $diffInSeconds = $startTime->diffInSeconds($endTime);
            $decimalHours = $diffInSeconds / 3600;

            $hours = floor($diffInSeconds / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);
            $seconds = $diffInSeconds % 60;

            $formattedDuration = trim(sprintf(
                '%s%s%s',
                $hours ? $hours . ' hr ' : '',
                $minutes ? $minutes . ' min ' : '',
                $seconds ? $seconds . ' s' : ''
            ));
            $project = Project::find($projectId);
            $title = $project->title;
            $timeLog->description = $title.' has been stoped.';
            $timeLog->end_time = $endTime;
            $timeLog->hours = $decimalHours;
            $timeLog->save();

            $data = [
                'user' => $timeLog->user->name,
                'project' => $timeLog->project->title,
                'start_time' => $timeLog->start_time,
                'end_time'=>$timeLog->end_time,
                'hour'=>$formattedDuration,
            ];
        return $this->response->successResponse($data, 'Timer stopped.');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getMessage(),$th->getTrace(), 500);
        }
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'from' => ['required', 'date', 'date_format:Y-m-d'],
            'to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

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

        $logs = $logsQuery->whereBetween('start_time', [$from, $to])->get();

        $clientName = $logs->first()?->project->client->name ?? 'Unknown';

        $pdf = Pdf::loadView('logs.report', [
            'logs' => $logs,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'clientName' => $clientName,
        ]);
        $filename = 'TimeLogReport_' . $from->toDateString() . '_to_' . $to->toDateString() . '.pdf';
        return $pdf->download($filename);
    }
}
