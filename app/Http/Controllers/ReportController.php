<?php

namespace App\Http\Controllers;

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
            'client_id' => 'required|exists:clients,id',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        dd($request->all());

        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $logs = TimeLog::with(['project.client'])
            ->whereHas('project', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            })
            ->whereBetween('start_time', [$from, $to])
            ->get();

        return $this->response->successResponse($logs, 'Report from ' . $from->toDateString() . ' to ' . $to->toDateString());
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

