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
use App\Repositories\Wechat\WxMessageRepository;
use App\Repositories\Wechat\WxEventRepository;

class ServeController extends Controller
{
    protected $wxmessage;
    protected $wxevent;
    public function __construct(WxMessageRepository $wxmessage, WxEventRepository $wxevent)
    {
        $this->wxmessage    = $wxmessage;
        $this->wxevent      = $wxevent;
    }
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve(Request $request)
    {
        Log::info('wechat/server request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        // $app->server->push(function($message){
        //     return "欢迎关注 overtrue！";
        // });
        $app->server->push(function ($message) {
            Log::info('$app->server->push', $message);
            // 非事件，记录消息
            if (!$message['MsgType']!='event') {
                $msg = $this->wxmessage->newMessage($message);
            }
            
            $id = isset($msg['id']) ? '['.$msg['id'].']' : '[0]';
            switch ($message['MsgType']) {
                case 'event':
                    $msg = $this->wxevent->newEvent($message);
                    $id = isset($msg['id']) ? '['.$msg['id'] . ']' : '[0]';
                    return $id.'收到事件消息';
                    break;
                case 'text':
                    
                    return $id.'收到文字消息';
                    break;
                case 'image':
                    return $id.'收到图片消息';
                    break;
                case 'voice':
                    return $id.'收到语音消息';
                    break;
                case 'video':
                    return $id.'收到视频消息';
                    break;
                case 'location':
                    return $id.'收到坐标消息';
                    break;
                case 'link':
                    return $id.'收到链接消息';
                    break;
                case 'file':
                    return $id.'收到文件消息';
                // ... 其它消息
                default:
                    return $id.'收到其它消息';
                    break;
            }
        });
        return $app->server->serve();
    }
}
