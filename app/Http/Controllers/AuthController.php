<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Giao diện đăng nhập quản trị
     */
    public function login()
    {
        // Nếu đã đăng nhập, chuyển thẳng vào trang quản trị bài viết
        if (Auth::check()) {
            return redirect()->route('admin.posts');
        }

        return view('pages.login');
    }

    /**
     * Xử lý đăng nhập quản trị viên
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Vui lòng nhập Email hoặc Tên đăng nhập Admin.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Tìm người dùng theo Email hoặc Name
        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        // Hỗ trợ alias đăng nhập mẫu hoặc alias hệ thống
        if (!$user && in_array(strtolower($loginInput), ['admin', 'admin@ketnoi.vn', 'admin@devforum.vn', 'admin@gmail.com'])) {
            $user = User::first();
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công! Chào mừng ' . $user->name,
                    'redirect' => route('admin.posts')
                ]);
            }

            return redirect()->intended(route('admin.posts'))->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->name);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Email/Tên đăng nhập hoặc mật khẩu không chính xác!'
            ], 422);
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->with('error', 'Email/Tên đăng nhập hoặc mật khẩu không chính xác!');
    }

    /**
     * Đăng xuất khỏi hệ thống quản trị
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đăng xuất an toàn.',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất an toàn khỏi hệ thống.');
    }
}
