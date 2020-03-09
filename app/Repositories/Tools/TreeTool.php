<?php 
/** 
 * 将一维数组转换为children多维数组
 * 
 */

namespace App\Repositories\Tools;

class TreeTool
{
	// 构建树形数组
	public static function build($data, $parent_id=0)
	{
		$result = [];
		foreach ($data as $cate) {
			if ($cate['parent_id'] == $parent_id) {
				// 菜单权限
				// $cate['permission'] = $this->getRowPermission($cate['status']);
				// $cate['status_name'] = $cate['status'] ? '上架' : '下架';
				// 获取子分类
				$children = self::build($data, $cate['id']);
				if ($children) {
					$cate['children'] = $children;
				}
				// 记录分类数据
				$result[] = $cate;
			}
		}
		return $result;
	}
}