<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
	protected function success($data = [])
	{
		// print_r(config('errorcode.code')[200]);die;
		return response()->json([
			'status' => true,
			'code' => 200,
			'message' => config('errorcode.code')[200],
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
