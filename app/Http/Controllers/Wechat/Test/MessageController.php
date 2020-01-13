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
			'ToUserName'=>'oWRmw0oQFYGDGjXJp_INrbdnOPYU',
			'FromUserName'=>'oWRmw0sTqZWw8SoQyR3jayHTDuZE',
			'MsgType'=>'text',
			'CreateTime'=>time(),
			'MsgId'=>1234567890123457,
			'Content'=>'这是文本消息内容，请查收',
			'MediaId'=>234324324322,
			'PicUrl'=>'http://iamge.dafds.com/ja.jpg'
		];

		$result = $this->wxmessage->newMessage($data);
		return $this->success($result);
	}
}