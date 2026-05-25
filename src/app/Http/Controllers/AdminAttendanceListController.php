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
    public function index(Request $request){

        $dateParam = $request->input('date');
        $date = $dateParam ? Carbon::parse($dateParam) : Carbon::now(); 
    
        $attendances = Attendance::with('user')
            -> whereDate('date', $date->format('Y-m-d'))
            -> with('breaktimes')
            -> get();

        $prevDay = $date -> copy() -> subDay() -> format('Y-m-j');
        $nextDay = $date -> copy() -> addDay() -> format('Y-m-j');

        return view('admin.admin-attendance-list', compact('attendances',  'prevDay', 'nextDay', 'date'));
    }
}
