<?php 
/** 
 * 门店网站基类
 * 
 */

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WebController extends BaseController
{
	// 全局参数
	protected $_globals;

	public function getOrgById($org_id)
	{
		if (isset($this->_globals['orgInfo'])) {
			return $this->_globals['orgInfo'];
		}

		$org = \App\Models\OrgInfoModel::find($org_id);
		if ($org) {
			$org->templates;
		}

		$this->_globals['orgInfo'] = $org->toArray();
		// 初始化导航列表
		$this->parseNav();

		return $this->_globals['orgInfo'];
	}

	/** 
	 * 根据导航标识,获取导航信息
	 * 
	 * @param     [type]      $action_name [description]
	 * @return    [type]                [description]
	 */
	protected function getCurNav($action_name='')
	{
		$action_name = $action_name ? $action_name : $this->getRouteName();
		if (!$this->_globals['nav'] || !isset($this->_globals['nav'][$action_name])) {
			return '';
		}

		return $this->_globals['nav'][$action_name];
	}

	/** 
	 * 初始化导航信息
	 * 
	 * @return    [type]      [description]
	 */
	private function parseNav()
	{
		if (isset($this->_globals['nav'])) {
			return $this->_globals['nav'];
		}
		$this->_globals['nav'] = [];
		if (!isset($this->_globals['orgInfo']) || !$this->_globals['orgInfo']['templates']) {
			return $this->_globals['nav'];
		}
		// 排序
		$sort = [];
		foreach ($this->_globals['orgInfo']['templates'] as $tmp) {
			// 失效菜单不显示
			if (!$tmp['status']) {
				continue;
			}
			$key = $tmp['sort'].'_'.$tmp['filename'];
			$sort[$key] = $tmp;
		}
		ksort($sort);

		foreach ($sort as $info) {
			$this->_globals['nav'][$info['filename']] = $info;
		}
		return $info;
	}

	public function getRouteName()
	{
		return str_replace(['pc_', 'h5_'], '', request()->route()->getAction()['as']);
	}

	protected function getCurTempInfo($action_name='')
	{
		if (isset($this->_globals['curNav'])) {
			return $this->_globals['curNav'];
		}
		$this->_globals['curNav'] = $this->getCurNav($action_name);
		if ($this->_globals['curNav']) {
			$this->_globals['curNav']['view_filename'] = $this->_globals['curNav']['template_path'].'/'.$this->_globals['curNav']['filename'];
		}
		return $this->_globals['curNav'];
	}

	protected function setView($path, $args=[])
	{
		$args['_globals'] = $this->_globals;
		isset($_GET['debug']) && print_r($args['_globals']);
		return view($path, $args);
	}

}