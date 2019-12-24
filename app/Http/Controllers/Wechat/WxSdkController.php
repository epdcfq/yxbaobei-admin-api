<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxSdkController extends Controller
{
	/** 
	 * 网页授权配置
	 * 
	 */
  	public function config(Request $request)
  	{
  		if ($request->has('url')) {
  			$url = $request->url;
      	} else {
        	$url = $request->fullUrl();
      	}

      	$app = app('wechat.official_account');
      	$app->jssdk->setUrl($url);
      	$data = $app->jssdk->buildConfig(['updateAppMessageShareData', 'updateTimelineShareData'], true);

      	return $this->success($data);
  	}
}