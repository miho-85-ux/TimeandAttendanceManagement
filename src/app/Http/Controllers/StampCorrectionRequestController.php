<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    public function index(Request $request){
        $status = $request->status ?? 'pending';

        $requests = AttendanceRequest::with('user')->where('user_id', auth()->id())->where('status', $status)->get();


        return view('general.stamp-correction-request', compact('requests'));
    }
}
