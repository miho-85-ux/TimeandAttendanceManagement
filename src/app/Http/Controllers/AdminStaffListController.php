<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;


class AdminStaffListController extends Controller
{
    public function index(){
        $users = User::all();

        return view('admin.admin-staff-list', compact('users'));
    }

    public function detail(Request $request, $id) {
        $user = User::find($id);
        $dateParam = $request->input('date');
        $date = $dateParam ? Carbon::parse($dateParam) : Carbon::now(); 
        $attendances = Attendance::where('user_id', $id)
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

        return view('admin.admin-attendance-staff', compact('user', 'date', 'attendances', 'prevMonth', 'nextMonth','dates'));
    }
}
