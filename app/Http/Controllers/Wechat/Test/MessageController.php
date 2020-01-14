<?php 

namespace App\Http\Controllers\Wechat\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Wechat\WxMessageRepository;

class MessageController extends Controller
{
	protected $wxmessage;
	public function __construct(WxMessageRepository $wxmessage)
	{
		$this->wxmessage = $wxmessage;
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
}