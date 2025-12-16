<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlActivityLog extends Model
{
    use HasFactory;

    protected $table = 'url_activity_logs';

    protected $fillable = [
        'url_id',
        'status',
        'response_time',
        'status_code',
        'error_message',
        'checked_at',
        'log_date',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'log_date' => 'date',
    ];

    /**
     * Get the URL this log belongs to
     */
    public function url()
    {
        return $this->belongsTo(UrlManagement::class, 'url_id');
    }

    /**
     * Scope to get logs for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('log_date', today());
    }

    /**
     * Scope to get downtime logs
     */
    public function scopeDown($query)
    {
        return $query->where('status', 'down');
    }

    /**
     * Scope to get uptime logs
     */
    public function scopeUp($query)
    {
        return $query->where('status', 'up');
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('log_date', [$startDate, $endDate]);
    }
}
