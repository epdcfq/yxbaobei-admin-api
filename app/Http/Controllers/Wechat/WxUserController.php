<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\WxAuthorizeRepository;

class WxUserController extends Controller
{
	protected $open_id;
	public function __construct()
	{
		parent::__construct();

	}
    public function info()
    {
    	// 获取单一用户信息
        $open_id = isset($_GET['open_id']) ? $_GET['open_id'] : 'oxyQh1WpluVaxaG_STzgwlZhd18A'; 
        if (!$open_id && !in_array($opt, ['blocklist'])) {
            return $this->fail(100, '缺少openid');
        }
        $app = app('wechat.official_account');
        $data = $app->user->get($open_id);
        return $this->success($data);
    }

    public function auth()
    {
        $obj = new \App\Models\Wx\WxAuthModel();
        $auth = new WxAuthorizeRepository($obj);

        $data = [];
        $result = $auth->addAuth($data);
        print_r($result);
        echo 1;die;
    }
}
