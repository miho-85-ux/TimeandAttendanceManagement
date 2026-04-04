<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index() {
        $attendance=Attendance::first();
      

        return view('general.attendance');
    }
}
