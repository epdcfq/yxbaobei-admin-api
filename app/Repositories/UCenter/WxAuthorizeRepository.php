<?php 
/** 
 * 微信授权相关服务
 * 
 */
namespace App\Repositories\UCenter;

use App\Models\UCenter\WxAuthModel;

class WxAuthorizeRepository
{
	protected $auth;

	public function __construct(WxAuthModel $auth)
	{
		$this->auth = $auth;
	}

	public function addAuth($data, $source=[])
	{
		$result = ['auth_id'=>0, 'auth_source_id'=>0, 'status'=>['auth'=>false]];

		// 过滤授权数据
		$filter_data = $this->filterAuthData($data);
		if (!$filter_data 
			|| 
			(!$filter_data['openid'] && !$filter_data['unionid'])) {
			return $result;
		}

		try {
			if ($filter_data['openid']) {
				$where = ['openid'=>$filter_data['openid']];
			}
			if ($filter_data['unionid']) {
				$where = ['unionid'=>$filter_data['unionid']];
			}
			$authInfo = false;
			$authorInfo = $this->auth->firstOrNew($where, $filter_data);
			if ($authInfo) {
				$authInfo = $authInfo->asArray();
			}

			// 保存授权信息
			$authInfo = $this->getAuthInfo($data['openid'], $data['unionid']);
			if ($authInfo) {
				// 更新授权内容
				$result['status']['auth'] = $this->auth->where(['id'=>$authInfo])->update($authInfo);
			} else {
				// 新增授权信息,并更新返回状态
				$authInfo = $this->auth::create($authInfo);
				if ($authInfo) {
					$authInfo = $authInfo->asArray();
					$result['status']['auth'] = true;
				}
			}

		} catch (Exception $e) {
			$result = $e->getMessage();
		}
		
		return ['data'=>$data, 'result'=>$result, 'filter_data'=>$filter_data];
	}
	
	/** 
	 * 根据授权id获取授权信息
	 * 
	 * @param     [string]      $openid  [用户授权openid]
	 * @param     [string]      $unionid [用户授权unionid]
	 * 
	 * @return    [array]
	 */
	public function getAuthInfo($openid, $unionid)
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

		return $this->auth->where($where)->first();
	}

	/** 
	 * 过滤授权数据
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function filterAuthData($data)
	{
		$data = $this->auth->filter_field($data);
		foreach ($data as $k=>$info) {
			$info = is_array($info) ? json_encode($info) : $info;
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
	 * 微信授权信息列表
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function listAuth($args)
	{
		$where = $this->buildQuery($args);
		$result = $this->auth->where($where)->paginate($per_page)->toArray();
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