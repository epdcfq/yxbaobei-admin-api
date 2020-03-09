<?php 
/** 
 * 商品分类管理
 * 
 */
namespace App\Repositories\Org;

use App\Models\Org\GoodsCategoryModel;

class GoodsCategory 
{
	protected $category;

	public function __construct(GoodsCategoryModel $category)
	{
		$this->category = $category;
	}

	/** 
	 * 根据门店id获取分类列表数据
	 * 
	 * @param     [int]      $org_id [门店id]
	 * @param     [enum]      	$type   [类型(article:文章, product:商品)]
	 * @param     [string]      $status [状态 [空:显示全部; 0:已失效  1:有效]]
	 * 
	 * @return    [array]
	 */
	public function CategoryByOrgId($org_id, $type, $status='')
	{
		$result = [];
		if (!$org_id) {
			return $result;
		}

		/*** 查询列表数据 ***/
		$query = $this->category::where('org_id', $org_id);
		/*** 条件筛选 ***/
		// 类型查询
		if ($type !== '' && is_string($type)) {
			$query->where('type', $type);
		}
		// 状态查询
		if ($status !=='' && is_numeric($status)) {
			$query->where('status', $status);
		}
		// 获取列表数据
		$data = $query->orderBy('parent_id', 'ASC')->orderBy('sort', 'ASC')->get();
		if ($data->isEmpty()) {
			return $result;
		}
		
		// 转为数组
		$result = $data->toArray();
		// 初始化角色信息
		foreach ($result as &$info) {
			$this->parseCategory($info);
		}

		return $result;
	}

	public function parseCategory(&$data)
	{
		// 状态文案
		$data['status_name'] = $data['status'] ? '上架' : '下架';
		// 菜单角色
		$data['permission'] = $this->getRowPermission($data['status']);
	
		return $data;
	}

	/** 
	 * 根据门店获取商品分类
	 * 
	 * @param     [int]      	$org_id [门店id]
	 * @param     [enum]      	$type   [类型(article:文章, product:商品)]
	 * @param     [string]      $status [状态 [空:显示全部; 0:已失效  1:有效]]
	 * 
	 * @return    [array]
	 */
	public function categoryTreeByOrgId($org_id, $type, $status='')
	{
		$data = $this->categoryByOrgId($org_id, $type, $status);
		$result = \App\Repositories\Tools\TreeTool::build($data);
		return $result;

	}

	

	/** 
	 * 根据分类id获取详情
	 * 
	 * @param     [int]		$cate_id [分类id]
	 * 
	 * @return    [array]
	 */
	public function getCategoryById($cate_id)
	{
		$result = [];
		if (!$cate_id) {
			return $result;
		}

		$data = $this->category->find($cate_id);
		if (!$data) {
			return $result;
		}

		// 转化为数组
		$result = $data->first()->toArray();

		return $result;
	}

	public function getRowPermission($status) {
		if ($status==1) {
			return ['edit', 'add', 'down', 'add', 'addChild'];
		} else {
			return ['edit', 'up'];
		}
	}

	/** 
	 * 新增信息
	 */
	public function addCategory($data)
	{
		$result = [];
		// 过滤无效字段
		$data_field = $this->category->filter_field($data);
		if (!$data_field) {
			return $result;
		}

		// 将数据保存到数据库
		$this->category->setRawAttributes($data_field);
		if ($this->category->save()) {
			// 获取新增记录数据
			$result = $this->category->getAttributes();
		}
		
		return $result;
	}

	/** 
	 * 根据id，更新信息
	 * 
	 * @param     [int]		$id   [分类id]
	 * @param     [type]	$data [待更新的数据]
	 * 
	 * @return    [array]
	 */
	public function updateCategoryById($id, $data)
	{
		$result = [];
		// 过滤无效字段
		$data_field = $this->category->filter_field($data);
		if (!$id || !$data_field) {
			return $result;
		}

		// 根据id查询频道记录
		$info = $this->category->find($id);
		if (!$info) {
			return $result;
		}

		// 将数据保存到数据库
		$info->setRawAttributes($data_field);
		// 保存失败，返回空
		if (!$info->save()) {
			return $result;
		}

		// 获取更新记录数据
		$result = $info->getAttributes();
		
		return $result;
	}
}