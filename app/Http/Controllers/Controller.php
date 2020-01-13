<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	protected $page =1;
	protected $per_page = 20;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
    	// $this->user = JWTAuth::parseToken()->authenticate();
    	// if (!$this->user) {
    	// 	return $this->fail(301, '登录失效');
    	// }
    }
	protected function success($data = [], $msg='')
	{
		// print_r(config('errorcode.code')[200]);die;
		return response()->json([
			'status' => true,
			'code' => 200,
			'message' => $msg ? $msg : config('errorcode.code')[200],
			'data' => $data,
		]);
	}

	protected function fail($code, $data = [], $msg='')
	{
		$msg = $msg ? $msg : '';
		if (!$msg && isset(config('errorcode.code')[(int) $code])) {
			$msg = config('errorcode.code')[(int) $code]; 
		}
		return response()->json([
			'status' => false,
			'code' => $code,
			'message' => $msg,
			'data' => $data,
		]);
	}
}
