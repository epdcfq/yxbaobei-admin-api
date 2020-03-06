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
				if (!$this->hasRulePlat($plat, $args)) {
					unset($plats[$k]);
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
			$info['permission'] = ['edit'];

			$result[] = $info;
		}

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
	/** 
	 * [新增] 创建平台数据
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function createPlats($channel_id, $data)
	{
		$result = [];
		// 过滤无效字段
		$data_field = $this->models['plats']->filter_field($data);
		if (!$data_field) {
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
	public function updatePlats($id, $data)
	{
		$result = [];
		// 参数校验
		$id = intval($id);
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


		// 将数据保存到数据库
		$info->setRawAttributes($data_field);
		if ($info->save()) {
			// 获取新增记录数据
			$result = $info->getAttributes();
		}
		
		return $result;
	}

	/******************* [channel + plats] 所属平台操作方法 ************************/
	public function createChannelAndPlats($data)
	{
		$result = ['channel'=>false, 'plats'=>false];

		// 1. 新增商品主表数据
		$result['channel'] = $this->createChannel($data);
		// 插入失败或未传附属表数据，直接返回
		if (!$result['channel']['id'] || !isset($data['plats']) || !$data['plats']) {
			return $result;
		}

		// // 根据商品类型增加附属信息
		// $type = $data['type'] ? $data['type'] : 'article';
		// switch ($type) {
		// 	case 'article':
		// 		// 文章附加表
		// 		$result['article'] = $this->createArticle($result['goods']['id'], $data['article']);
		// 		break;
		// 	case 'product':
		// 		// 产品附加表
		// 		$result['product'] = $this->createProduct($result['goods']['id'], $data['article']);
		// 		break;
			
		// 	default:
		// 		# code...
		// 		break;
		// }
		

		return $result;
	}

	public function updateChannelAndPlats($channel_id, $data)
	{
		$result = ['channel'=>false, 'channel'=>false];
		if (!$data) {
			return false;
		}

		// 1. 更新栏目表
		$result['channel'] = $this->updateChannel($goods_id, $data);
		// [finish-1] 无更新文章详情，直接返回结果
		if (!isset($data['channel']) && !$data['channel']) {
			return $result;
		}

		// 2. 更新所属平台信息
		$article_id = isset($data['channel']['id']) ? intval($data['channel']['id']) : 0;
		if ($article_id>0) {
			$result['article'] = $this->editArticle($article_id, $data['article']);
		} else {
			$result['article'] = $this->createArticle($goods_id, $data['article']);
		}
		
		return $result;
	}
}