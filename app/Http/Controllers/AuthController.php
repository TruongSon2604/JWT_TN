<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60, // Sử dụng getTTL()
            'user' => auth()->user()
        ]);
    }
    public function profile()
    {
         return response()->json(auth()->user());
    }
    public function logout()
    {   auth()->logout();
         return response()->json(["message"=>"logouted"]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    public function guard()
    {
        return Auth::guard();
    }
    public function changePassword(Request $request)
    {
        // Xác nhận yêu cầu thay đổi mật khẩu
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Xác minh mật khẩu hiện tại
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không đúng'], 400);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công']);
    }
}
