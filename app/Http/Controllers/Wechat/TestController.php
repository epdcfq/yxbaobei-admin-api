<?php
/**
 * 微信服务
 * 
 */

namespace App\Http\Controllers\Wechat;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;

class TestController extends Controller
{
    protected $app;
    public function __construct()
    {
        $this->app = app('wechat.official_account');
    }
    /**
     * 获取access_token
     *
     * @return string
     */
    public function access_token()
    {
        Log::info('access_token request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        

        /****** access_token测试 ******/
        // 获取token,方法getToken(true)强制刷新
        // 返回内容：{"access_token":"28_OUgg9YY6DUlcigJjFxiUsnPZZfvXqz9zH-HOSeiu4oQtvgZG8LCuua8yA5n04DQSSKSuBwdYgshk1Pal0ho0W0ugnWCehXudVzvOSTz97Wk1qNRjrEV4l04DWjuSruZsDhPaLpI4lVAJc9tPTTCeAFAYYH","expires_in":7200}
        // $token = $this->app->access_token->getToken();
        // 修改access_token
        // $app['access_token']->setToken($newAccessToken, 7200);

        $validIps = $this->app->base->getValidIps();

        // $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx559bf9cf416536c8&secret=035d6ed9feb74ed181db686dc49861aa';
        // $data = file_get_contents($url);
        return $this->success(['token'=>$token, 'data'=>$data]);
    }

    /** 
     * 微信服务器ip列表、回调地址网络监测
     * 
     * @return    [type]      [description]
     */
    public function wxips()
    {
        $data['getValidIps'] = $this->app->base->getValidIps();
        $data['checkCallbackUrl'] = $this->app->base->checkCallbackUrl();
        return $this->success($data);
    }

    /** 
     * 微信菜单
     * 
     * @return    [type]      [description]
     */
    public function menu(Request $request, $opt)
    {
        $data = [];
        switch ($opt) {
            case 'create':
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
            break;
            case 'current':
                // 查询当前菜单
                $data = $this->app->menu->current();
            break;
            case 'list':
                $data = $this->app->menu->list();
                break;
            case 'del':
                $menuId = $request->input['menuId'];
                if ($menuId) {
                    $data = $this->app->menu->delete($menuId);
                } else {
                    $this->app->menu->delete(); // 全部
                }
                

            break;
        }
        
        return $this->success($data);
    }

    public function user(Request $request, $opt)
    {
        // 获取单一用户信息
        $open_id = isset($_GET['open_id']) ? $_GET['open_id'] : 'oxyQh1WpluVaxaG_STzgwlZhd18A'; 
        if (!$open_id && !in_array($opt, ['blocklist'])) {
            return $this->fail(100, '缺少openid');
        }
        
        // 操作
        $data = [];
        switch ($opt) {
            case 'info':
                $data = $this->app->user->get($open_id);
                break;
            case 'muli-info':
                // 获取多个用户信息(多个openid用英文逗号隔开)
                $open_ids = is_string($open_id) ? explode(',', $open_id) : $open_id;
                $data = $this->app->user->select($open_ids);
                break;
            case 'remark':
                // 修改用户备注
                $remark_name = isset($_REQUEST['remark_name']) ? $_REQUEST['remark_name'] : '这是备注名称字符';
                $data = $this->app->user->remark($open_id, $remark_name);
                break;
            case 'block':
                // 拉黑用户
                $open_id = is_string($open_id) ? explode(',', $open_id) : $open_id;
                $data = $this->app->user->block($open_id);
                break;
            case 'unblock':
                # 取消拉黑
                $open_id = is_string($open_id) ? explode(',', $open_id) : $open_id;
                $data = $this->app->user->unblock($open_id);
                break;
            case 'blocklist':
                # 获取黑名单
                # 可选
                $beginOpenid = isset($_REQUEST['beginOpenid']) ? $_REQUEST['beginOpenid'] : null;
                $data = $this->app->user->unblock($beginOpenid);
                break;
            default:
                # code...
                break;
        }
        return $this->success($data);
    }

    public function tag(Request $request, $opt)
    {
        $data = [];
        switch ($opt) {
            case 'list':
                # 标签列表
                $data = $this->app->user_tag->list();
                break;
            case 'create':
                # 创建标签
                $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
                if (!$name) {
                    return $this->fail(1001, '请输入标签名称');
                }
                $data = $this->app->user_tag->create($name);
                break;
            case 'edit':
                # 修改标签
                $tagId = isset($_REQUEST['tagId']) ? $_REQUEST['tagId'] : 0;
                $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
                if (!$tagId) {
                    return $this->fail(1001, '请输入修改的标签id');
                }
                if (!$name) {
                    return $this->fail(1002, '请输入标签名称');
                }
                $data = $this->app->user_tag->update($tagId, $name);
                break;
            case 'del':
                # 删除标签
                $tagId = isset($_REQUEST['tagId']) ? $_REQUEST['tagId'] : 0;
                if (!$tagId) {
                    return $this->fail(1001, '请输入修改的标签id');
                }
                $data = $this->app->user_tag->delete($tagId);
            case 'user-tags':
                # 用户所属的标签
                $open_id = isset($_GET['open_id']) ? $_GET['open_id'] : 'oxyQh1WpluVaxaG_STzgwlZhd18A'; 
                if (!$open_id && !in_array($opt, ['blocklist'])) {
                    return $this->fail(100, '缺少openid');
                }
                $data = $this->app->user_tag->userTags($open_id);
                break;
            case 'users-of-tag':
                # 标签下用户列表
                $tagId = isset($_REQUEST['tagId']) ? $_REQUEST['tagId'] : 0;
                if (!$tagId) {
                    return $this->fail(1001, '缺少标签id');
                }
                $nextOpenId = isset($_REQUEST['nextOpenId']) ? $_REQUEST['nextOpenId'] : '';
                $data = $this->app->user_tag->usersOfTag($tagId, $nextOpenId);
                break;
            case 'tag-users':
                # 批量为用户添加标签
                # 标签id
                $tagId = isset($_REQUEST['tagId']) ? $_REQUEST['tagId'] : 0;
                if (!$tagId) {
                    return $this->fail(1001, '缺少标签id');
                }

                # 用户openid
                $open_id = isset($_GET['open_id']) ? $_GET['open_id'] : 'oxyQh1WpluVaxaG_STzgwlZhd18A'; 
                if (!$open_id && !in_array($opt, ['blocklist'])) {
                    return $this->fail(100, '缺少openid');
                }
                $open_ids = is_string($open_id) ? explode(',', $open_id) : $open_id;
                $data = $this->app->user_tag->tagUsers($open_ids, $tagId);
                break;
            case 'untag-users':
                # 批量为用户移除标签
                # 标签id
                $tagId = isset($_REQUEST['tagId']) ? $_REQUEST['tagId'] : 0;
                if (!$tagId) {
                    return $this->fail(1001, '缺少标签id');
                }

                # 用户openid
                $open_id = isset($_GET['open_id']) ? $_GET['open_id'] : 'oxyQh1WpluVaxaG_STzgwlZhd18A'; 
                if (!$open_id && !in_array($opt, ['blocklist'])) {
                    return $this->fail(100, '缺少openid');
                }
                $open_ids = is_string($open_id) ? explode(',', $open_id) : $open_id;
                $data = $this->app->user_tag->untagUsers($open_ids, $tagId);
                break;
            default:
                # code...
                break;
        }
        return $this->success($data);
    }

    /**
     * 素材管理
     * 
     */
    public function media()
    {
        // $this->app->media;
    }
}
