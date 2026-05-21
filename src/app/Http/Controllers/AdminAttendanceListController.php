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
        $date = $dateParam ? \Carbon\Carbon::parse($dateParam) : \Carbon\Carbon::now(); 
    
        $attendances = Attendance::with('user')
            -> whereYear('date', $date->year) 
            -> whereMonth('date', $date->month)
            -> with('breaktimes')
            -> get()
            -> KeyBy('date');

        $prevMonth = $date -> copy() -> subMonth() -> format('Y-m');
        $nextMonth = $date -> copy() -> addMonth() -> format('Y-m');

        $dates = [];
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        for ($day = $start; $day->lte($end); $day->addDay()) {
            $dates[] = $day->copy();
        }

        return view('admin.admin-attendance-list', compact('attendances',  'prevMonth', 'nextMonth', 'dates','date'));
    }
}
