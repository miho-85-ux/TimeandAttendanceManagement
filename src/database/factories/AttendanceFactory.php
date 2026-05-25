<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Attendance;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => $this -> faker -> date('Y-m-d'),
            'check_in' => $this -> faker -> dateTimeBetween('09:00', '10:00'),
            'check_out' => $this -> faker -> dateTimeBetween('17:00', '19:00'),        
        ];
    }
}
