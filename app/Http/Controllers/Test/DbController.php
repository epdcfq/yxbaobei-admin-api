<?php 
/** 
 * DB库测试类
 * 
 */

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class DbController extends Controller {
	public function index()
	{
		// 手机号验证
		$phone = '18810238888';
		$check['phone'] = \App\Repositories\Tools\RuleTool::phone($phone);
		// 邮箱验证
		$email = '8426480880@qq.ccom';
		$check['email'] = \App\Repositories\Tools\RuleTool::email($email);
		// 身份证号验证
		$identity = '13022520111117191';
		$check['identity'] = \App\Repositories\Tools\RuleTool::email($email);
		// 加密解密
		$check['encode'] = \App\Repositories\Tools\EncryptTool::authcode($identity, 'encode');
		$check['decode'] = \App\Repositories\Tools\EncryptTool::authcode($check['encode'], 'decode');
		$check['expire_str'] = \App\Repositories\Tools\EncryptTool::authcode('67a2UUCdxRci3EHP7X7mFIdo7iWFzY006mxkaKVQpAK2igaFl7IIczt0Bta6NA', 'decode');
		
		$accountObj = new \App\Repositories\OrgUserAccountRespository(new \App\Models\OrgUserAccountModel(), new \App\Models\OrgUserCustomerModel());
		$check['account'] = $accountObj->createAccount($phone, '34804jlk4jl2j4l3jl2k432ljfdsafds');

		return $this->success(['check'=>$check]);
		// $t = new \App\Repositories\ArticleRepository(new \App\Models\ArticleModel());
		// $result = $t->list();
		// print_r($result);
	}
}