<?php 

namespace App\Http\Controllers\Wechat\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Wechat\WxMessageRepository;
use App\Repositories\Wechat\WxEventRepository;
use App\Repositories\OrgInfoRepository;



class MessageController extends Controller
{
	protected $wxmessage;
	protected $event;
	protected $org;
	public function __construct(WxMessageRepository $wxmessage, WxEventRepository $event, OrgInfoRepository $org)
	{
		$this->wxmessage = $wxmessage;
		$this->event = $event;
		$this->org = $org;
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

	public function template()
	{
		// $result = $this->org->createUnLimitQRCode(2, '/pages/login/login', ['art_id'=>10, 'from_user'=>1]);
		$result = $this->org->createQRCode(3);
		print_r($result);
		if (isset($result['data']['path'])) {
			echo '<br>http://127.0.0.1:8000/'.ltrim($result['data']['path'], '.');
		}
		
		die;
		// $result = $app->template_message->send([
	 //        'touser' 		=> 'oxyQh1WpluVaxaG_STzgwlZhd18A',
	 //        'template_id' 	=> 'pYVz0VprC3S9I-HnVAhRYFgU2glH-noVzfjLYH5PgiY',
	 //        'url' 			=> '',
	 //        // 'miniprogram' => [
	 //        //         'appid' => 'xxxxxxx',
	 //        //         'pagepath' => 'pages/xxx',
	 //        // ],
	 //        'data' => [
	 //            'first' => '亲爱的[xxx]同学，您的会员卡状态已变更，具体如下：',
	 //            'keyword1' => 'VIP黄金会员',
	 //            'keyword2'	=> '2020-01-01 ~ 2030-01-01',
	 //            'remark'	=> '详细内容请向门店咨询，电话：010-xxxxxxx'
	 //        ]
	 //    ]);
	 	// 获取所设置模板列表
		$result = $app->template_message->getPrivateTemplates();
		// 获取支持的行业列表
		$result = $app->template_message->getIndustry();
	    return $this->success($result);
	}
}