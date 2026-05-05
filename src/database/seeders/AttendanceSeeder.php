<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'テストユーザー', 'password' => bcrypt('password')]
        );
      
        $start = Carbon::now()->subMonths(4)->startOfMonth();
        $end   = Carbon::now()->subMonth()->endOfMonth();
        
        for($date = $start; $date->lte($end); $date->addDay()){

            if ($date->isWeekend()) {
                continue;
            }
            
            $attendance =Attendance::create([
                'user_id' => $user->id,
                'date' => $date->format('Y-m-d'),
                'check_in' => $date->copy()->setTime(9,0),
                'check_out' => $date->copy()->setTime(18,0),
            ]);

            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start' => $date->copy()->setTime(12,0),
                'break_end' => $date->copy()->setTime(13,0),
            ]);

            
        }



    }
}