<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.admin-register'); // Tạo view cho trang đăng ký
    }

    public function register(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:adminuser',
            'password' => 'required|string|min:6|confirmed',
        ]);

        //dd('Validation passed', $request->all());
        AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Mã hóa mật khẩu
        ]);


        return redirect()->route('admin.login')->with('success', 'Tạo tài khoản thành công!');
    }
}

