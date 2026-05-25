<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    private function createAdmin()
    {
        return User::create([
            'name' => 'テストユーザー',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function メールアドレス未入力でエラーになる()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function  誤った情報でログイン失敗する()
    {
        $this->createAdmin();

        $response = $this->post('/login', [
            'email' => 'example@test.com',
            'password' => 'abc123456',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 正しい情報でログインできる()
    {
        $admin = $this->createAdmin();

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/attendance/list'); 
        $this->assertAuthenticatedAs($admin);
    }

}
