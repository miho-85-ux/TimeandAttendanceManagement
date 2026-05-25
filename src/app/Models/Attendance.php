<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'remarks',
        'status',
    ];

    public function user() {
        return $this -> belongsTo(User::class);
    }
    
    public function breaktimes() {
        return $this -> hasMany(BreakTime::class);
    }

    public function attendanceRequests() {
        return $this -> hasMany(AttendanceRequest::class);
    }

    public function getActualWorkTimeAttribute(){
        $hours = floor($this->actual_work_minutes / 60);
        $minutes = $this->actual_work_minutes % 60;

        return "{$hours}時間{$minutes}分";
    }

    public function getListDateAttribute(){
        return Carbon::parse($this->date)->translatedFormat('n/j (D)');
    }

    public function getDetailDateAttribute(){
        return carbon::parse($this->date)->format('Y年  n月j日');
    }

    public function getCheckInTimeAttribute(){
        return Carbon::parse($this->check_in)->format('H:i');
    }
    
    public function getCheckOutTimeAttribute(){
        if(!$this->check_out) {
            return '-';
        }
        return Carbon::parse($this->check_out)->format('H:i');
    }

    public function getBreakTimeAttribute(){
        $totalBreak = 0;

        foreach ($this->breaktimes as $break) {
            $totalBreak += Carbon::parse($break->break_end)->diffInMinutes(Carbon::parse($break->break_start));
        }

        $hours = floor($totalBreak /60);
        $minutes = $totalBreak % 60;

        return "{$hours}時間{$minutes}分";
    }

    public function getWorkMinutesAttribute(){
        if(!$this->check_in || !$this->check_out) {
            return 0;
        }

        return Carbon::parse($this->check_out)->diffInMinutes(Carbon::parse($this->check_in));
    }

    public function getBreakMinutesAttribute(){
        $total = 0;
        foreach($this->breaktimes as $break){
            if($break->break_start && $break->break_end) {
                $total += Carbon::parse($break->break_end)->diffInMinutes(Carbon::parse($break->break_start));
            }
        }
        return $total;
    }

    public function getActualWorkMinutesAttribute(){
        return $this->work_minutes - $this->break_minutes;
    }

    public function getActualWorkHoursAttribute(){
        $hours = floor($this->actual_work_minutes / 60);
        $minutes = $this->actual_work_minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function getBreakHoursAttribute(){
        $hours = floor($this->break_minutes / 60);
        $minutes = $this->break_minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    
}
