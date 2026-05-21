<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Http\Requests\AttendanceDetailRequest;

class AdminAttendanceDetailController extends Controller
{
    public function index($id) {
        $attendance = Attendance::with('user', 'breaktimes')->find($id);


        return view('admin.admin-attendance-detail', compact('attendance'));
    }

    public function update(AttendanceDetailRequest $request, $id) {
        $attendance = Attendance::find($id);

        $attendance->update([
            'check_in' => $attendance->date . ' ' . $request->check_in,
            'check_out' => $attendance->date . ' ' . $request->check_out,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.detail', $id)->with('message', '修正しました');;

    }
}
