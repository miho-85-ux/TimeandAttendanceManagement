<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
 

    private function createAdminUser()
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function 管理者勤怠一覧で全ユーザー分が表示される()
    {
        $admin = $this->createAdminUser();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Attendance::create([
            'user_id' => $user1->id,
            'date' => today(),
            'check_in' => today()->setTime(9, 0),
            'check_out' => today()->setTime(18, 0),
        ]);

        Attendance::create([
            'user_id' => $user2->id,
            'date' => today(),
            'check_in' => today()->setTime(10,0),
            'check_out' => today()->setTime(19,0),
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list?date=' . today()->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
    }

    /** @test */
    public function 勤怠一覧で現在日付が表示される()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)
            ->get('/admin/attendance/list');

        $response->assertSee(today()->format('Y/m/d'));
    }

    /** @test */
    public function 翌日を押すと翌日の勤怠が表示される()
    {
        $admin = $this->createAdminUser();

        $tomorrow = today()->addDay()->format('Y-m-d');

        $response = $this->actingAs($admin)
            ->get("/admin/attendance/list?date={$tomorrow}");

        $response->assertStatus(200);
    }

    /** @test */
    public function 管理者勤怠詳細が正しく表示される()
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => today()->setTime(9,0),
            'check_out' => today()->setTime(18,0),
        ]);

        $response = $this->actingAs($admin)
            ->get("/admin/attendance/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('09:00');
    }


}

