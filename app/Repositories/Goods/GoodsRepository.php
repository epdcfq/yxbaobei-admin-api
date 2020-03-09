<?php
/**
 * 门店商品管理基类(资讯+商品)
 *
 */

namespace App\Repositories\Goods;

use App\Models\GoodsModel;
use App\Models\GoodsArticleModel;
use App\Models\GoodsProductModel;

class GoodsRepository
{
	protected $model;
	// 商品主表model类
	protected $goods;
	// 文章表model类
	protected $article;
	// 商品表model类
	protected $product;
	// 图片域名
	protected $img_host;
	// 分页条数
	protected $psize=10;
	// 允许的类型表字段
	protected $table_type = ['goods', 'article', 'product'];

	// 构造类
	public function __construct(GoodsModel $goods, GoodsArticleModel $article, GoodsProductModel $product)
	{
		$this->model['goods'] = $goods;
		$this->model['article'] = $article;
		$this->model['product'] = $product;

		$this->goods 	= $goods;
		$this->article 	= $article;
		$this->product 	= $product;
		$this->img_host = env('APP_IMG_URL', 'http://127.0.0.1:000');
	}

	// 获取表字段列表或过滤数据
	public function getField($type, $data=[])
	{
		$field = [];
		if (!in_array($type, $this->table_type)) {
			return $field;
		}

		switch ($type) {
			case 'article':
				return $this->article->filter_field($data);
				break;
			case 'product':
				return $this->product->filter_field($data);
			default:
				return $this->goods->filter_field($data);
				break;
		}
	}

	/**
	 * 根据id获取文章详情
	 *
	 * @param     [int]      $id          [文章id]
	 * @param     boolean     $withContent [是否获取文章详情]
	 * @return    [type]                   [description]
	 */
	public function getGoodsById($id, $withContent=true)
	{
		if (!$id) {
			return [];
		}

		$data = $this->goods::find($id);
		if ($data) {
			switch ($data['type']) {
				case 'article':
					$data->article;
					break;
				case 'product':
					$data->product;
					break;
				default:
					# code...
					break;
			}
		}
		// $data['cover_pic'] = $this->parseImg($data['cover_pic']);
		// $data['show_cover_pic'] = $this->parseImg($data['show_cover_pic']);
		return $data;
	}

	/**
	 * 过滤保存文章数据
	 *
	 * @param     [array]      $data [待保存的数据]
	 * @return    [type]            [description]
	 */
	public function filterGoodsData($data)
	{
		$allow_field = $this->article->filter_field($data);
		if (isset($allow_field['cover_pic']) && $allow_field['cover_pic']) {
			$allow_field['cover_pic'] = parse_url($allow_field['cover_pic'])['path'];
		}
		if (isset($allow_field['status']) && $allow_field['status']) {
			$allow_field['status'] = intval($allow_field['status']);
		}
		return $allow_field;
	}

	/** 
	 * 新增商品主表数据
	 * 
	 * @param     [array]      $data [待新增数组数据]
	 * 
	 * @return    [array]            [返回新增商品数据(含主键id)]
	 */
	public function createGoods($data)
	{
		// 获取商品表有效字段数据
		$goodsData = $this->getField('goods', $data);
		if ($goodsData) {
			// 设置新增数据
			unset($goodsData['id']);
			$this->goods->setRawAttributes($goodsData);
			if ($this->goods->save()) {
				$goodsData = $this->goods->getAttributes();
			}
		}

		if (!isset($goodsData['id'])) {
			$goodsData['id'] = 0;
		}

		return $goodsData;
	}

	/** 
	 * 更新商品主表数据
	 * 
	 * @param     [int]			$id 	[商品表主键id]
	 * @param     [array]      	$data 	[待更新数组数据]
	 * 
	 * @return    [array]            [返回新增商品数据状态(含主键id)]
	 */
	public function updateGoods($id, $data)
	{
		// 初始化数据
		$id = intval($id);
		if (isset($data['id'])) {
			unset($data['id']);
		}
		// 参数有效性校验
		if (!$id || !$data || !is_array($data)) {
			return false;
		}

		// 获取商品详情
		$goods = $this->getGoodsById($id);
		if (!$goods) {
			return false;
		}
		// 待更新商品表数据
		$goodsData = $this->getField('goods', $data);
		if (!$goodsData) {
			return false;
		}
		// 执行更新
		$goods->setRawAttributes($goodsData);
		if ($result = $goods->save()) {
			// 保存成功，返回修改后数据
			$goods = $goods->getAttributes();
			if (!isset($goods['id'])) {
				$goods['id'] = $id;
			}
			
			return $goods;
		} else {
			return $result;
		}
	}

	/**
	 * [新增|编辑] 更新文章内容
	 *
	 * @param     [array]      $data [description]
	 *
	 * @return    [type]            [description]
	 */
	public function modifyGoods($data)
	{
		$result = false;
		if (!$data || !is_array($data)) {
			return $result;
		}
		$id = isset($data['id']) ? intval($data['id']) : 0;
		$filter_data = $this->filterArticleData($data);
		try {
			$result = false;
			if ($id>0) {
				$result = $this->article::where(['id'=>$id])->update($filter_data);
			} else {
				// 方式一：字段赋值
				// foreach ($filter_data as $k=>$v) {
				// 	$this->article->$k = $v;
				// }
				// $result = $this->article->save();
				// 方式二：配合model类变量fillable
				$result = $this->article::create($filter_data);
			}
		} catch (Exception $e) {
			$result = $e->getMessage();
		}

		return ['data'=>$data, 'result'=>$result, 'filter_data'=>$filter_data];
	}

	protected function parseImg($imgs)
	{
		$imgs = array_filter(explode(',', $imgs));
		if (!$imgs) {
			return implode(',', $imgs);
		}

		foreach ($imgs as &$img) {
			if (!$img) {
				continue;
			}
			$img = $this->img_host.parse_url($img)['path'];
		}

		return implode(',', $imgs);
	}

	/** 
	 * 商品分页列表
	 * 
	 * @param     [type]      $type     [description]
	 * @param     [type]      $args     [description]
	 * @param     integer     $page     [description]
	 * @param     integer     $per_page [description]
	 * @return    [type]                [description]
	 */
	public function pageGoodsList($type, $args, $page=1, $per_page=10)
	{
		$where = $this->buildPageWhere($type, $args);
		$sql = 'select * from f_org_goods where '.$where;
		$data['list'] = $this->goods->queryAll($sql, [], 'id DESC', $page, $per_page);
		$total = $this->goods->queryOne(str_replace('*', 'COUNT(*) AS num', $sql))['num'];
		$data['pagination'] = ['total'=>$total, 'page'=>$page, 'per_page'=>$per_page];
		$data['where'] = $where;
		return $data;
	}

	// 根据查询参数拼接查询条件
	public function buildPageWhere($type, $args)
	{
		$where = [];
		if (isset($args['header.orgid']) && $args['header.orgid']) {
			$where[] = 'org_id='.$args['header.orgid'];
		}
		if (isset($args['type'])) {
			$where[] = "type='".$args['type']."'";
		}
		if (isset($args['title'])) {
			$where[] = "title LIKE '%".$args['title']."%'";
		}
		if (isset($args['status'])) {
			$where[] = 'status='.intval($args['status']);
		}
		return implode(' AND ', $where);
	}

	// public function articleList($args)
	// {

	// 	$per_page = isset($args['per_page']) && $args['per_page']>0 ? intval($args['per_page']) : 10;
	// 	$where = $this->buildWhere($args);
	// 	$where = $this->buildWhere($args);
	// 	$result = $this->article->where($where)->paginate($per_page)->toArray();
	// 	$result['data'] = $this->parseList($result['data']);
	// 	return $result;
	// }

	protected function parseList($list)
	{
		if (!$list) {
			return $list;
		}

		foreach ($list as &$v) {
			// 状态
			$v['status_name'] = $this->article->getStatusName($v['status']);
			// 推荐星级
			$v['imp_star_name'] = $this->article->getImpStarName($v['imp_star']);
			// 发布状态
			$v['published_name'] = $this->article->getPublishedName($v['published']);
			// 处理域名图片
			$v['cover_pic'] = $this->parseImg($v['cover_pic']);
			$v['show_cover_pic'] = $this->parseImg($v['show_cover_pic']);
			if (!$v['show_cover_pic']) {
				$v['show_cover_pic'] = 0;
			}
			$v['show_cover_pic'] = explode(',', $v['show_cover_pic']);
		}
		return $list;
	}

	public function buildWhere($args)
	{
		$where = [];
		// 标题模糊搜索
		if (isset($args['title']) && $args['title']) {
			$where[] = ['title', 'like', '%'.$args['title'].'%'];
		}
		// 推荐星级
		if (isset($args['imp_star']) && $args['imp_star'] !== ''&& $args['status']!==null) {
			$where[] = ['imp_star', $args['imp_star']];
		}
		// 状态搜索
		if (isset($args['status']) && $args['status'] !== '' && $args['status']!==null) {
			$where[] = ['status', $args['status']];
		} else {
			$where[] = ['status', 1];
		}
		if (isset($args['dates']) && is_array($args['dates']) && $args['dates']) {
			list($start_date, $end_date) = $args['dates'];
			$where[] = ['start_time', '>=', strtotime($start_date)];
			$where[] = ['start_time', '<=', strtotime($end_date.' 23:59:59')];
		}

		return $where;
	}

	/****************** 文章附加表方法 ******************/
	/** 
	 * 新增文章详情
	 * 
	 * @param     [int]      $goods_id [f_org_goods主键id]
	 * @param     [array]    $data     [待新增文章详情数组]
	 */
	protected function createArticle($goods_id, $data)
	{
		// 参数有效性校验
		if (!$goods_id || !is_array($data)) {
			return false;
		}

		// 获取文章可批量赋值的字段数据
		$data['goods_id'] = intval($goods_id);
		$article = $this->getField('article', $data);
		if ($article) {
			// 设置新增数据
			unset($article['id']);
			$this->article->setRawAttributes($article);
			if ($this->article->save()) {
				$article = $this->article->getAttributes();
			}
		}
		if (!isset($article['id'])) {
			$article['id'] = 0;
		}
		// $article = $this->article::create($data);

		return $article;
	}

	/** 
	 * 更新文章详情
	 * 
	 * @param     [type]      $article_id [description]
	 * @param     [type]      $data       [description]
	 * @return    [type]                  [description]
	 */
	protected function editArticle($article_id, $data)
	{
		// 参数有效性校验
		$article_id = intval($article_id);
		if (!$article_id || !is_array($data)) {
			return false;
		}

		// 参数有效性过滤
		$field_data = $this->getField('article', $data);
		$article_id = isset($data['id']) ? intval($data['id']) : 0;
		return $this->article::where(['id'=>$article_id])->update($field_data);
	}

	/****************** [附加表] 相关方法 ******************/
	/** 
	 * [新增] 附加表数据
	 * 
	 * @param     [int]      $goods_id 	[f_org_goods主键id]
	 * @param     [enum]	 $type 		[附加表标识]
	 * @param     [array]    $data     	[待新增附加表数据数组]
	 * 
	 * @return [array]
	 */
	protected function createGoodsExt($goods_id, $type, $data)
	{
		// 参数有效性校验
		if (!$goods_id || !is_array($data)) {
			return false;
		}

		// 获取文章可批量赋值的字段数据
		$data['goods_id'] = intval($goods_id);
		$extData = $this->getField($type, $data);
		if ($extData) {
			// 设置新增数据
			unset($extData['id']);
			$this->model[$type]->setRawAttributes($extData);
			if ($this->model[$type]->save()) {
				$extData = $this->model[$type]->getAttributes();
			}
		}
		if (!isset($extData['id'])) {
			$extData['id'] = 0;
		}
		// $article = $this->article::create($data);

		return $extData;
	}

	/** 
	 * [更新] 附加表数据
	 * 
	 * @param     [int]      $goods_id 	[goods主键id]
	 * @param     [int]      $ext_id 	[附加表主键id]
	 * @param     [enum]	 $type 		[附加表标识]
	 * @param     [array]    $data    	[description]
	 */
	protected function updateGoodsExt($goods_id, $ext_id, $type, $data)
	{
		// 参数有效性校验
		$ext_id = intval($ext_id);
		if (!$ext_id || !is_array($data)) {
			return false;
		}

		// 参数有效性过滤
		$ext_data = $this->getField($type, $data);
		if ($ext_id>0) {
			// [查询] 获取扩展信息数据
			$ext = $this->model[$type]->find($ext_id);
			// [返回] 无数据，新增记录
			if (!$ext) {
				return $this->createGoodsExt($goods_id, $type, $data);
			}

			// [保存] 设置表数据，保存
			$ext->setRawAttributes($ext_data);
			if ($result = $ext->save()) {
				// [get] 获取更新后数据
				$ext_data = $ext->getAttributes();
				// 补全id
				if (!isset($ext_data['id'])) {
					$ext_data['id'] = $ext_id;
				}
			}
			return $ext_data;
		} else {
			return $this->createGoodsExt($goods_id, $type, $data);
		}
	}
	/****************** [商品主表+附加表] 方法 ******************/
	/** 
	 * [新增] 商品主表及附属表数据
	 * 
	 * @param     [array]      $data [新增数据数组]
	 * @return    [type]            [description]
	 */
	public function createGoodsAndExt($data)
	{
		$result = ['goods'=>false];

		// 1. 新增商品主表数据
		$result['goods'] = $this->createGoods($data);
		// 商品主表数据保存失败或没有文章详情，直接返回
		if (!$goods_id = $result['goods']['id']) {
			return $result;
		}

		// 2. 新增商品附属表数据
		$type = $data['type'] ? $data['type'] : 'article';
		$extData = isset($data[$type]) ? $data[$type] : [];
		$result[$type] = $this->createGoodsExt($goods_id, $type, $extData);

		return $result;
	}

	/** 
	 * [更新] 商品主表及附属表数据
	 * 
	 * @param     [array]      $data [新增数据数组]
	 * @return    [type]            [description]
	 */
	public function updateGoodsAndExt($goods_id, $data)
	{
		$result = ['goods'=>false];
		if (!$data) {
			return false;
		}

		// 1. [更新] 商品主表
		$result['goods'] = $this->updateGoods($goods_id, $data);
		// 更新失败，返回结果
		if (!$result['goods']) {
			return $result;
		}

		// 2. [校验] 是否更新附属表数据
		# 商品类型
		$type = $result['goods']['type'];
		# 没有更新的附属数据，直接返回
		if (!isset($data[$type]) && !$data[$type]) {
			return $result;
		}

		// 3. [更新] 附加产品表数据，无记录则新增
		$ext_id = isset($data[$type]['id']) ? intval($data[$type]['id']) : 0;
		$ext_data = $data[$type];
		if ($ext_id>0) {
			$result[$type] = $this->updateGoodsExt($goods_id, $ext_id, $type, $ext_data);
		} else {
			$result[$type] = $this->createGoodsExt($goods_id, $type, $ext_data);
		}
		
		return $result;
	}
}
