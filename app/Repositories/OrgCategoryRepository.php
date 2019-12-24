<?php 
/** 
 * 商品分类管理服务类
 * 
 */

namespace App\Repositories;

use App\Models\CategoryModel;

class OrgCategoryRepository
{
	protected $cate;
	public function __construct(CategoryModel $cate)
	{
		$this->cate = $cate;
	}

	/** 
	 * [新增|编辑] 更新文章内容
	 * 
	 * @param     [array]      $data [description]
	 * 
	 * @return    [type]            [description]
	 */
	public function storeCategory($data)
	{
		$result = false;
		$id = isset($data['id']) ? intval($data['id']) : 0;
		$filter_data = $this->filterCateData($data);
		// return $filter_data;
		try {
			$result = false;
			if ($id>0) {
				$result = $this->cate::where(['id'=>$id])->update($filter_data);
			} else {
				// 方式一：字段赋值
				foreach ($filter_data as $k=>$v) {
					$this->cate->$k = $v;
				}
				$result = $this->cate->save();
				// 方式二：配合model类变量fillable
				// $result = $this->cate::create($filter_data);
			}
		} catch (Exception $e) {
			$result = $e->getMessage();
		}
		
		return ['data'=>$data, 'result'=>$result, 'filter_data'=>$filter_data];
	}

	public function changeCateStatus($id)
	{
		if (!$id) {
			return false;
		}
		return $this->cate::update(['status'=>0])->where(['id'=>$id]);
	}

	public function removeCate($id)
	{
		if (!$id) {
			return false;
		}
		return $this->cate::where(['id'=>$id])->delete();
	}

	/** 
	 * 过滤保存文章数据
	 * 
	 * @param     [array]      $data [待保存的数据]
	 * @return    [type]            [description]
	 */
	public function filterCateData($data)
	{
		$allow_field = $this->cate->filter_field($data);
		if (isset($allow_field['icon_img']) && $allow_field['icon_img']) {
			$allow_field['icon_img'] = parse_url($allow_field['icon_img'])['path'];
		}
		if ($allow_field['status']) {
			$allow_field['status'] = intval($allow_field['status']);
		}
		// 扩展信息转json
		if (isset($allow_field['ext'])) {
			$allow_field['ext'] = is_array($allow_field['ext']) ? json_encode($allow_field['ext']) : '';
		}
		return $allow_field;
	}

	/** 
	 * 分类查询列表
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function listCategory($args)
	{
		$result = [];

		$where = $this->buildWhere($args);
		// $where = ['status', 1];
		// print_r($where);die;
		$data = $this->cate->where($where)->get()->toArray();
		foreach ($data as &$info) {
			$info = $this->parseData($info);
		}
		return $data;
	}

	// 组建查询提交
	protected function buildWhere($args)
	{
		$where = [];
		// 频道分类查询
		if (isset($args['channel_id']) && $args['channel_id']) {
			$where[] = ['channel_id', intval($args['channel_id'])];
		}
		// 父类查询
		if (isset($args['parentid']) && $args['parentid']) {
			$where[] = ['parentid', intval($args['parentid'])];
		}
		if (isset($args['status']) && $args['status'] !== '' && in_array($args['status'], [0,1])) {
			$where[] = ['status', ($args['status'])];
		} else {
			$where[] = ['status', '1'];
		}
		return $where;
	}

	// 初始化单条数据
	protected function parseData($info)
	{
		if (!$info) {
			return $info;
		}

		$info['status_name'] = $this->cate->getStatusName($info['status']);
		return $info;
	}
}