<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Hitung aktivitas hari ini dan minggu ini
        $todayLogs = $user->workLogs()->whereDate('worked_at', Carbon::today())->count();
        $weekLogs = $user->workLogs()->whereBetween('worked_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        // Total jam kerja (konversi dari menit)
        $totalMinutes = $user->workLogs()->sum('duration_minutes');
        $totalHours = round($totalMinutes / 60, 1);

        return response()->json([
            'stats' => [
                'today_activities' => $todayLogs,
                'weekly_activities' => $weekLogs,
                'total_hours_logged' => $totalHours,
                'active_projects' => $user->projects()->where('status', 'ongoing')->count(),
            ],
            'recent_work_logs' => $user->workLogs()->with('project')->latest('worked_at')->take(5)->get(),
            'recent_notes' => $user->knowledgeNotes()->latest()->take(5)->get(),
        ]);
    }
}
