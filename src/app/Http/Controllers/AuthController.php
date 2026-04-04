<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;

class AuthController extends Controller
{
    public function registerForm() {

        return view('auth.register');
    }
    public function loginForm() {

        return view('auth.login');
    }

}
