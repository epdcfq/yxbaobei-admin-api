<?php 

namespace App\Http\Controllers\Wechat\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Wechat\WxAuthorizeRepository;


class AuthorizeController extends Controller
{
	protected $authorize;
	public function __construct(WxAuthorizeRepository $authorize)
	{
		$this->authorize = $authorize;
	}

	public function info(Request $request)
	{
		$openId = 'oxyQh1WpluVaxaG_STzgwlZhd18A';
		// echo $openId;die;
		$result = $this->authorize->authByOpenId($openId);
		$data = [];
		if ($result) {
			$result['openid'] .= 'B';
			$result['unionid'] = 'oxyQh1WpluVaxaG_STzgwlZhd18B';
			$data = $this->authorize->addAuthorize($result);
		}

		return $this->success(['author'=>$result, 'db'=>$data]);
	}

	public function event(Request $request)
	{
		$data = [
			'ToUserName'=>'gh_857275425e4c',
			'FromUserName'=>'oxyQh1WpluVaxaG_STzgwlZhd18A',
			'MsgType'=>'event',
			'CreateTime'=>'1578966091',
			// 'Event'=>'subscribe',
			'Event'=>'VIEW',
			'EventKey'=>'http://118.190.38.248',
			'MenuId'=>'437175746'
		];

		$result = $this->event->newEvent($data);
		return $this->success($result);
	}
}