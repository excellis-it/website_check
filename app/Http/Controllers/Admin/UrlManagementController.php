<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UrlManagement;
use App\Models\UrlActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', UrlManagement::class);
        $user = Auth::user();

        // Users with manage-urls permission can see all URLs
        if ($user->hasRole('ADMIN')) {
            $urls = UrlManagement::with(['assignedUsers', 'creator'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular users can only see assigned URLs
            $urls = UrlManagement::whereHas('assignedUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with(['assignedUsers', 'creator'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.url_management.list', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admins can create
        $this->authorize('create', UrlManagement::class);

        $users = User::where('status', 1)
            ->where('id', '!=', Auth::id())
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'ADMIN');
            })
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.url_management.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admins can create
        $this->authorize('create', UrlManagement::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Check SSL status
            $sslStatus = parse_url($request->url, PHP_URL_SCHEME) === 'https' ? 'active' : 'inactive';

            $url = UrlManagement::create([
                'name' => $request->name,
                'url' => $request->url,
                'status' => 'inactive',
                'ssl_status' => $sslStatus,
                'created_by' => Auth::id(),
            ]);

            // Assign users if provided
            if ($request->has('assigned_users') && is_array($request->assigned_users)) {
                $url->assignedUsers()->sync($request->assigned_users);
            }

            DB::commit();
            return redirect()->route('url-management.index')
                ->with('message', 'URL created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create URL: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId)
    {
        $id = UrlManagement::decryptId($encryptedId);

        if (!$id) {
            abort(404, 'URL not found');
        }

        $url = UrlManagement::with(['assignedUsers', 'creator'])->findOrFail($id);

        // Check access permissions
        $this->checkAccessPermission($url);

        // Get activity logs for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $activityLogs = $url->activityLogs()
            ->whereDate('log_date', '>=', $startDate)
            ->orderBy('checked_at', 'desc')
            ->paginate(50);

        // Get daily summary for last 30 days
        $dailySummary = UrlActivityLog::where('url_id', $id)
            ->whereDate('log_date', '>=', $startDate)
            ->select(
                'log_date',
                DB::raw('COUNT(*) as total_checks'),
                DB::raw('SUM(CASE WHEN status = "up" THEN 1 ELSE 0 END) as up_count'),
                DB::raw('SUM(CASE WHEN status = "down" THEN 1 ELSE 0 END) as down_count'),
                DB::raw('AVG(response_time) as avg_response_time')
            )
            ->groupBy('log_date')
            ->orderBy('log_date', 'desc')
            ->get();

        $uptimePercentage = $url->getUptimePercentage($startDate);

        return view('admin.url_management.show', compact('url', 'activityLogs', 'dailySummary', 'uptimePercentage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        $id = UrlManagement::decryptId($encryptedId);

        if (!$id) {
            abort(404, 'URL not found');
        }

        $url = UrlManagement::with('assignedUsers')->findOrFail($id);

        // Only admins can edit
        $this->authorize('update', $url);

        $users = User::where('status', 1)
            ->where('id', '!=', Auth::id())
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'ADMIN');
            })
            ->orderBy('name', 'asc')
            ->get();

        $assignedUserIds = $url->assignedUsers->pluck('id')->toArray();

        return view('admin.url_management.edit', compact('url', 'users', 'assignedUserIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        $id = UrlManagement::decryptId($encryptedId);

        if (!$id) {
            abort(404, 'URL not found');
        }

        $url = UrlManagement::findOrFail($id);

        // Only admins can update
        $this->authorize('update', $url);

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:1000',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Check SSL status
            $sslStatus = parse_url($request->url, PHP_URL_SCHEME) === 'https' ? 'active' : 'inactive';

            $url->update([
                'name' => $request->name,
                'url' => $request->url,
                'ssl_status' => $sslStatus,
            ]);

            // Update assigned users
            if (auth()->user()->hasRole('ADMIN')) {
                if ($request->has('assigned_users') && is_array($request->assigned_users)) {
                    $url->assignedUsers()->sync($request->assigned_users);
                } else {
                    $url->assignedUsers()->sync([]);
                }
            }


            DB::commit();
            return redirect()->route('url-management.index')
                ->with('message', 'URL updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update URL: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId)
    {
        $id = UrlManagement::decryptId($encryptedId);

        if (!$id) {
            abort(404, 'URL not found');
        }

        $url = UrlManagement::findOrFail($id);

        // Only admins can delete
        $this->authorize('delete', $url);

        $url->delete();

        return redirect()->route('url-management.index')
            ->with('error', 'URL has been deleted successfully.');
    }

    /**
     * Check URL manually
     */
    public function checkUrl($encryptedId)
    {
        $id = UrlManagement::decryptId($encryptedId);

        if (!$id) {
            return response()->json(['error' => 'Invalid URL ID'], 404);
        }

        $url = UrlManagement::findOrFail($id);

        $this->authorize('check-urls');

        // Check access permissions
        $this->checkAccessPermission($url);

        // Perform URL check
        $result = $this->performUrlCheck($url);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Fetch data for AJAX pagination/search
     */
    public function fetchData(Request $request)
    {
        if ($request->ajax()) {
            $this->authorize('viewAny', UrlManagement::class);
            $user = Auth::user();
            $sortBy = $request->get('sortby', 'id');
            $sortType = $request->get('sorttype', 'desc');
            $search = $request->get('query');

            // Start query
            if ($user->can('manage-urls')) {
                $urlsQuery = UrlManagement::with(['assignedUsers', 'creator']);
            } else {
                $urlsQuery = UrlManagement::whereHas('assignedUsers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->with(['assignedUsers', 'creator']);
            }

            // Apply search
            if (!empty($search)) {
                $search = str_replace(" ", "%", $search);
                $urlsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            // Pagination
            $page = $request->get('page', 1);
            if (!empty($search)) {
                $page = 1;
            }

            $urls = $urlsQuery
                ->orderBy($sortBy, $sortType)
                ->paginate(10, ['*'], 'page', $page);

            return response()->json([
                'data' => view('admin.url_management.table', compact('urls'))->render()
            ]);
        }
    }

    /**
     * Check access permission for a URL
     */
    private function checkAccessPermission($url)
    {
        $user = Auth::user();

        // Users with manage-urls have full access
        if ($user->can('manage-urls')) {
            return true;
        }

        // Check if user is assigned to this URL
        $isAssigned = $url->assignedUsers()->where('user_id', $user->id)->exists();

        if (!$isAssigned) {
            abort(403, 'You do not have permission to access this URL.');
        }

        return true;
    }

    /**
     * Perform URL check
     */
    private function performUrlCheck($url)
    {
        $startTime = microtime(true);
        $status = 'down';
        $statusCode = null;
        $errorMessage = null;
        $responseTime = null;

        try {
            $ch = curl_init($url->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request

            curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000); // Convert to milliseconds

            if ($statusCode >= 200 && $statusCode < 400) {
                $status = 'up';
            } else {
                $status = 'down';
                $errorMessage = "HTTP Status Code: {$statusCode}";
            }

            if ($curlError) {
                $status = 'down';
                $errorMessage = $curlError;
            }
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);
            $status = 'down';
            $errorMessage = $e->getMessage();
        }

        // Update URL status
        $sslStatus = parse_url($url->url, PHP_URL_SCHEME) === 'https' ? 'active' : 'inactive';

        $url->update([
            'status' => $status === 'up' ? 'active' : 'down',
            'ssl_status' => $sslStatus,
            'last_checked_at' => now(),
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
        ]);

        // Log activity
        UrlActivityLog::create([
            'url_id' => $url->id,
            'status' => $status,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => now(),
            'log_date' => today(),
        ]);

        return [
            'status' => $status,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
        ];
    }
}
