<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');  // Trả về view trang dashboard
    }
    public function index()
    {
        return view('user.index');  // Trả về view trang dashboard
    }
}
