<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use App\Models\AttendanceRequestBreakTime;
use App\Http\Requests\AttendanceDetailRequest;

class AttendanceDetailController extends Controller
{
    public function index( $id ) {
        $attendance = Attendance::with('user', 'breaktimes','attendanceRequests.attendanceRequestBreakTimes')->find($id);
        $pendingRequest = $attendance->attendanceRequests->where('status', 'pending')->last();
        $isPending =  !is_null($pendingRequest);
        
        return view('general.attendance-detail', compact('attendance', 'isPending', 'pendingRequest'));
    }

    public function update(AttendanceDetailRequest $request, $id) {
        $attendance = Attendance::with('user', 'breaktimes','attendanceRequests.attendanceRequestBreakTimes')->find($id);

        $attendanceRequest = AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'requested_check_in' => $attendance->date . ' ' . $request->check_in,
            'requested_check_out' => $attendance->date . ' ' . $request->check_out,
            'reason' => $request->remarks,
            'status' => 'pending',
        ]);

        foreach($request->break_start ?? [] as $index => $start){
            $end = $request->break_end[$index] ?? null;
            if (!$start || !$end) continue;

            AttendanceRequestBreakTime::create([
                'attendance_request_id' => $attendanceRequest->id,
                'break_start' => $attendance->date . ' ' . $start,
                'break_end' => $attendance->date . ' ' . $end,
            ]);
            
        }

        
       
        return redirect()->route('attendance.detail', $attendance->id );
    }
}
