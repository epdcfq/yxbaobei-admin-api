<?php 
/**
 * 资讯管理服务类
 * 
 */

namespace App\Repositories\Goods;

use App\Repositories\Goods\GoodsRepository;

class ProductRepository extends GoodsRepository
{
	public function pageProductList($org_id, $params=[], $page=1, $per_page=10)
	{
		return $this->pageGoodsList($org_id, $params, $page, $per_page);
	}

	public function getProductById($id)
	{
		$data = $this->getGoodsById($id);
		// $data->article = '';
		if ($data) {
			$data->product;
		}

		// 文章内容容错处理
		// if (!isset($data['article']) || !$data['article']) {
		// 	$data['article'] = (Object) array_fill_keys($this->getField('article'), '');
		// }
		// die($data->toSql());
		return $data;
	}

}