<?php 
/** 
 * 门店客户管理类
 * 
 */

namespace App\Repositories\UCenter;

use Illuminate\Http\Request;
use App\Models\UCenter\CustomerAccountModel;
use App\Models\UCenter\CustomerModel;
use App\Repositories\Tools\RuleTool;

use App\Repositories\BaseRepository;

class CustomerAccountRepository extends BaseRepository
{
	protected $account;
	protected $customer;
	protected $accountField;

	public function __construct(CustomerAccountModel $account, CustomerModel $customer)
	{
		// 创建账号对象
		$this->account = $account;
		// 创建客户对象
		$this->customer = $customer;
		// 创建账号允许操作字段
		$this->accountField = $this->account->filter_field();
	}

	/******************************* [1] 账号 + 客户信息组合方法 *******************************/
	/** 
	 * 1. 创建账号 + 客户信息
	 * 
	 * @param  		 $paramname description
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function createAccountAndCustomer($phone, $args, $fam_account_id=0)
	{
		$result = ['account'=>[], 'customer'=>[]];
		// 获取创建账号数据
		$args['phone'] 			= $phone;
		$args['fam_account_id'] = $fam_account_id;
		$adata = $this->filterAccountData($args);

		// 1.1 [校验] 创建账号参数有效性及账号存在性判断
		$verify = $this->accountCreatedRules($adata);
		if ($verify['code'] !== 200) {
			return $verify;
		}

		/******** 1.2 创建账号,失败直接返回 ********/
		$this->beginTrans();
		$result['account'] = $this->account->create($adata);
		if (!$result['account']) {
			$this->rollbackTrans();
			return $this->codeMsg(2000, '账号创建失败', $result);
		} 

		/******** 1.3 创建客户信息 ********/
		# 客户所属家庭id
		$args['fam_account_id'] = $result['account']->id;
		# 规则控制
		$verify = $this->customerCreatedRules($args);
		if ($verify['code'] !== 200) {
			return $verify;
		}
		// 获取客户可更新字段
		$cdata = $this->customer->filter_data($args);
		// 创建客户,失败直接返回
		$this->beginTrans();
		$result['customer'] = $this->customer->create($cdata);
		if (!$result['customer']) {
			$this->rollbackTrans();
			return $this->codeMsg(2000, '账号创建失败', $result);
		}
		# 提交事务
		$this->commitTrans();

		// 1.4 账号转为数组,返回数据
		$result['account'] = $result['account']->toArray();
		$result['customer'] = $result['customer']->toArray();

		return $this->codeMsg(200, 'success', $result);

	}


	/******************************* [2] 账号方法 *******************************/

	/** 
	 * [2.1] 创建账号
	 * 
	 * @param     intger      $fam_account_id   [主账号id]
	 * @param     string      $phone    		[手机号]
	 * @param     string      $unionid  		[用户授权id]
	 * @param     string      $email    		[邮箱]
	 * @param     string      $username 		[登录用户名]
	 * 
	 * @return    [array]                [code=>状态码, msg=>消息内容, data=>返回的数据]
	 */
	public function createAccount($fam_account_id, $phone='', $unionid='', $email='', $username='')
	{
		// 获取创建账号数据
		$data = $this->filterAccountData(['fam_account_id'=>$fam_account_id, 'phone'=>$phone, 'unionid'=>$unionid, 'email'=>$email, 'username'=>$username]);

		// 2.1 [校验] 创建账号参数有效性及账号存在性判断
		$verify = $this->accountCreatedRules($data);
		if ($verify['code'] !== 200) {
			return $verify;
		}

		// 创建账号,失败直接返回
		$this->beginTrans();
		$accountInfo = $this->account->create($data);
		if (!$accountInfo) {
			$this->rollbackTrans();
			return $this->codeMsg(2000, '账号创建失败', $data);
		} 
		$this->commitTrans();

		// 账号转为数组
		$accountInfo = $accountInfo->toArray();
		return $this->codeMsg(200, 'success', $accountInfo);
	}

	/** 
	 * 2.1.1 账号有效性校验
	 * 
	 * @return  [array] [code=200成功,否则失败]
	 */
	public function accountCreatedRules($data)
	{
		// 过滤空数据
		$data = array_filter($data);

		if (!$data || !isset($data['phone'])) {
			return $this->codeMsg(2001, '账号创建失败, 缺少必要参数');
		}

		// 1. 创建账号必须有手机号
		if (!$data['phone'] || !RuleTool::phone($data['phone'])) {
			return $this->codeMsg(2002, '请输入正确手机号');
		}
		// 2. unionid判断(可选)
		if (isset($data['unionid']) && $data['unionid'] && !RuleTool::unionid($data['unionid'])) {
			return $this->codeMsg(2003, '用户授权信息错误');
		}

		// 3. [校验] 账号是否存在
		$existAccount = $this->existsAccount($data['phone'], $data['unionid']);
		if ($existAccount['code'] !== 200) {
			return $existAccount;
		}

		return $this->codeMsg(200);
	}

	/** 
	 * 2.1.2 账号是否存在，存在返回数据
	 * 
	 * @param     [number]      $phone   [手机号]
	 * @param     [string]      $unionid [用户授权id]
	 * 
	 * @return    [array]               [code=200成功,否则失败]
	 */
	public function existsAccount($phone, $unionid='')
	{
		if (!$phone || !RuleTool::phone($phone)) {
			return $this->codeMsg(2004, '缺少必要正确手机号', ['phone'=>$phone, 'unionid'=>$unionid]);
		}

		$sql = 'select id,phone,unionid from f_ucenter_customer_account where phone=:phone';
		$params = [':phone'=>$phone];
		if ($unionid) {
			$sql .= ' OR unionid=:unionid';
			$params[':unionid'] = $unionid;
		}
		$data = $this->account->queryOne($sql, $params);
		if ($data) {
			return $this->codeMsg(2005, '账号已存在', $data);
		} else {
			return $this->codeMsg(200, '账号不存在', $data);
		}
	}

	/** 
	 * 拼接账号数据
	 * 
	 * @param     [array]      $data [待处理数组数据]
	 * 
	 * @return    [array]
	 */
	protected function filterAccountData($data)
	{
		return [
			'phone'		=> $this->getVar('phone', $data, ''),
			'unionid'	=> $this->getVar('unionid', $data, ''),
			'email'		=> $this->getVar('email', $data, ''),
			'username'	=> $this->getVar('username', $data, '')
		];
	}

	/****************************** [3] 客户信息方法 ******************************/
	/** 
	 * 3.1 创建客户信息
	 * 
	 * @param     [int]		$belong_account_id 	[客户所属账号id]
	 * @param     [array]   $data              	[客户信息]
	 * @param     [int]     $create_account_id 	[创建客户信息的账号id]
	 * @param     [int]     $role_id 			[用户角色,0为主账号]
	 * 
	 * @return    [type]                         [description]
	 */
	public function createCustomer($data, $fam_account_id, $role_id=0)
	{
		// 3.1.1 参数有效性判断
		$data['fam_account_id'] = intval($fam_account_id);
		$data['role_id'] = intval($role_id);
		$verify = $this->customerCreatedRules($data);
		if ($verify['code'] !== 200) {
			return $verify;
		}

		// 获取可更新字段
		$data = $this->customer->filter_data($data);
		// 创建账号,失败直接返回
		$this->beginTrans();
		$customerInfo = $this->customer->create($data);
		if (!$customerInfo) {
			$this->rollbackTrans();
			return $this->codeMsg(2000, '账号创建失败', $data);
		}
		# 提交事务
		$this->commitTrans();

		// 账号转为数组
		$customerInfo = $customerInfo->toArray();
		return $this->codeMsg(200, 'success', $customerInfo);

	}

	/** 
	 * [规则] 创建客户信息规则定义
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function customerCreatedRules($data)
	{
		// 所属家庭必要字段
		$fam_account_id = $this->getVar('fam_account_id', $data, 0);
		if (!$fam_account_id) {
			return $this->codeMsg(2001, '创建客户信息缺少必要家庭信息');
		}
		// 姓名必要字段
		$realname = $this->getVar('realname', $data, '');
		$nickname = $this->getVar('nickname', $data, '');
		if (!$realname && !$nickname) {
			return $this->codeMsg(2002, '请输入客户姓名或昵称');
		}

		return $this->codeMsg(200);
	}

	/**
	 * 客户角色是否存在
	 * 
	 * @param     [int]      $role_id        [角色id]
	 * @param     [int]      $fam_account_id [description]
	 * 
	 * @return    [array]
	 */
	public function existsCustomerRole($role_id, $fam_account_id)
	{
		# 家庭账号可重复创建
		if (!$role_id) {
			return $this->codeMsg(200, 'success');
		}

		if (!$fam_account_id) {
			return $this->codeMsg(2000, '缺少家庭账号信息');
		}
		$sql = 'SELECT * FROM f_ucenter_customer WHERE fam_account_id=:fam_account_id AND role_id=:role_id';
		$params = [':fam_account_id'=>intval($fam_account_id), ':role_id'=>intval($role_id)];
		$data = $this->account->queryOne($sql, $params);
		if ($data) {
			return $this->code('1001', '角色已存在', $data);
		} else {
			return $this->code(200, 'success', $data);
		}
	}

	/** 
	 * 查询客户列表
	 * @param     [type]      $org_id   [description]
	 * @param     [type]      $args     [description]
	 * @param     [type]      $page     [description]
	 * @param     [type]      $per_page [description]
	 * @return    [type]                [description]
	 */
	public function pageListCustomer($args)
	{
		$where = $this->buildCustomerWhere($args);
		$orderBy = 'cu.id desc';
		$sql = 'SELECT %s FROM f_ucenter_customer cu,f_ucenter_customer_account ca,f_ucenter_customer_trace ct WHERE cu.account_id=ca.id AND ct.customer_id=cu.id AND '.$where;
		// 获取列表数据
		$this->parsePaginate($args);
		$result['list'] = $this->customer->queryAll(sprintf($sql, '*'), [], $orderBy, $this->page, $this->limit);

		// 初始化字段释义
		foreach ($result['list'] as &$info) {
			$this->parseCustomerField($info);
		}

		// 总记录数查询
		$result['total'] = count($result['list']);
		if (!($this->page==1 && $result['total']<$this->limit)) {
			$result['total'] = $this->customer->queryCount(sprintf($sql, 'COUNT(*) as num'));
		}

		return $this->codeMsg(200, 'success', $result);
	}

	// 组建列表查询条件
	protected function buildCustomerWhere($args)
	{
		$where = [];

		// 门店查询
		if ($org_id = $this->getVar('org_id', $args)) {
			$where[] = ' ct.org_id='.intval($org_id);
		}
		// 姓名查询
		if ($realname = $this->getVar('realname', $args)) {
			$where[] = "(cu.realname LIKE '%".$realname."%' OR cu.nickname LIKE '%".$realname."%')";
		}

		// 手机号查询
		if ($phone = $this->getVar('phone', $args)) {
			$where[] = "ca.phone LIKE '%".intval($phone)."%'";
		}
		// 状态查询
		if (isset($args['status']) && is_numeric($args['status'])) {
			$where[] = 'ct.status='.intval($args['status']);
		} else {
			$where[] = 'ct.status=1';
		}

		return implode(' AND ', $where);
	}

	/** 
	 * 初始化客户字段释义
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	public function parseCustomerField(&$data)
	{
		if (!$data) {
			return $data;
		}
		// 性别
		if (isset($data['sex'])) {
			$data['sex_name'] = $this->customer->getEnums('sex', $data['sex']);
		}
		// 状态
		if (isset($data['status'])) {
			$data['status_name'] = $this->customer->getEnums('status', $data['status']);
		}
		if (isset($data['nickname']) && isset($data['realname'])) {
			$data['realname'] = $data['realname'] ? $data['realname'] : $nickname;
		}

		return $data;
	}
}