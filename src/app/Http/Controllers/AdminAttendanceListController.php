<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;

class AdminAttendanceListController extends Controller
{
    public function index(){
    
        $attendances = Attendance::with('user')->get();
        return view('admin.admin-attendance-list', compact('attendances'));
    }
}
