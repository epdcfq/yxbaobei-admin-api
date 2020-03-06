<?php 
/**
 * 资讯管理服务类
 * 
 */

namespace App\Repositories\Goods;

use App\Repositories\Goods\GoodsRepository;

class ArticleRepository extends GoodsRepository
{
	public function pageArticleList($org_id, $params=[], $page=1, $per_page=10)
	{
		return $this->pageGoodsList($org_id, $params, $page, $per_page);
	}

	public function getArticleById($id)
	{
		$data = $this->getGoodsById($id);
		// $data->article = '';
		if ($data) {
			$data->article;
		}

		// 文章内容容错处理
		// if (!isset($data['article']) || !$data['article']) {
		// 	$data['article'] = (Object) array_fill_keys($this->getField('article'), '');
		// }
		// die($data->toSql());
		return $data;
	}

	public function createGoodsArticle($data)
	{
		$result = ['goods'=>false, 'article'=>false];

		// 1. 新增商品主表数据
		$result['goods'] = $this->createGoods($data);
		// 商品主表数据保存失败或没有文章详情，直接返回
		if (!$result['goods']['id'] || !isset($data['article'])) {
			return $result;
		}

		// 根据商品类型增加附属信息
		$type = $data['type'] ? $data['type'] : 'article';
		switch ($type) {
			case 'article':
				// 文章附加表
				$result['article'] = $this->createArticle($result['goods']['id'], $data['article']);
				break;
			case 'product':
				// 产品附加表
				$result['product'] = $this->createProduct($result['goods']['id'], $data['article']);
				break;
			
			default:
				# code...
				break;
		}
		

		return $result;
	}

	public function updateGoodsArticle($goods_id, $data)
	{
		$result = ['goods'=>false, 'article'=>false];
		if (!$data) {
			return false;
		}

		// 1. 更新商品主表
		$result['goods'] = $this->updateGoods($goods_id, $data);
		// [finish-1] 无更新文章详情，直接返回结果
		if (!isset($data['article']) && !$data['article']) {
			return $result;
		}

		// 2. 更新文章详情
		$article_id = isset($data['article']['id']) ? intval($data['article']['id']) : 0;
		if ($article_id>0) {
			$result['article'] = $this->editArticle($article_id, $data['article']);
		} else {
			$result['article'] = $this->createArticle($goods_id, $data['article']);
		}
		
		return $result;
	}
}