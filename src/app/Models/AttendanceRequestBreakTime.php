<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRequestBreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_request_id',
        'break_start',
        'break_end',
    ];

    public function attendanceRequest()
    {
        return $this->belongsTo(AttendanceRequest::class);
    }

    public function getBreakStartTimeAttribute(){
        return Carbon::parse($this->break_start)->format('H:i');
    }

    public function getBreakEndTimeAttribute(){
        return Carbon::parse($this->break_end)->format('H:i');
    }

}
