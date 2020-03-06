<?php 
/** 
 * JWT账号登录校验类
 * 
 */

namespace App\Repositories\UCenter;

use App\Repositories\BaseRepository;
use App\Repositories\UCenter\UCenterAccountRepository;

class AuthJwtRepository extends BaseRepository
{
	protected $account;

	public function __construct(UCenterAccountRepository $account)
	{
		$this->account = $account;
	}
	/** 
	 * 登录验证参数
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	protected function ruleLogin($args)
	{
		$args = array_filter($args);
		// 只允许密码或验证码登录/注册
		if (
			!$args 
			||
			count($args)<2
			||
			!(isset($args['code']) || isset($args['password']))
		) {
			return $this->codeMsg(1000, '缺少登录必要参数', $args);
		}

		// 手机号长度验证
		if ( !isset($args['phone']) || strlen($args['phone'])!=11) {
			return $this->codeMsg(1001, '请输入11位手机号,当前手机号'.strlen($args['phone']).'位', $args);
		}

		// 验证码登录校验(临时测试)
		$code_check = 12345;
		if (isset($args['code']) && $args['code'] != $code_check) {
			return $this->codeMsg(1002, '手机号验证码错误', $args);
		}

		return $this->codeMsg(200, 'success', $args);
	}

	/** 
	 * [登录] 方式一：手机号 + 密码
	 * 
	 * @param     [number]      $phone [手机号]
	 * @param     [string]      $pwd   [密码]
	 * 
	 * @return    [type]             [description]
	 */
	public function loginByPwd($phone, $password, $platid=0)
	{
		// 登录规则验证
		$args = ['phone'=>$phone, 'password'=>$password];
		$rules = $this->ruleLogin($args);
		if ($rules['code'] !== 200) {
			return $rules;
		}

		// 根据手机号获取账号信息,不存在则创建
		$result = $this->account->getAccountByPhone($phone, true);
		if (!$result['data']) {
			return $this->codeMsg(2000, '您的账号不存在', $result);
		}

		// 密码校验
		if ($result['data']['password']!=md5($password)) {
			return $this->codeMsg(2001, '您的账号或密码输入错误', md5($password));
		}

		// 设置登录平台标识
		if ($result['data']) {
			$result['data']->setPlat($platid);
		}
		
		return $result;
	}

	/** 
	 * [登录] 手机号 + 验证码 (无账号,自动注册)
	 * 
	 * @param     [array]      $args [登录参数]
	 * 
	 * @return    [type]            [description]
	 */
	public function loginByCode($phone, $code, $platid=0)
	{
		// 登录规则验证
		$args = ['phone'=>$phone, 'code'=>$code];
		$rules = $this->ruleLogin($args);
		if ($rules['code'] !== 200) {
			return $rules;
		}

		// 根据手机号获取账号信息,不存在则创建
		$result = $this->account->getAccountByPhone($phone, false);
		if (!$result && isset($args['code']) && $args['code']) {
			$result = $this->account->createAccountAndCustomer($phone);
			if (isset($result['data']['customer'])) {
				unset($result['data']['customer']);
			}
		}

		// 设置登录平台标识
		if ($result['data']) {
			$result['data']->setPlat($platid);
		}
		

		return $result;
	}

	
}