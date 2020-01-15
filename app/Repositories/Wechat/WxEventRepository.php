<?php 
/** 
 * 消息管理类
 * 
 */

namespace App\Repositories\Wechat;

use App\Repositories\BaseRepository;
use App\Repositories\Tools\EncryptTool;
use App\Models\WechatEventModel;
use App\Repositories\Wechat\WxAuthorizeRepository;

class WxEventRepository extends BaseRepository
{
	protected $wxevent;
	protected $wxauthorize;

	public function __construct(WechatEventModel $wxevent, WxAuthorizeRepository $wxauthorize)
	{
		$this->wxevent = $wxevent;
		$this->wxauthorize = $wxauthorize;
	}

	/** 
	 * 新增事件
	 * 
	 * @param     [array]      $args [消息推送数据]
	 * @return    [type]            [description]
	 */
	public function newEvent($args)
	{
		$result = [];

		// 1. 初始化数据
		$data = $this->parseField($args);
		if (!isset($data['event']) || !$data['event']) {
			return [];
		}

		// 2. 关注/取消公众号，保存用户授权信息
		if (
			in_array($data['event'], ['subscribe','unsubscribe']) 
			&& 
			$data['from_user_name']) {
			// 获取用户授权
			switch ($data['event']) {
				case 'subscribe':
					# 关注公众号,保存授权信息
					$this->wxauthorize->subscribe($data['from_user_name']);
					break;
				case 'unsubscribe':
					# 取消关注公众号, 变更关注状态
					$this->wxauthorize->unsubscribe($data['from_user_name']);
					break;
			}
		}

		// 3. 检测消息是否已保存,存在直接返回
		$result = isset($data['menu_id']) ? $this->existsEventId($data['from_user_name'], $data['event'], $data['menu_id']) : false;
		if ($result) {
			// 事件次数自增
			$result = $result->toArray();
			$this->wxevent->where('id', $result['id'])->increment('event_num', 1);
			$result['event_num'] += 1;

			return $result;
		}

		// 保存消息
		$data['event_num'] = 1; // 默认事件次数为1
		$result = $this->wxevent::create($data);

		
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
		$allow_field = $this->wxevent->filter_field();
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
	public function existsEventId($from_user_name, $event, $menu_id)
	{
		return $this->wxevent->where(['from_user_name'=>$from_user_name, 'event'=>$event, 'menu_id'=>$menu_id])->first();
	}
}