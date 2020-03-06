<?php 
/** 
 * 小程序服务类
 * 
 */

namespace App\Repositories\Wechat;

class MinpRepository {
	protected $minp;
	/** 
	 * 构造类
	 * 
	 * @param     WechatAuthorizeModel $wxauth [description]
	 */
	public function __construct()
	{
		$this->minp = app('wechat.mini_program');
	}

}