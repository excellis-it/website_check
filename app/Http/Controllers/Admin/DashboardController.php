<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'total_urls' => 0,
            'active_urls' => 0,
            'down_urls' => 0,
            'total_checks' => 0,
            'total_users' => 0,
            'recent_logs' => collect([]),
            'url_status_counts' => ['up' => 0, 'down' => 0],
        ];

        // URL Statistics
        if ($user->can('viewAny', \App\Models\UrlManagement::class) || $user->hasRole('Admin')) {
            $data['total_urls'] = \App\Models\UrlManagement::count();
            $data['active_urls'] = \App\Models\UrlManagement::where('status', 'active')->count();
            $data['down_urls'] = \App\Models\UrlManagement::where('status', 'down')->count();

            // Checks Stats
            $data['total_checks'] = \App\Models\UrlActivityLog::count();
            $data['recent_logs'] = \App\Models\UrlActivityLog::with('url')->latest('checked_at')->take(6)->get();

            // For Charts
            $data['url_status_counts'] = [
                'up' => \App\Models\UrlActivityLog::where('status', 'up')->count(),
                'down' => \App\Models\UrlActivityLog::where('status', 'down')->count(),
            ];

            // Today's Stats
            $data['today_stats'] = [
                'checks' => \App\Models\UrlActivityLog::whereDate('log_date', today())->count(),
                'up' => \App\Models\UrlActivityLog::whereDate('log_date', today())->where('status', 'up')->count(),
                'down' => \App\Models\UrlActivityLog::whereDate('log_date', today())->where('status', 'down')->count(),
                'avg_response' => round(\App\Models\UrlActivityLog::whereDate('log_date', today())->avg('response_time') ?? 0, 2),
            ];
        }

        // User Statistics (Admin only usually)
        if ($user->can('viewAny', \App\Models\User::class) || $user->hasRole('Admin')) {
            $data['total_users'] = \App\Models\User::where('status', 1)->count(); // Active users
        }

        return view('admin.dashboard', compact('data'));
    }
}
