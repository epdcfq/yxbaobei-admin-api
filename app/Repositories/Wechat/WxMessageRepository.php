<?php 
/** 
 * 消息管理类
 * 
 */

namespace App\Repositories\Wechat;

use App\Repositories\BaseRepository;
use App\Repositories\Tools\EncryptTool;
use App\Models\WechatMessageModel;

class WxMessageRepository extends BaseRepository
{
	protected $wxmessage;

	public function __construct(WechatMessageModel $wxmessage)
	{
		$this->wxmessage = $wxmessage;
	}


	public function newMessage($args)
	{
		$result = [];

		// 初始化数据
		$data = $this->parseField($args);
		if (!$data['msg_id']) {
			return [];
		}

		// 检测消息是否已保存,存在直接返回
		$result = $this->existsMsgId($data['msg_id']);
		if ($result) {
			return $result;
		}

		// 保存消息
		$result = $this->wxmessage::create($data);
		
		return $result;
	}

	/** 
	 * 初始化字段，转换成数据库字段格式
	 * 
	 * @param     [array]      $args [待转换消息数组]
	 * 
	 * @return    [array]            [数据库字段对应内容]
	 */
	public function parseField($args)
	{
		// 数据库允许添加的字段
		$allow_field = $this->wxmessage->filter_field();
		$data = [];
		foreach ($args as $k=>$value) {
			$key = EncryptTool::toUnderScore($k);
			// 非允许字段，放到data_json字段中
			if (!array_key_exists($key, $allow_field)) {
				$data['data_json'][$key] = $value;
				continue;
			}

			// 字段赋值
			$data[$key] = $value;
		}

		if (isset($data['data_json'])) {
			$data['data_json'] = json_encode($data['data_json']);
		}

		return $data;
	}

	/** 
	 * 根据消息id获取消息是否存在
	 * 
	 * @param     [int]      $msg_id [微信消息id]
	 * 
	 * @return    [array]
	 */
	public function existsMsgId($msg_id)
	{
		return $this->wxmessage->where('msg_id', $msg_id)->first();
	}
}