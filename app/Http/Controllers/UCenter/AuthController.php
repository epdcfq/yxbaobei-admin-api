<?php
/** 
 * 个人中心登录相关
 * 
 */

namespace App\Http\Controllers\UCenter;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Repositories\UCenter\AuthJwtRepository;

class AuthController extends Controller
{
	protected $account;
    protected $platid = 0;
    // jwt登录token
    protected $token;
    public function __construct(AuthJwtRepository $account, Request $request)
    {
    	$this->account = $account;
        /****** 获取公共参数 ******/
        // 获取token参数
        $this->token = $request->has('token') ? $request->token : '';
        // 登录平台
        $this->platid = $request->has('platid') ? intval($request->platid) : 0;
        if ($this->token) {
            JWTAuth::setToken($this->token);
        }
        // 中间件处理
        // $this->middleware('auth.refresh', ['except'=>['loginbypwd', 'loginbycode', 'code2session']]);
    }

    /** 
     * 手机号 + 验证码登录
     * 
     * @return    [type]      [description]
     */
    public function loginbypwd(Request $request)
    {
        $credentials = $request->only('phone', 'password', 'code', 'platid', 'openid', 'unionid');
        // 手机号校验
        if (!isset($credentials['phone']) || strlen($credentials['phone'])<11) {
            return $this->fail(1000, [], '请输入正确的手机号');
        }
        if (!isset($credentials['password']) || strlen($credentials['password'])<6) {
            return $this->fail(1001, [], '请输入密码，密码长度不少于6位');
        }

        $result = ['code'=>1000, 'msg'=>'参数错误', 'data'=>[]];

        // 默认平台登录
        $credentials['platid'] = isset($credentials['platid']) ? intval($credentials['platid']) : 0;
        $account = $this->account->loginByPwd($credentials['phone'], $credentials['password'], $credentials['platid']);
        

        if ($account['code']!==200 || !$account['data']) {
            return $account;
        }

        // 获取
        if (! $token = JWTAuth::fromUser($account['data'])) {
            return response()->json(['error' => '登录失败'], 3000);
        }

        // return $this->respondWithToken($token);
        return $this->success($this->respondWithToken($token));
    }

    /** 
     * 手机号 + 验证码登录
     * 
     * @return    [type]      [description]
     */
    public function loginbycode(Request $request)
    {
        $credentials = $request->only('phone', 'password', 'code', 'platid', 'openid', 'unionid');
        // 手机号校验
        if (!isset($credentials['phone']) || strlen($credentials['phone'])<11) {
            return $this->fail(1000, '请输入正确的手机号');
        }
        if (!(isset($credentials['code']) || isset($credentials['password']))) {
            return $this->fail(1001, '您的账号或密码错误');
        }

        $result = ['code'=>1000, 'msg'=>'参数错误', 'data'=>[]];

        // 默认平台登录
        $credentials['platid'] = isset($credentials['platid']) ? intval($credentials['platid']) : 0;
        // 手机号 + 验证码登录
        $account = $this->account->loginByCode($credentials['phone'], $credentials['code'], $credentials['platid']);
        if ($account['code']!==200 || !$account['data']) {
            return $account;
        }
        // $account['data'] = $account['data'];
        // print_r($account['data']);die;
        // return $this->success($account['data']);
        // 获取
        if (! $token = JWTAuth::fromUser($account['data'])) {
            return response()->json(['error' => '登录失败'], 3000);
        }

        $request->headers->set('Authorization','Bearer '.$token);

        return $this->success(['token'=>$token]);
    }

    /** 
     * 刷新token
     * 
     * @return    [type]      [description]
     */
    public function refresh(Request $request)
    {
        // try {
        //     $token = JWTAuth::parseToken()->refresh();
        // } catch (Exception $e) {
        //     return $this->fail(50014);
        // }

    	return $this->success(
            $this->respondWithToken(
                // $token
                JWTAuth::parseToken()->refresh()
            )
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // try {
        //     // JWTAuth::setToken($this->token);
        //     $token = JWTAuth::refresh($this->token);
        // } catch (Exception $e) {
        //     return $this->fail(401, '请登录');
        // }
        // die($token);
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }

    public function info(Request $request)
    {
        // 获取登录用户信息
        try {
            // 获取登录用户信息
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->fail(404, $user, 'user_not_found');
            }
            // 获取登录平台标识
            if ($user) {
                $user['platid'] = JWTAuth::getClaim('platid');
            }
            
        } catch (Exception $e) {
            return $this->fail(401, '请登录');
        }

        $user['roles'] = ['editor', 'admin'];
        $user['menuList'] = [99, 100, 101, 102, 103, 1,2,3,4,5,6,7,8,9,10,11,12,13,14];
        return $this->success($user);
    }

    /** 
     * 退出
     * 
     * @return    [type]      [description]
     */
    public function logout(Request $request)
    {
    	JWTAuth::parseToken()->invalidate();

        return response()->json(['message' => 'success']);
    }

    /****** 微信小程序 *****/
    /** 
     * code获取登录session
     * 
     * @return    [type]      [description]
     */
    public function code2session(Request $request)
    {

    }
}
