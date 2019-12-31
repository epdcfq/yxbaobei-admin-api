<?php 
/** 
 * Repository基类
 * 
 */

namespace App\Repositories;

use DB;

class BaseRepository {
	// 当前页码
	protected $page = 1;
	// 每页显示条数
	protected $limit = 20;
	// 事务开启状态
	protected $transaction = 0;
	/** 
	 * 初始化页码
	 * 
	 */
	protected function parsePaginate($args)
	{
		$this->page = isset($args['page']) && $args['page']>0 ? intval($args['page']) : $this->page;
		$this->limit = isset($args['limit']) && $args['limit']>0 ? intval($args['limit']) : $this->limit;
	}

	protected function parseImg($imgpath)
	{
		if (!$imgpath) {
			return $imgpath;
		}

		static $img_host;
		if (!$img_host) {
			$img_host = env('APP_IMG_URL', 'http://127.0.0.1:8000');
		}
		
		return $img_host . parse_url($imgpath)['path'];
	}

	/** 
	 * 获取变量值
	 * 
	 * @param     [string]      $name          [数组下标]
	 * @param     [array]      	$data          [数组]
	 * @param     [string]      $default_value [默认返回值]
	 * 
	 */
	protected function getVar($name, $data, $default_value='')
	{
		return isset($data[$name]) ? $data[$name] : $default_value;
	}

	/** 
	 * 标准返回格式封装
	 * 
	 * @param     [type]      $code [状态码]
	 * @param     string      $msg  [状态码中文描述]
	 * @param     array       $data [返回数据]
	 * @return    [type]            [description]
	 */
	protected function codeMsg($code, $msg='success', $data=[])
	{
		return ['code'=>$code, 'msg'=>$msg, 'data'=>$data];
	}

	protected function beginTrans()
	{
		$this->transaction = 1;
		DB::beginTransaction();
	}

	protected function commitTrans()
	{
		$this->transaction = 0;
		DB::commit();
	}

	public function rollbackTrans()
	{
		$this->transaction = 0;
		DB::rollback();
	}

	public function __destory()
	{
		// 有未提交事务,取消事务
		if ($this->transaction) {
			echo '[__destory]取消事务'.chr(10);
			$this->rollbackTrans();
		}
	}
}