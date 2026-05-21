<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;

class AdminStampCorrectionRequestController extends Controller
{
    public function index(Request $request){

        $status = $request->status ?? 'pending';

        $requests = AttendanceRequest::with('user')
            ->where('status', $status)
            ->get();

        return view('admin.admin-stamp-correction-request', compact('requests'));
    }

    public function show($id) {
        $request = AttendanceRequest::with('user', 'attendance')->find($id);

        return view('admin.approval', compact('request'));
    }

    public function approval($id) {
        $request = AttendanceRequest::with('user', 'attendance')->find($id);
        $attendance = $request->attendance;

        $attendance -> update ([
            'check_in' => $request->requested_check_in,
            'check_out' => $request->requested_check_out,
            'remarks' => $request->reason,
        ]);

        $request->update ([
            'status' => 'approved'
        ]);

        return redirect()->back();
    }

}
