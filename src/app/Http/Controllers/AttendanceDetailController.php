<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Http\Requests\AttendanceDetailRequest;

class AttendanceDetailController extends Controller
{
    public function index( $id ) {
        $attendance = Attendance::with('user', 'breaktimes')->find($id);
        
        return view('general.attendance-detail', compact('attendance'));
    }

    public function update(AttendanceDetailRequest $request, $id) {
        $attendance = Attendance::with('user', 'breaktimes')->find($id);
        $attendance->update([
            'check_in' => $attendance->date . ' ' . $request->check_in,
            'check_out' => $attendance->date . ' ' . $request->check_out,
            'remarks' => $request->remarks,
            'status' => 'pending',
        ]);

        $attendance->breaktimes()->delete();

        foreach($request->break_start as $index => $start){
            if($start && $request->break_end[$index]) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $attendance->date . ' ' . $start,
                    'break_end' => $attendance->date . ' ' . $request->break_end[$index],
                ]);
            }
        }
        return redirect()->route('attendance.detail', $attendance->id);
    }
}
