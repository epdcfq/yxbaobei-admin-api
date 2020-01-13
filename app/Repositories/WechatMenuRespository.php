<?php 
/** 
 * 微信公众号菜单管理类
 * 
 */

namespace App\Repositories;

use App\Models\WechatMenuModel;

class WechatMenuRepository
{
	protected $menu;
	/** 
	 * 注入menu类
	 * 
	 * @param     WechatMenuModel $menu [description]
	 */
	public function __construct(WechatMenuModel $menu) 
	{
		$this->menu = $menu;
	}

	public function menuList($org_id)
	{
		
	}
}