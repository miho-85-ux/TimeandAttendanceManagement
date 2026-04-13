<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index() {
        $attendance = Attendance :: where('user_id', auth()->id())->whereDate('date', today())->with('breaktimes')->first();
        $date = \Carbon\Carbon::now(); 
        $break = null;
        if($attendance){
            $break = $attendance->breaktimes->last();
        }

        return view('general.attendance', compact('attendance', 'break', 'date'));
    }

    public function store(Request $request){
        $attendance = Attendance :: where('user_id', auth()->id())->whereDate('date', today())->with('breaktimes')->first();
        $break = null;
        $type = $request -> input('type'); 

        if($type == 'check_in'){
            if($attendance){
                return back()->with('error', 'すでに出勤しています');
            }
            Attendance::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'check_in' => now(),
            ]);
        }elseif($type == 'check_out'){
            $attendance -> update([
                'check_out' => now(),
            ]);
        }elseif($type == 'break_start'){
            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start' => now(),
            ]);
        }elseif($type == 'break_end'){
            $break = $attendance->breaktimes->last();
            $break->update([
                'break_end' => now(),
            ]);
        }
            
        return back();
    }
}
