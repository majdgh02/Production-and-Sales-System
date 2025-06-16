<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('username', $fields['username'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'اسم المستخدم او كلمة المرور غير صحيحة'
            ], 401); // 401 Unauthorized
        }

        $token = $user->createToken('usertoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200); // 200 OK
    }

    public function logout(Request $request)
    {
        // حذف التوكن الحالي الذي يستخدمه المستخدم للمصادقة
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسحيل الخروج بنجاح'
        ], 200);
    }

    public function changepass(Request $request)
    {
        $fields = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        $user = $request->user();
        if(!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 401);
        }

        $user->password = $request->new_password;
        $user->save();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح, يرجى اعادة تسجيل الدخول'
        ], 200);
    }
}
