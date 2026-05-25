<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 日時が正しく表示される()
    {
        $user = User::factory()->create();

        $now = now();

        Carbon::setTestNow($now);

        $response = $this->actingAs($user)
            ->get('/attendance');

        $response->assertSee($now->format('Y年m月d日'));
    }

    private function createUser()
    {
        return User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function 勤務外ステータスが表示される()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('勤務外'); 
    
    }
    /** @test */
    public function 勤務中ステータスが表示される()
    {
        $user = $this->createUser();

        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('出勤中'); 
    }

    /** @test */
    public function 休憩中ステータスが表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('休憩中'); 
    }

    /** @test */
    public function 退勤済みステータスが表示される()
    {
        $user = $this->createUser();

        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
            'check_out' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');
        $response->assertSee('退勤済'); 
    }

    /** @test */
    public function 出勤できる()
    {
        $user = $this->createUser();

        // ① 初期画面確認
        $response = $this->actingAs($user)->get('/attendance');

        // ② 出勤ボタン確認
        $response->assertSee('出勤');

        // ③ 出勤処理
        $response = $this->actingAs($user)->post('/attendance', [
            'type' => 'check_in',
        ]);

        $response->assertStatus(302);

        // ④ DB確認
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
        ]);

        // ⑤ もう一回画面見て状態確認
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('出勤中');
    }

    /** @test */
    public function 退勤できる()
    {
        $user = $this->createUser();
        
        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->subHours(8),
        ]);

        // ① 初期画面確認
        $response = $this->actingAs($user)->get('/attendance');
        
        // ② 退勤ボタン確認
        $response->assertSee('退勤');

        // ③ 退勤処理
        $response = $this->actingAs($user)->post('/attendance', [
            'type' => 'check_out',
        ]);

        $response->assertStatus(302);

        // ④ DB確認
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
        ]);

        // ⑤ もう一回画面見て状態確認
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('退勤済');
    }

    /** @test */
    public function 休憩できる()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        // ① 初期画面確認
        $response = $this->actingAs($user)->get('/attendance');

        // ② 休憩ボタン確認
        $response->assertSee('休憩入');

        // ③ 休憩処理
        $response = $this->actingAs($user)->post('/attendance', [
            'type' => 'break_start',
        ]);

        $response->assertStatus(302);

        // ④ DB確認
        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
        ]);

        // ⑤ もう一回画面見て状態確認
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('休憩中');
    }

    /** @test */
    public function 出勤は一日一回のみ()
    {
        $user = $this->createUser();

        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->subHours(8),
            'check_out' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertDontSee('出勤');
    }

    /** @test */
    public function 休憩戻できる()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->subHours(2),
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now()->subMinutes(30),
        ]);

        $response = $this->actingAs($user)->post('/attendance', [
            'type' => 'break_end',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('出勤中');
    }

    /** @test */
    public function 出勤時刻が一覧画面に表示される()
    {
        $user = $this->createUser();

        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertSee('09:00');
    }

    /** @test */
    public function 退勤時刻が一覧画面に表示される()
    {
        $user = $this->createUser();

        Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
            'check_out' => now()->setTime(18,0),
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertSee('18:00');
    }

    /** @test */
    public function 休憩時刻が一覧画面に表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now()->setTime(12,0),
            'break_end' => now()->setTime(13,0),
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertSee('1:00');
    }

    /** @test */
    public function 現在の月が表示される()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertSee(now()->format('Y年m月'));
    }

    /** @test */
    public function 前月の情報が表示される()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get('/attendance/list?date=' . now()->subMonth()->format('Y-m'));

        $response->assertSee(now()->subMonth()->format('Y年m月'));
    }

    /** @test */
    public function 翌月の情報が表示される()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get('/attendance/list?date=' . now()->addMonth()->format('Y-m'));

        $response->assertSee(now()->addMonth()->format('Y年m月'));
    }

    /** @test */
    public function 詳細画面に遷移できる()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function 勤怠詳細画面に名前が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertSee($user->name);
    }

    /** @test */
    public function 勤怠詳細画面に日付が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertSee(today()->format('Y年'));

    }

    /** @test */
    public function 出勤退勤時間が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
            'check_out' => now()->setTime(18,0),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** @test */
    public function 休憩時間が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now()->setTime(12,0),
            'break_end' => now()->setTime(13,0),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }

    /** @test */
    public function 出勤時間が退勤時間より後ならエラー()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(18,0),
            'check_out' => now()->setTime(9,0),
        ]);

        $response = $this->actingAs($user)
        ->patch("/attendance/detail/{$attendance->id}", [
            'check_in' => '18:00',
            'check_out' => '09:00',
        ]);

        $response->assertSessionHasErrors([
            'check_in' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    /** @test */
    public function 休憩開始が退勤より後ならエラー()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
            'check_in' => now()->setTime(9,0),
            'check_out' => now()->setTime(18,0),
        ]);

        $response = $this->actingAs($user)
            ->patch("/attendance/detail/{$attendance->id}", [
                'check_in' => '09:00',
                'check_out' => '18:00',
                'break_start' => ['19:00'],
                'break_end' => ['18:00'],
            ]);

        $response->assertSessionHasErrors([
            'break_start.0' => '休憩時間が不適切な値です',
        ]);
    }

    /** @test */
    public function 備考未入力エラー()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        $response = $this->actingAs($user)
            ->patch("/attendance/detail/{$attendance->id}", [
                'remarks' => '',
            ]);

        $response->assertSessionHasErrors([
            'remarks' => '備考を記入してください',
        ]);
    }

    /** @test */
    public function 修正申請が作成される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        $response = $this->actingAs($user)
            ->patch("/attendance/detail/{$attendance->id}", [
                'check_in' => '09:00',
                'check_out' => '18:00',
                'remarks' => '修正します',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('attendance_requests', [
            'attendance_id' => $attendance->id,
            'status' => 'pending',
        ]);
    }

    public function 承認待ちに自分の申請が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'requested_check_in' => '09:00',
            'requested_check_out' => '18:00',
            'reason' => 'テスト',
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance-requests?status=pending');

        $response->assertSee('テスト');
    }

    public function 承認済みに管理者の承認済み申請が表示される()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => 'approved',
            'requested_check_in' => '09:00',
            'requested_check_out' => '18:00',
            'reason' => '承認済みテスト',
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance-requests?status=approved');

        $response->assertSee('承認済みテスト');
    }

    public function 詳細ボタンで勤怠詳細に遷移できる()
    {
        $user = $this->createUser();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => today(),
        ]);

        $response = $this->actingAs($user)
            ->get("/attendance/detail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee('勤怠詳細');
    }

}
