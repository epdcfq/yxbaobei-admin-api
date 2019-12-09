<?php
/** 
 * 公众号菜单管理
 *     规则：
 * 		1. 自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
 *   	2. 一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
 * 		3. 创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，
 *   		如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。
 *     		测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。​
 */
namespace App\Http\Controllers\Wechat;

use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxMenuController extends Controller
{
    protected $app;
    public function __construct()
    {
        $this->app = app('wechat.official_account');
        parent::__construct();
    }

    /** 
     * 新增菜单
     * 
     * @return    [type]      [description]
     */
    public function create()
    {
 		// 创建菜单
        $menu =[
            [
                "name"       => "关于我们",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "品牌介绍",
                        "url"  => "http://118.190.38.248"
                    ],
                    [
                        "type" => "view",
                        "name" => "课程体系",
                        "url"  => "http://118.190.38.248"
                    ],
                    [
                        "type" => "view",
                        "name" => "最新动态",
                        "url"  => "http://118.190.38.248"
                    ]
                ]
            ],
            [

                "name"       => "优惠活动",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "双12活动",
                        "url"  => "http://118.190.38.248"
                    ]
                ],
            ],
            [

                "name"       => "个人中心",
                "type" => "pic_photo_or_album",
                "key"=>"V1001_GOOD"
                // "sub_button" => [
                //     [
                //         "type" => "pic_photo_or_album",
                //         "name" => "拍照上传",
                //         "url"  => "http://118.190.38.248"
                //     ]
                // ],
            ]
        ];
        // print_r($menu);die;
        $data = $this->app->menu->create($menu);

        return $this->success($data);
    }

    // 查询当前菜单
    public function current()
    {
    	// 查询当前菜单
        $data = $this->app->menu->current();
        return $this->success($data);
    }

    // 当前菜单列表
    public function list()
    {
        $data = $this->app->menu->list();
        return $this->success($data);
    }

    // 指定或全部删除菜单
    public function del()
    {
    	$menuId = $request->input['menuId'];
	    if ($menuId) {
	        $data = $this->app->menu->delete($menuId);
	    } else {
	        $data = $this->app->menu->delete(); // 全部
	    }
	    return $this->success($data);
    }
}
