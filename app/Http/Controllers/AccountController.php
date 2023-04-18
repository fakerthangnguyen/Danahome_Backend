<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'phone_number' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $user = new Account([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
           'phone_number' => $request->phone_number
        ]);

        $user->save();

        return response()->json([
            'status' => 'Đăng kí thành công',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'fails',
                'message' => 'Đăng nhập không thành công'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1); // lưu thêm 1 tuần
        }  else {
            $token->expires_at = Carbon::now()->addMillisecond(5000); // lưu trong 3 giờ
        }
        // $sql = 'SELECT `roles`.`name`
        // FROM `accounts`
        // INNER JOIN `roles`
        //   ON `roles`.`id` = `accounts`.`role_id`'  ;
        //   $sql2 = DB::select( $sql);
        $token->save();

        return response()->json([
            'status' => 'Đăng nhập thành công',
            'full_name'  => $request->user()->full_name,
            'email' => $request->user()->email,
            'phone_number' => $request->user()->phone_number,
            'access_token' => $tokenResult->accessToken,
            'image' =>$request->user()->image,
            'role' => $request->user()->Role->name,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => 'success',
        ],200);
    }


    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
