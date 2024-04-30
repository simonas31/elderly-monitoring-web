<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'device_id',
        'fell',
    ];

    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }

    public static function getActivityLogsGroupedByHour($device_id)
    {
        // Get the current year, month, and day
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        // Retrieve activity logs grouped by each hour of the current day
        $activityLogs = ActivityLog::selectRaw("COUNT(*) as count, HOUR(created_at) as hour_of_day")
            ->where('device_id', $device_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereDay('created_at', $currentDay)
            ->groupBy('hour_of_day')
            ->orderBy('hour_of_day', 'asc')
            ->get();

        // Create an array to hold data for all hours of the day
        $hourlyData = array_fill(0, 24, 0);

        // Fill the array with retrieved data
        foreach ($activityLogs as $log) {
            $hourlyData[$log->hour_of_day] = $log->count;
        }

        return $hourlyData;
    }

    public static function getActivityLogsGroupedByDay($device_id)
    {
        // Get the current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get the number of days in the current month
        $daysInMonth = Carbon::now()->daysInMonth;

        // Retrieve activity logs grouped by each day of the current month
        $activityLogs = ActivityLog::selectRaw("COUNT(*) as count, DAY(created_at) as day_of_month")
            ->where('device_id', $device_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->groupBy('day_of_month')
            ->orderBy('day_of_month', 'asc')
            ->get();

        // Create an array to hold data for all days of the month
        $dailyData = array_fill(1, $daysInMonth, 0);

        // Fill the array with retrieved data
        foreach ($activityLogs as $log) {
            $dailyData[$log->day_of_month] = $log->count;
        }

        return $dailyData;
    }

    public static function getActivityLogsGroupedByMonth($device_id)
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Retrieve activity logs grouped by month for the current year
        $activityLogs = ActivityLog::selectRaw("COUNT(*) as count, MONTH(created_at) as month_number")
            ->where('device_id', $device_id)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month_number')
            ->orderBy('month_number', 'asc')
            ->get();

        // Create an array to hold data for all 12 months
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $monthlyData = array_fill(1, 12, 0);
        // Fill the array with retrieved data
        foreach ($activityLogs as $log) {
            $monthlyData[$log->month_number] = $log->count;
        }

        $monthlyData = array_combine($months, $monthlyData);
        // Reindex the array to start from 0
        // $monthlyData = array_values($monthlyData);

        return $monthlyData;
    }
}
