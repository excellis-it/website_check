<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UrlActivityLog;
use App\Models\UrlManagement;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base URL query
        $urlQuery = UrlManagement::query();

        // If NOT admin → filter by assigned users
        if (!$user->hasRole('ADMIN')) {
            $urlQuery->whereHas('assignedUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $data = [
            'total_urls' => 0,
            'active_urls' => 0,
            'down_urls' => 0,
            'total_checks' => 0,
            'total_users' => 0,
            'recent_logs' => collect([]),
            'url_status_counts' => ['up' => 0, 'down' => 0],
            'today_stats' => [
                'checks' => 0,
                'up' => 0,
                'down' => 0,
                'avg_response' => 0,
            ],
        ];

        // URL & Log statistics
        if ($user->can('viewAny', UrlManagement::class) || $user->hasRole('Admin')) {

            $data['total_urls']  = (clone $urlQuery)->count();
            $data['active_urls'] = (clone $urlQuery)->where('status', 'active')->count();
            $data['down_urls']   = (clone $urlQuery)->where('status', 'down')->count();

            // Logs (filtered by user's URLs)
            $logQuery = UrlActivityLog::whereIn(
                'url_id',
                $urlQuery->pluck('id')
            );

            $data['total_checks'] = $logQuery->count();

            $data['recent_logs'] = $logQuery
                ->with('url')
                ->latest('checked_at')
                ->take(6)
                ->get();

            // Chart counts
            $data['url_status_counts'] = [
                'up'   => (clone $logQuery)->where('status', 'up')->count(),
                'down' => (clone $logQuery)->where('status', 'down')->count(),
            ];

            // Today stats
            $todayLogs = (clone $logQuery)->whereDate('log_date', today());

            $data['today_stats'] = [
                'checks' => $todayLogs->count(),
                'up' => (clone $todayLogs)->where('status', 'up')->count(),
                'down' => (clone $todayLogs)->where('status', 'down')->count(),
                'avg_response' => round($todayLogs->avg('response_time') ?? 0, 2),
            ];
        }

        // User stats (Admin only)
        if ($user->can('viewAny', User::class) || $user->hasRole('Admin')) {
            $data['total_users'] = User::where('status', 1)->count();
        }

        return view('admin.dashboard', compact('data'));
    }
}
