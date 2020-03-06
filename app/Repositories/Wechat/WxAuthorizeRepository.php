<?php 
/** 
 * 微信授权相关服务
 * 
 */

namespace App\Repositories\Wechat;

use App\Models\WechatAuthorizeModel;

class WxAuthorizeRepository
{
	protected $wxauth;
	protected $app;

	public function __construct(WechatAuthorizeModel $wxauth)
	{
		$this->wxauth = $wxauth;
		$this->app = app('wechat.official_account');
	}

	/******************** [1] 微信授权基本操作 ********************/
	/** 
	 * 根据一个已授权openid获取用户信息(关注公众号openid可直接获取信息)
	 * 
	 * @param     [string]      $open_id [单个用户授权openid]
	 * @return    [type]               [description]
	 */
	public function authorizeByOpenId($openId)
	{
		$result = [];
		if (!$openId) {
			return $result;
		}

		// 未授权，从微信授权获取
		$result = $this->app->user->get($openId);

		return $result;
	}

	/** 
	 * 根据多个授权openid获取用户信息
	 * 
	 * @param     [array]      $open_ids [多个用户openid]
	 * @return    [type]                [description]
	 */
	public function authorizeByOpenIds($openIds)
	{
		$result = [];
		// 格式转换成数组,过滤空/重复值
		$openIds = array_unique(array_filter(is_array($openIds) ? $openIds : explode(',', $openIds)));
		if (!$openIds) {
			return $result;
		}

		// 批量获取用户授权信息
		$result = $this->app->user->select($openIds);

		return $result;
	}

	/** 
	 * 根据用户授权openid修改用户备注
	 * 
	 * @param     [type]      $openid [description]
	 * @param     [type]      $remark [description]
	 * @return    [type]              [description]
	 */
	public function remarkByOpenId($openId, $remark)
	{
		if (!$openId || !$remark) {
			return false;
		}

		return $this->app->user->remark($openId, $remark);
	}

	/** 
	 * 根据openid拉黑用户
	 * 
	 * @param     [string|array]      $openIds [一个或多个授权openid]
	 * @return    [type]               [description]
	 */
	public function blockByOpenId($openIds)
	{
		$result = [];
		// 格式转换成数组,过滤空/重复值
		$openIds = array_unique(array_filter(is_array($openIds) ? $openIds : explode(',', $openIds)));
		if (!$openIds) {
			return $result;
		}

		return $this->app->user->block($openIds);
	}


	/******************* [2] 系统业务处理 *******************/
	/** 
	 * 新增/更新授权信息
	 * 
	 * @param     [type]      $data   [description]
	 * @param     array       $source [description]
	 */
	public function addAuthorize($data, $source=[])
	{
		$result = [''=>0, 'auth_source_id'=>0, 'state'=>'insert'];

		// f_wechat_authorize索引id
		$id = isset($data['id']) ? $data['id'] : 0;
		// 过滤授权数据
		$data_field = $this->filterAuthData($data);
		if (!$data_field 
			|| 
			(!$data_field['openid'] && !$data_field['unionid'])) {
			return $result;
		}

		try {
			// 获取数据库记录
			$authorInfo = $this->wxauth->where('openid', $data_field['openid']);
			if ($data_field['unionid']) {
				$authorInfo->orWhere('unionid', $data_field['unionid']);
			}
			$authorInfo = $authorInfo->first();

			// 保存授权数据
			if (!$authorInfo) {
				// 新增授权信息
				$result = $this->newAuthorize($data_field);
			} else {
				// 更新授权信息
				$result = $this->updateAuthorizeById($authorInfo->id, $data_field);
			}
		} catch (Exception $e) {
			$result = $e->getMessage();
		}
		
		return $result;
	}

	/** 
	 * 新增用户授权信息
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function newAuthorize($data)
	{
		if (!$data) {
			return false;
		}
		return $this->wxauth::create($data);
	}

	/** 
	 * 更新用户授权信息
	 * 
	 * @param     [type]      $id   [description]
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function updateAuthorizeById($id, $data)
	{
		if (!$id || !$data) {
			return false;
		}

		$data_field = $this->filterAuthData($data);
		$state = $this->wxauth->where(['id'=>$id])->update($data_field);
		if (!$state) {
			return false;
		}

		return $this->wxauth->where(['id'=>$id])->first();
	}

	/** 
	 * 根据openId获取授权信息
	 * 
	 * @param     [type]      $openId [description]
	 * @return    [type]              [description]
	 */
	public function getAuthorizeByOpenId($openId, $from_wx=true)
	{
		if (!$openId) {
			return false;
		}

		// 1. 从数据库中获取
		$result = $this->wxauth->where(['openid'=>$openId])->first();
		if ($result) {
			return $result;
		}

		// 2. 数据库不存在，指定从微信授权获取
		if ($from_wx) {
			$result = $this->authorizeByOpenId($openId);
		}

		return $result;
	}
	
	/** 
	 * 根据授权id获取授权信息
	 * 
	 * @param     [string]      $openid  [用户授权openid]
	 * @param     [string]      $unionid [用户授权unionid]
	 * 
	 * @return    [array]
	 */
	public function getwxauthInfo($openid, $unionid)
	{
		$where = [];
		if ($openid) {
			$where[] = ['openid'=>$openid];
		}
		if ($unionid) {
			$where[] = ['unionid'=>$unionid];
		}

		if (!$where) {
			return [];
		}

		return $this->wxauth->where($where)->first();
	}

	/** 
	 * 过滤授权数据
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function filterAuthData($data)
	{
		// 根据model类配置字段筛选
		$data = $this->wxauth->filter_field($data);
		if (!$data) {
			return $data;
		}

		foreach ($data as $k=>&$info) {
			if ($k == 'tagid_list' && is_array($info)) {
				$info = implode(',', $info);
			}
			$info = is_array($info) ? json_encode($info) : (string)$info;
		}
		// unionid兼容处理
		if (!isset($data['unionid'])) {
			$data['unionid'] = '';
		}
		// openid兼容处理
		if (!isset($data['openid'])) {
			$data['openid'] = '';
		}

		return $data;
	}

	/** 
	 * 关注公众号
	 * 
	 * @param     [type]      $open_id [description]
	 * @return    [type]               [description]
	 */
	public function subscribe($openId)
	{
		// 1. 微信授权信息是否存在
		$authorInfo = $this->authorizeByOpenId($openId);
		if (!$authorInfo) {
			return false;
		}

		// 更新授权信息
		$authorInfo['unsubscribe_time'] = 0;
		return $this->addAuthorize($authorInfo);
	}

	/** 
	 * 取消关注公众号
	 * 
	 * @param     [type]      $openId [description]
	 * @return    [type]              [description]
	 */
	public function unsubscribe($openId)
	{
		$data = [
			'subscribe'=>0,	# 取消关注
			'unsubscribe_time' => time()	# 取消关注时间
		];
		return $this->wxauth->where(['openid'=>$openId])->update($data);
	}


	/** 
	 * 微信授权信息列表
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function listwxauth($args)
	{
		$where = $this->buildQuery($args);
		$result = $this->wxauth->where($where)->paginate($per_page)->toArray();
	}

	protected function buildQuery($args)
	{
		$where = [];
		if (iset($args['org_id']) && $args['org_id']) {
			$where[] = ['org_id'=>intval($args['org_id'])];
		}

		return $where;
	}
}