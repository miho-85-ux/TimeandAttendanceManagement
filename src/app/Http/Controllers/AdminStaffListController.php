<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminStaffListController extends Controller
{
    public function index(){
        return view('admin.admin-staff-list');
    }
}
