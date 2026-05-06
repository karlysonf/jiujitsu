<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;

class AttendanceService
{
    public function recordAttendance(int $userId, $date = null)
    {
        return Attendance::create([
            'user_id' => $userId,
            'date' => $date ?? now()->toDateString(),
        ]);
    }

    public function getStudentAttendanceHistory(int $userId)
    {
        return Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getDailyAttendance($date = null)
    {
        $date = $date ?? now()->toDateString();
        return Attendance::with('user')
            ->where('date', $date)
            ->get();
    }

    public function removeAttendance(int $userId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->delete();
    }
}
