<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth; // Đảm bảo import đúng namespace
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AdminUser; // Import model

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //dd($request);
        // Sử dụng AdminUser để xác thực
        if (Auth::guard('admin')->attempt($credentials)) {
            // Nếu đăng nhập thành công, chuyển hướng tới trang dashboard
            //dd($credentials, AdminUser::where('email', $request->email)->first());
            return redirect('/admin'); // Thay vì route

        }


        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
