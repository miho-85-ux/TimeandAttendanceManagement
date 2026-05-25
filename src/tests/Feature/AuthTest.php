<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    /** @test */
    public function 名前が未入力の場合バリデーションエラーになる()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function メールが未入力の場合バリデーションエラーになる()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが8文字未満ならエラーになる()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワード不一致ならエラーになる()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 正常に会員登録できる()
    {
        $response = $this->post('/register',[
            'name' => 'テスト',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users',[
            'email' => 'test@test.com',
            'name' => 'テスト',
        ]);
    }

}
