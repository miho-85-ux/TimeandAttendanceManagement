<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'requested_check_in',
        'requested_check_out',
        'reason',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function attendance() {
        return $this->belongsTo(Attendance::class);
    }
    
    public function attendanceRequestBreakTimes() {
        return $this->hasMany(AttendanceRequestBreakTime::class);
    }

    public function getCheckInTimeAttribute(){
        return Carbon::parse(
            $this->requested_check_in
        )->format('H:i');
    }

    public function getCheckOutTimeAttribute(){
        return Carbon::parse(
            $this->requested_check_out
        )->format('H:i');
    }

    public function getRequestDateAttribute(){
        return Carbon::parse($this->requested_check_in)->format('Y/n/j');
    }

    public function getFormattedCreatedAtAttribute(){
        return Carbon::parse($this->created_at)->format('Y/n/j');
    }
    
}
