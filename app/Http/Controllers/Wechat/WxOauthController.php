<?php

namespace App\Http\Controllers\Wechat;

use Log;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class WxOauthController extends Controller
{
	/** 
	 * 用户同意授权,获取scope
	 * 
	 * @return    [type]      [description]
	 */
    public function Scope(Request $request)
    {
    	Log::info('request arrived.');

    	// 设置授权成功返回地址
    	$referer = '/home';
    	if ($request->has('referer')) {
    		$referer = $request->referer;
    	}
    	$_SESSION['referer'] = $referer;

    	# 授权跳转
    	$app = app('wechat.official_account');
    	$response = $app->oauth->scopes(['snsapi_userinfo'])
                          ->redirect();
        return $response;
    }

    // 授权回调页面
    public function ScopeCallback()
    {
    	Log::info('request arrived.');

    	$app = app('wechat.official_account');
    	// $oauth = $app->oauth;

		// 获取 OAuth 授权结果用户信息
		$user = $app->oauth->user();

		$_SESSION['wechat_user'] = $user->toArray();

		$targetUrl = empty($_SESSION['referer']) ? '/' : $_SESSION['referer'];
		echo 'header:'.$targetUrl.chr(10);
		print_r($_SESSION['wechat_user']);
		die;
		// header('location:'. $targetUrl); // 跳转到 user/profile
    }
}
