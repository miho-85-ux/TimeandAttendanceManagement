<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;

class CsvController extends Controller
{
    public function exportCsv($id, Request $request) {
        $month = $request->month; // 2026-05

        $query = Attendance::where('user_id', $id);

        if ($month) {
            $query->where('date', 'like', $month . '%');
        }

        $attendances = $query->get();

        $csvData = [];
        $csvData[] = [
            '氏名',
            '日付', 
            '出勤', 
            '退勤', 
            '休憩時間合計', 
            '実働時間', 
        ];

        foreach ($attendances as $attendance) {

            if (!$attendance->check_in_time || !$attendance->check_out_time) {
                continue;
            }
        
            $checkIn = (!empty($attendance->check_in_time) && $attendance->check_in_time !== '-')
                ? Carbon::parse($attendance->check_in_time)
                : null;

            $checkOut = (!empty($attendance->check_out_time) && $attendance->check_out_time !== '-')
                ? Carbon::parse($attendance->check_out_time)
                : null;

         
            $breakMinutes = $attendance->breaktimes->reduce(function ($carry, $break) {
                if (!$break->break_start_time || !$break->break_end_time) {
                    return $carry;
                }

                return $carry + Carbon::parse($break->break_end_time)
                    ->diffInMinutes(Carbon::parse($break->break_start_time));
            }, 0);

        
            $totalMinutes = ($checkIn && $checkOut)
                ? $checkIn->diffInMinutes($checkOut)
                : 0;

      
            $workMinutes = $totalMinutes - $breakMinutes;

     
            $hours = floor($workMinutes / 60);
            $minutes = $workMinutes % 60;

            $workTime = sprintf('%02d:%02d', $hours, $minutes);

            $csvData[] = [
                $attendance->user->name,
                $attendance->detail_date,
                $attendance->check_in_time,
                $attendance->check_out_time,
                $breakMinutes,
                $workTime,
            ];
        }

        return response()->streamDownload(function () use ($csvData) {
            $handle = fopen('php://output', 'w');

            echo "\xEF\xBB\xBF";

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'attendance.csv');
    }
}
 
