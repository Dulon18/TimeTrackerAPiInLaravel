<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeLogController extends Controller
{

    protected ResponseService  $response;
    public function __construct(ResponseService  $response)
    {
        $this->response = $response;
    }
   public function start(Request $request)
   {
        try {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
            ]);
            $userID = Auth::user()->id;
            $existing = TimeLog::where('user_id', $userID)
                               ->whereNull('end_time')
                               ->first();

            if ($existing) {
                $message = 'You already have a running timer.';
                return $this->response->errorResponse($message, 400);
            }

            $timeLog = TimeLog::create([
                'user_id' => $userID,
                'project_id' => $request->project_id,
                'start_time' => now(),
            ]);
            return $this->response->successResponse($timeLog, 'Timer started..');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getMessage(), 500);
        }
   }
    //stop function start
    public function stop(Request $request)
    {
        try {
        $userID = Auth::user()->id;
        $timeLog = TimeLog::where('user_id', $userID)
                        ->whereNull('end_time')
                        ->latest()
                        ->first();

        if (!$timeLog) {
            $message = 'No running timer found.';
            return $this->response->errorResponse($message, 404);
        }

        $timeLog->end_time = now();
        $timeLog->hours = $timeLog->start_time->diffInMinutes($timeLog->end_time) / 60;
        $timeLog->save();

        return $this->response->successResponse($timeLog, 'Timer stopped.');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getMessage(), 500);
        }
    }
}
