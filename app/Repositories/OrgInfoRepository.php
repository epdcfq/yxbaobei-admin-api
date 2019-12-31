<?php 
/** 
 * 门店管理类
 * 
 */

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\OrgInfoModel;
use App\Repositories\BaseRepository;
use App\Repositories\UCenter\CustomerAccountRespository;


class OrgInfoRepository extends BaseRepository
{
	protected $org;
	protected $account;
	public function __construct(OrgInfoModel $org, CustomerAccountRespository $account)
	{
		$this->org = $org;
		$this->acccount = $account;
	}

	public function getOrgById($id)
	{
		$result = [];
		if (!$id) {
			return $result;
		}

		$result = $this->org::find($id);
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
		// 创建账号及客户信息
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

		// echo 2;die;
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
}