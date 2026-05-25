<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
    ];

    public function attendance(){
        return $this -> belongsTo(Attendance::class);
    }

    public function getBreakStartTimeAttribute(){
        return Carbon::parse($this->break_start)->format('H:i');
    }

    public function getBreakEndTimeAttribute(){
        if(!$this->break_end) {
            return '-';
        }
        return Carbon::parse($this->break_end)->format('H:i');
    }

}

