<?php

namespace App\Http\Controllers\Wechat;

use Log;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Overtrue\Socialite\AuthorizeFailedException;


class WxOauthController extends Controller
{
  // state安全校验盐值
  protected $salt = '*f&()^@Q';
  
	/** 
	 * 用户同意授权,获取scope
	 * 
	 * @param  Request 	$request 	Request请求
	 * @param  string 	$source 	用户来源标识[可选]
	 * @param  int 		$source 	来源id[可选]
	 * 
	 * @return    [type]      [description]
	 */
    public function index(Request $request, $source='', $source_id=0)
    {
    	Log::info('/wechat/authorize arrived.');

    	// 设置授权成功返回地址
    	$redirect_uri = '/home';
    	if ($request->has('redirect_uri')) {
    		$redirect_uri = $request->redirect_uri;
    	}
    	$_SESSION['redirect_uri'] = $redirect_uri;




    	# 授权回调跳转(附带来源)
    	$callback_url = config('wechat')['official_account']['default']['oauth']['callback'];
    	$app = app('wechat.official_account');
    	$response = $app->oauth->scopes(['snsapi_userinfo'])->with(['state'=>$this->encodeState($source, $source_id)])
                          ->redirect(urlencode($callback_url));
        return $response;
    }

    /** 
     * 授权回调
     * 
     * @param     Request     $request   [Request请求]
     * @param     string      $source    [用户来源标识]
     * @param     integer     $source_id [用户来源id]
     */
    public function callback(Request $request, $source='', $source_id=0)
    {
    	Log::info('/wechat/authorize/callback arrived.');

  		try {
  			// 获取 OAuth 授权结果用户信息
  	    $app = app('wechat.official_account');
  			$user = $app->oauth->user();
        if (!$user) {
          $this->fail('401', '授权失败');
        }
        
  			// 保存授权信息，记录来源
  			$_SESSION['wechat_user'] = $user->toArray();
  		} catch (AuthorizeFailedException $e) {
        return $this->fail(401, $e->getMessage());
  		}
  		
      
  		$targetUrl = empty($_SESSION['referer']) ? '/' : $_SESSION['referer'];
  		echo 'header:'.$targetUrl.chr(10);
  		print_r($_SESSION['wechat_user']);
  		die;
		// header('location:'. $targetUrl); // 跳转到 user/profile
    }


    /** 
     * 增加授权state参数加密
     * 
     * @param     [type]      $source    [description]
     * @param     [type]      $source_id [description]
     * @return    [type]                 [description]
     */
    protected function encodeState($source, $source_id)
    {
      $source = str_replace('-', '_', $source);
      $source_id = str_replace('-', '_', $source_id);
      // 设置默认值
      if (!$source) {
        $source = 'web';
      }
      if (!$source_id) {
        $source_id = 0;
      }

      $params = [$source, $source_id];
      $params['t'] = time();
      $params['token'] = md5(implode($this->salt, $params));
      return urlencode(implode('-', $params));
    }

    /** 
     * 授权回调state校验
     * 
     * @param     [string]     $state [description]
     * @return    [type]             [description]
     */
    protected function decodeState($state)
    {
      $state_arr = explode('-', $state);
      if (count($state_arr) != 4) {
        return false;
      }

      // 获取各参数值
      $params = [];
      $token  = '';
      list($params['source'], $params['source_id'], $params['t'], $token) = $state_arr;
      // 校验token值
      if (!$token || $token != md5(implode($this->salt, $params))) {
        return false;
      }

      return $params;
    }


}
