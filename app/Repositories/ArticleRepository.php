<?php
/** 
 * 文章管理类
 * 
 */

namespace App\Repositories;

use App\Models\ArticleModel;
use App\Models\ArticleContentModel;

class ArticleRepository
{
	protected $article;
	protected $img_host;

	// 构造类
	public function __construct(ArticleModel $article) 
	{
		$this->article = $article;
		$this->img_host = env('APP_IMG_URL', 'http://127.0.0.1:000');
	}

	public function list()
	{
		$sql = 'select * from f_org_article where id>:id';
		return $this->article->queryAll($sql, [':id'=>1], 1, 3);
	}

	/** 
	 * 根据id获取文章详情
	 * 
	 * @param     [int]      $id          [文章id]
	 * @param     boolean     $withContent [是否获取文章详情]
	 * @return    [type]                   [description]
	 */
	public function getArticleById($id, $withContent=true)
	{
		if (!$id) {
			return [];
		}

		$data = $this->article::find($id);
		$data['focus_img'] = $this->parseImg($data['focus_img']);
		$data['swiper_imgs'] = $this->parseImg($data['swiper_imgs']);
		return $data;
	}

	/** 
	 * 过滤保存文章数据
	 * 
	 * @param     [array]      $data [待保存的数据]
	 * @return    [type]            [description]
	 */
	public function filterArticleData($data)
	{
		$allow_field = $this->article->filter_field($data);
		if (isset($allow_field['focus_img']) && $allow_field['focus_img']) {
			$allow_field['focus_img'] = parse_url($allow_field['focus_img'])['path'];
		}
		if (isset($allow_field['status']) && $allow_field['status']) {
			$allow_field['status'] = intval($allow_field['status']);
		}
		return $allow_field;
	}

	/** 
	 * [新增|编辑] 更新文章内容
	 * 
	 * @param     [array]      $data [description]
	 * 
	 * @return    [type]            [description]
	 */
	public function modifyArticle($data)
	{
		$result = false;
		if (!$data) {
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

	public function articleList($args)
	{

		$per_page = isset($args['per_page']) && $args['per_page']>0 ? intval($args['per_page']) : 10;
		$where = $this->buildWhere($args);
		$where = $this->buildWhere($args);
		$result = $this->article->where($where)->paginate($per_page)->toArray();
		$result['data'] = $this->parseList($result['data']);
		return $result;
	}

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
			$v['focus_img'] = $this->parseImg($v['focus_img']);
			$v['swiper_imgs'] = $this->parseImg($v['swiper_imgs']);
			if (!$v['swiper_imgs']) {
				$v['swiper_imgs'] = $v['focus_img'];
			}
			$v['swiper_imgs'] = explode(',', $v['swiper_imgs']);
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
			$where[] = ['status', 99];
		}
		if (isset($args['dates']) && is_array($args['dates']) && $args['dates']) {
			list($start_date, $end_date) = $args['dates'];
			$where[] = ['start_time', '>=', strtotime($start_date)];
			$where[] = ['start_time', '<=', strtotime($end_date.' 23:59:59')];
		}

		return $where;
	}
}