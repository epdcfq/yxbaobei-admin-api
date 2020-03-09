<?php 
/**
 * 资讯管理服务类
 * 
 */

namespace App\Repositories\Org;

use App\Models\Org\ChannelModel;
use App\Models\Org\ChannelPlatsModel;


class ChannelRepository
{
	protected $models = [];

	public function __construct(ChannelModel $channel, ChannelPlatsModel $plats)
	{
		$this->models['channel'] = $channel;
		$this->models['plats'] = $plats;
	}
	/******************* [channel] 方法 ************************/

	/** 
	 * [单记录查询] 根据栏目id获取详情
	 * 
	 * @param     [int]      $channel_id [栏目]
	 * @return    [type]                  [description]
	 */
	public function getChannelById($channel_id)
	{
		if (!$channel_id) {
			return [];
		}
		$data = $this->models['channel']::where('id', $channel_id)->with('plats')->first();
		;
		return $data;
	}

	/** 
	 * [新增] 创建频道
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function createChannel($data)
	{
		$result = [];
		// 过滤无效字段
		$data_field = $this->models['channel']->filter_field($data);
		if (!$data_field) {
			return $result;
		}

		// 将数据保存到数据库
		$this->models['channel']->setRawAttributes($data_field);
		if ($this->models['channel']->save()) {
			// 获取新增记录数据
			$result = $this->models['channel']->getAttributes();
		}
		
		return $result;
	}

	/** 
	 * [编辑] 根据频道id，修改频道信息
	 * 
	 * @version   [version]
	 * @param     [type]      $id   [description]
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function updateChannel($id, $data)
	{
		$result = [];
		// 参数校验
		$id = intval($id);
		// 过滤无效字段
		$data_field = $this->models['channel']->filter_field($data);
		if (!$id || !$data_field) {
			return $result;
		}

		// 根据id查询频道记录
		$info = $this->models['channel']->find($id);
		if (!$info) {
			return $result;
		}


		// 将数据保存到数据库
		$info->setRawAttributes($data_field);
		if ($info->save()) {
			// 获取新增记录数据
			$result = $info->getAttributes();
		}
		
		return $result;
	}

	/** 
	 * 获取门店平台栏目列表
	 * 
	 * @param     [type]      $org_id  [description]
	 * @param     [type]      $plat_id [description]
	 * @param     array       $args    [description]
	 * @return    [type]               [description]
	 */
	public function getOrgPlatChannel($org_id, $args_plat, $args=[])
	{
		$result = [];
		if (!$org_id || !$args_plat) {
			return $result;
		}

		$args['type'] = 'channel';
		$data = $this->getOrgChannel($org_id, $args)->toArray();
		if (!$data) {
			return $result;
		}

		foreach ($data as $info) {
			// 过滤不是当前平台数据
			if (!$info['plats']) {
				continue;
			}
			$plats = $info['plats'];
			foreach ($plats as $k=>$plat) {
				// 过滤不符合条件数据
				if (!$this->hasRulePlat($plat, $args) || $plat['status'] !== 1) {
					unset($plats[$k]);
					continue;
				}
				$info['show_plats'][] = $plat['plat'];
			}
			// 无平台栏目，不显示
			if (!$plats) {
				continue;
			}
			$info['plats'] = array_values($plats);

			// 平台转换为字符串
			$info['show_plats'] = implode('/', $info['show_plats']);
			// 操作权限
			$info['permission'] = ['edit', 'addLevel', 'addChild'];
			if ($info['status']) {
				$info['permission'][] = 'down';
				$info['status_name'] = '上架';
			} else {
				$info['permission'][] = 'up';
				$info['status_name'] = '下架';
			}

			$result[] = $info;
		}

		$result = \App\Repositories\Tools\TreeTool::build($result);
		
		return $result;
	}

	/** 
	 * 平台栏目辅助方法
	 * 
	 * @param     [array]      $platInfo [平台信息]
	 * @param     [array]      $args     [平台查询参数]
	 * @return    boolean               [description]
	 */
	public function hasRulePlat($platInfo, $args)
	{
		if (isset($args['plat']) && $platInfo['plat']!==$args['plat']) {
			return false;
		}

		if (isset($args['status']) && $args['status']!=='' 
			&& $plat['status']!==intval($args['status'])
		) {
			return false;
		}

		return true;
	}

	/** 
	 * 门店分类列表
	 * 
	 * @param     [type]      $org_id [description]
	 * @param     array       $args   [description]
	 * @return    [type]              [description]
	 */
	public function getOrgChannel($org_id, $args=[])
	{
		if (!$org_id) {
			return [];
		}

		$data = $this->models['channel']
					->where('org_id', $org_id)
					->with('plats')
					->get();
		return $data;
	}

	/******************* [plats] 所属平台操作方法 ************************/
	public function getChannelPlats($channel_id, $status='') {
		$result = [];
		if (!$channel_id) {
			return $result;
		}
		// 根据栏目id查询平台信息
		$query = $this->models['plats']->where('channel_id', $channel_id);
		// 查询状态
		if ($status !== '' && is_numeric($status)) {
			$query->where('status', intval($status));
		}
		// 查询数据
		$data = $query->get();
		if (!$data) {
			return $result;
		}
		// 转化数组
		$result = $data->toArray();
		return $result;
	}
	/** 
	 * 根据栏目查询所属平台列表，并以平台id小标作为key返回
	 * 
	 * @param     [type]      $channel_id [description]
	 * @param     string      $status     [description]
	 * @return    [type]                  [description]
	 */
	public function getChannelPlatsKey($channel_id, $status='')
	{
		$result = [];
		$data = $this->getChannelPlats($channel_id, $status);
		if (!$data) {
			return $result;
		}
		foreach ($data as $plat) {
			$result[$plat['plat']] = $plat;
		}
		return $result;
	}
	/** 
	 * [新增] 创建平台数据
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function createPlat($channel_id, $data)
	{
		$result = [];
		if (!isset($data['channel_id'])) {
			$data['channel_id'] = $channel_id;
		}
		// 过滤无效字段
		$data_field = $this->models['plats']->filter_field($data);
		if (!$channel_id || !$data_field) {
			return $result;
		}

		// 将数据保存到数据库
		$this->models['plats']->setRawAttributes($data_field);
		if ($this->models['plats']->save()) {
			// 获取新增记录数据
			$result = $this->models['plats']->getAttributes();
		}
		
		return $result;
	}

	/** 
	 * [编辑] 根据频道id，修改频道信息
	 * 
	 * @version   [version]
	 * @param     [type]      $id   [description]
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function updatePlat($id, $data)
	{
		$result = [];
		// 参数校验
		$id = $id;
		if (isset($data['id'])) {
			unset($data['id']);
		}
		// 过滤无效字段
		$data_field = $this->models['plats']->filter_field($data);
		if (!$id || !$data_field) {
			return $result;
		}

		// 根据id查询频道记录
		$info = $this->models['plats']->find($id);
		if (!$info) {
			return $result;
		}
		// print_r($data_field);die;

		// 将数据保存到数据库
		$info->setRawAttributes($data_field);
		if ($info->save()) {
			// 获取新增记录数据
			$result = $info->getAttributes();
		}
		
		return $result;
	}

	/** 
	 * [删除平台] 根据栏目id和平台id，删除平台信息(暂时不提供，为避免id缺失)
	 * 
	 * @param     [int]      $channel_id [栏目id]
	 * @param     [int]      $plat_id    [平台id]
	 * 
	 * @return    [boolean]                  [删除状态]
	 */
	// private function deleteChannelPlat($channel_id, $plat_id)
	// {
	// 	if (!$channel_id || !$plat_id) {
	// 		return false;
	// 	}
	// 	return $this->models['plats']->where('channel_id', $channel_id)->where('id', $plat_id)->delete();
	// }

	/******************* [channel + plats] 所属平台操作方法 ************************/
	public function createChannelAndPlats($data)
	{
		$result = ['channel'=>false, 'plats'=>false];
		// 门店id
		if (isset($data['header.orgid']) && $data['header.orgid']) {
			$data['org_id'] = $data['header.orgid'];
		}
		// 1. 新增商品主表数据
		$result['channel'] = $this->createChannel($data);
		// 插入失败或未传附属表数据，直接返回
		if (!$result['channel']['id'] || !isset($data['plats']) || !$data['plats']) {
			return $result;
		}

		// 创建所属平台
		foreach ($data['plats'] as $plat) {
			$plat['channel_id'] = $result['channel']['id'];
			$result['plats'][] = $this->createPlat($result['channel']['id'], $plat);
		}
		
		return $result;
	}

	public function updateChannelAndPlats($channel_id, $data)
	{
		$result = ['channel'=>false, 'channel'=>false];
		if (!$data || !$channel_id) {
			return false;
		}
		// 1. 更新栏目表
		$result['channel'] = $this->updateChannel($channel_id, $data);
		// [finish-1] 无更新平台信息，直接返回结果
		if (!isset($data['plats']) || !$data['plats']) {
			return $result;
		}

		// 2. 对更新平台信息进行分组（增、删、更新）
		$plats = $this->getDiffPlats($channel_id, $data['plats']);
		foreach ($plats as $type=>$data) {
			// 无数据处理，走下次循环
			if (!$data) {
				continue;
			}
			foreach ($data as $key=>$value) {
				// [路由] 根据不同数据类型进行操作
				switch ($type) {
					case 'create':
						// 新增
						$value['channel_id'] = $channel_id;
						$result['plats']['create'][$value['plat']] = $this->createPlat($channel_id,$value);
						break;
					case 'update':
						// 更新
						$result['plats']['update'][$value['plat']] = $this->updatePlat($value['id'], $value);
						break;
				}
			}
			
		}
		
		return $result;
	}

	public function getDiffPlats($channel_id, $plats) {
		$result = ['create'=>[], 'update'=>[], 'delete'=>[]];
		// 获取栏目平台列表(以平台plat字段为下标)
		$channel_plats = $this->getChannelPlatsKey($channel_id);
		// [方式一] 频道没有所属平台数据，直接返回新增
		if (!$channel_plats) {
			$result['create'] = $plats;
			return $result;
		}

		// [方式二] 根据现有平台数据和要保存的平台数据做对比
		// 当前plats变量平台id列表
		$plat_tags = [];
		// 判断新增、更新平台数据
		foreach ($plats as $plat) {
			// 平台变量
			$tag = $plat['plat'];

			// [新增] 关联平台信息
			if (!array_key_exists($tag, $channel_plats)) {
				$result['create'][$tag] = $plat;
				continue;
			}

			// [更新] 关联平台
			if (isset($plat['id'])) {
				unset($plat['id']);
			}
			if (isset($plat['channel_id'])) {
				unset($plat['channel_id']);
			}

			$result['update'][$tag] = array_merge($channel_plats[$tag], $plat);
		}

		return $result;
	}
}