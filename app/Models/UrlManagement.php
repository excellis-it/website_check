<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class UrlManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'url_management';

    protected $fillable = [
        'name',
        'url',
        'status',
        'last_checked_at',
        'response_time',
        'status_code',
        'error_message',
        'created_by',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    /**
     * Get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    /**
     * Decrypt ID
     */
    public static function decryptId($encryptedId)
    {
        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the users assigned to this URL
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'url_assignments', 'url_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the activity logs for this URL
     */
    public function activityLogs()
    {
        return $this->hasMany(UrlActivityLog::class, 'url_id');
    }

    /**
     * Get the user who created this URL
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get today's activity logs
     */
    public function todayLogs()
    {
        return $this->activityLogs()
            ->whereDate('log_date', today())
            ->orderBy('checked_at', 'desc');
    }

    /**
     * Get downtime logs for a specific date range
     */
    public function downtimeLogs($startDate = null, $endDate = null)
    {
        $query = $this->activityLogs()->where('status', 'down');

        if ($startDate) {
            $query->whereDate('log_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('log_date', '<=', $endDate);
        }

        return $query->orderBy('checked_at', 'desc');
    }

    /**
     * Get uptime percentage for a date range
     */
    public function getUptimePercentage($startDate = null, $endDate = null)
    {
        $query = $this->activityLogs();

        if ($startDate) {
            $query->whereDate('log_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('log_date', '<=', $endDate);
        }

        $totalChecks = $query->count();

        if ($totalChecks == 0) {
            return 0;
        }

        $upChecks = $query->where('status', 'up')->count();

        return round(($upChecks / $totalChecks) * 100, 2);
    }
}
