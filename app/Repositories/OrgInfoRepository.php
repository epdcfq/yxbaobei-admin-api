<?php 
/** 
 * 门店管理类
 * 
 */

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\OrgInfoModel;
use App\Models\Org\OrgTemplateModle;
use App\Repositories\BaseRepository;
use App\Repositories\UCenter\UCenterAccountRepository;


class OrgInfoRepository extends BaseRepository
{
	protected $org;
	protected $account;
	public function __construct(OrgInfoModel $org, UCenterAccountRepository $account)
	{
		$this->org = $org;
		$this->acccount = $account;
	}

	public function getOrgById($id, $template=false)
	{
		$result = [];
		if (!$id) {
			return $result;
		}

		$result = $this->org::find($id);
		if ($template) {
			$result->templates;
		}
		return $result;
	}

	/** 
	 * 创建门店及账号
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function createOrgAndAccount($args)
	{
		$result = ['account'=>[], 'org'=>[]];
		// 1. 创建门店
		
		// 2. 创建门店账号
		$accountInfo = $this->account->createAccountAndCustomer($args);
		
	}

	/** 
	 * 创建门店
	 * 
	 * @param     [type]      $args [description]
	 * @return    [type]            [description]
	 */
	public function createOrg($args)
	{

	}

	public function existsOrg($args)
	{
		// 拼接查询条件
		$where = [];
		if (isset($args['short_name']) && $args['short_name']) {
			$where['short_name'] = $args['short_name'];
		}
		if (isset($args['business_name']) && $args['business_name']) {
			$where['business_name'] = $args['business_name'];
		}

		if (!$args || !$where) {
			return false;
		}

		// return $this->org::where($where)->
	}

	/** 
	 * [新增|编辑] 更新内容
	 * 
	 * @param     [array]      $data [description]
	 * 
	 * @return    [type]            [description]
	 */
	public function storeOrg($data)
	{
		$result = ['data'=>$data, 'status'=>false, 'filter_data'=>[]];
		// 处理更新数据
		$id = isset($data['id']) ? intval($data['id']) : 0;
		unset($data['id']);
		$result['filter_data'] = $this->filterOrgData($data);
		if (!$data || !$result['filter_data']) {
			return $result;
		}
		try {
			if ($id>0) {
				$result['status'] = $this->org::where(['id'=>$id])->update($result['filter_data']);
			} else {
				// 方式一：字段赋值
				// foreach ($filter_data as $k=>$v) {
				// 	$this->article->$k = $v;
				// }
				// $result = $this->article->save();
				// 方式二：配合model类变量fillable
				$result['status'] = $this->org::create($result['filter_data']);
			}
		} catch (Exception $e) {
			$result = $e->getMessage();
		}

		return $result;
	}

	protected function filterOrgData($data)
	{
		$allow_field = $this->org->filter_field($data);

		return $allow_field;
	}

	/** 
	 * 门店列表管理
	 * 
	 * @return    [type]      [description]
	 */
	public function pageListOrg($args)
	{
		// 初始化页码
		$this->parsePaginate($args);

		$search = $this->buildOrgWhere($args);
		$sql = 'select :field from f_org_info WHERE '.$search['where'];
		$result['list'] = $this->org->queryAll(str_replace(':field', '*', $sql), $search['params'], 'created_at desc', $this->page, $this->limit);
		foreach ($result['list'] as &$info) {
			$info = $this->parseData($info);
		}
		// $count_sql = 'select count(*) as num from f_org_info WHERE '.$search['where'];
		// $search['params'][':field'] = 'count(*) as num';
		$result['total'] = ($this->org->queryOne(str_replace(':field', 'count(*) as num', $sql), $search['params']))['num'];
		return $result;
	}

	protected function buildOrgWhere($args)
	{
		$where = [];
		$params = [];
		// 门店id查询
		if (isset($args['org_id']) && $args['org_id']) {
			$where[] = 'org.id=:org_id';
			$params[':org_id'] = intval($args['org_id']);
		}
		// 门店名称查询
		if (isset($args['keyword']) && $args['keyword']) {
			$where[] = "(short_name LIKE '%".$args['keyword']."%' OR business_name LIKE '%".$args['keyword']."%')";
		}
		// 注册时间区间查询
		if (isset($args['dates']) && $args['dates'] && strpos($args['dates'], ',')) {
			list($min_date, $max_date) = explode(',', $args['dates']);
			if ($min_date) {
				$where[] = 'created_at>=:min_date';
				$params[':min_date'] = strtotime($min_date);
			}
			if ($max_date) {
				$where[] = 'created_at>=:max_date';
				$params[':max_date'] = strtotime($max_date);
			}
		}
		// 有效性查询
		$where[] = 'status=:status';
		if (isset($args['status']) && $args['status'] !== '') {
			$params[':status'] = intval($args['status']);
		} else {
			$params[':status'] = 1;
		}
		
		return ['where'=>implode(' AND ', $where), 'params'=>$params];
	}

	/** 
	 * 格式化数据
	 * 
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 */
	protected function parseData($data)
	{
		if (!$data) {
			return $data;
		}
		if (isset($data['business_license_img'])) {
			$data['business_license_img'] = $this->parseImg($data['business_license_img']);
		}
		if (isset($data['business_name']) && $data['business_name']) {
			$data['business_name'] = $data['business_name'];
		}

		return $data;
	}

	public function getCreateField()
	{
		$data = $this->org->filter_field();
		// 设置字段默认值
		$data = array_fill_keys(array_keys($data), '');
		$data['type'] = $data['status'] = $data['sex'] = 1;
		$data['id'] = 0;
		unset($data['created_at'], $data['updated_at']);
		return $data;
	}

	/** 
	 * 生成门店小程序二维码(限制100000个)
	 * 
	 * @param     [int]     $org_id [门店id]
	 * @param     [int]     $width  [小程序码大小]
	 * 
	 * @return    [type]              [description]
	 */
	public function createQRCode($org_id, $width=600, $force=false)
	{
		if (!$org_id) {
			return $this->codeMsg(2000, '缺少门店id');
		}

		// 1. 文件路径及名称
		$path = './uploads/qrcode/limit/org/'.$org_id;
		$filename = 'qrcode-'.$width.'.png';
		$filepath = $path.'/'.$filename;

		# 创建目录
		if (!file_exists($path) && !mkdir($path, 0777, true)) {
			return $this->codeMsg(2001, '路径创建失败');
		}

		// 2. 不强制更新，文件存在直接返回
		if (!$force && is_file($filepath)) {
			return $this->codeMsg(300, ['path'=>$filepath]);
		}

		// 3. 请求微信服务，生成小程序码
		$app = app('wechat.mini_program');
		$response = $app->app_code->get('pages/login/login', ['width'=>$width, 'scene'=>'org_id='.$org_id]);
		# 3.1 生成失败，返回消息
		if (!($response instanceof \EasyWeChat\Kernel\Http\StreamResponse)) {
			return $this->codeMsg(2001, $response['errmsg']);
		}

		# 3.2 保存文件
		$response->saveAs($path, $filename);
		if (!is_file($filepath)) {
			return $this->codeMsg(2002, '二维码保存本地失败');
		}
	    
	    return $this->codeMsg(200, ['path'=>$filepath]);
	}

	/** 
	 * 生成无限制小程序二维码
	 * 
	 * @param     [int]     $org_id [门店id]
	 * @param     [int]     $width  [小程序码大小]
	 * 
	 * @return    [type]              [description]
	 */
	public function createUnLimitQRCode($org_id, $page_path, $args=[], $width=300, $force=false)
	{
		if (!$org_id || !$page_path) {
			return $this->codeMsg(2000, '缺少必要参数');
		}
		// 组合sence参数
		$args['org_id'] = $org_id;
		$scene_str = http_build_query($args);

		// 1. 文件路径及名称
		$path = './uploads/qrcode/unlimit/'.$org_id;
		$filename = 'qrcode-'.md5($page_path.'?'.$scene_str).'.png';
		$filepath = $path.'/'.$filename;

		# 创建目录
		if (!file_exists($path) && !mkdir($path, 0777, true)) {
			return $this->codeMsg(2001, '路径创建失败');
		}

		// 2. 不强制更新，文件存在直接返回
		if (!$force && is_file($filepath)) {
			return $this->codeMsg(300, ['path'=>$filepath]);
		}
		// echo $page_path;die;
		// 3. 请求微信服务，生成小程序码
		$app = app('wechat.mini_program');
		$response = $app->app_code->getUnlimit(
										$scene_str, 
										['page'=>$page_path, 'width'=>$width]
									);
		# 3.1 生成失败，返回消息
		if (!($response instanceof \EasyWeChat\Kernel\Http\StreamResponse)) {
			return $this->codeMsg(2001, $response['errmsg']);
		}

		# 3.2 保存文件
		$response->saveAs($path, $filename);
		if (!is_file($filepath)) {
			return $this->codeMsg(2002, '二维码保存本地失败');
		}
	    
	    return $this->codeMsg(200, ['path'=>$filepath]);
	}

	
}