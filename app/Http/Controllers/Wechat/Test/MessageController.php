<?php 

namespace App\Http\Controllers\Wechat\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Wechat\WxMessageRepository;
use App\Repositories\Wechat\WxEventRepository;


class MessageController extends Controller
{
	protected $wxmessage;
	protected $event;
	public function __construct(WxMessageRepository $wxmessage, WxEventRepository $event)
	{
		$this->wxmessage = $wxmessage;
		$this->event = $event;
	}

	public function msg(Request $request)
	{
		$data = [
			'ToUserName'=>'gh_857275425e4c',
			'FromUserName'=>'oxyQh1WpluVaxaG_STzgwlZhd18A',
			'MsgType'=>'image',
			'CreateTime'=>'1578966091',
			'MsgId'=>22605360931853633,
			'MediaId'=>'Uyqst0cYF1ggYkO6KlWZ1pVcmU9npanmRkJzwrjJjptPIUrEVEGfqojsFp8jDW2l',
			'PicUrl'=>'http://mmbiz.qpic.cn/mmbiz_jpg/Aia1y9GYBGoIgf4ptQ8NSAib55SGn4fKChLpiaZoj7xK8s3etVx2CYX07xiajp8b1P9Fbwbsez4HHjxAxZTwRDhQvA/0'
		];

		$result = $this->wxmessage->newMessage($data);
		return $this->success($result);
	}

	public function event(Request $request)
	{
		$data = [
			'ToUserName'=>'gh_857275425e4c',
			'FromUserName'=>'oxyQh1WpluVaxaG_STzgwlZhd18A',
			'MsgType'=>'event',
			'CreateTime'=>'1578966091',
			'Event'=>'unsubscribe',
			// 'Event'=>'VIEW',
			'EventKey'=>'http://118.190.38.248',
			'MenuId'=>'437175746'
		];

		$result = $this->event->newEvent($data);
		return $this->success($result);
	}
}