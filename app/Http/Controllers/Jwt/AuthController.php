<?php

namespace App\Http\Controllers\Jwt;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\Admin;
use JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public $loginAfterSignUp = true;
    
    public function __construct(Request $request)
    {
        $request['token'] = JWTAuth::getToken();
    }
	
    //注册
    public function register(RegisterAuthRequest $request)
	{
	    $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
	}

	// 登录
	public function login(Request $request)
	{
	    $input = $request->only('email', 'username', 'password');
        $jwt_token = null;
        try {
            // 获取登录用户信息
            $user = User::where('username', $input['username'])->first();
            if (!$user || !Hash::check($input['password'], $user['password'])) {
                return $this->fail(401);
            }

            // if (!$jwt_token = JWTAuth::attempt($input)) {
            if (!$jwt_token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'token创建失败',
                    // 'data'    => $input
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        return $this->success(['token'=>$jwt_token]);
	}

	// 登录用户信息
	public function info(Request $request)
	{
	    $this->validate($request, [
            'token' => 'required'
        ]);

        // 获取登录用户信息
        $user = JWTAuth::authenticate($request->token);
        if (!$user) {
            return $this->fail(5002, '登录失效');
        }

        // 获取角色
        $user['roles'] = ['admin', 'editor'];
        $user['menuList'] = [99, 100, 101, 102, 103, 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];


        return $this->success($user);
	}

	// 退出
	public function logout(Request $request)
	{
        $request['token'] = JWTAuth::getToken();
		$this->validate($request, [
            'token' => 'required'
        ]);

        try {
            // 退出
            JWTAuth::invalidate($request->token);

            return $this->success();
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
	}

	// 刷新
	public function refresh()
	{
		if ($token = JWTAuth::parseToken()->refresh()) {
			return $this->success(['token'=>$token]);
		} else {
			return $this->fail(411);
		}
	}
}
