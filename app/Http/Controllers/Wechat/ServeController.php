<?php
/**
 * 微信服务
 * 
 */

namespace App\Http\Controllers\Wechat;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServeController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }

    public function checkSign()
    {
    	$echoStr = isset($_GET["echostr"]) ? $_GET['echostr'] : '';
         if($this->checkSignature()){
             echo $echoStr;
             exit;
         }
    }

    //检查签名
    private function checkSignature()
     {
         $signature = isset($_GET["signature"]) ? $_GET['signature'] : '';
         $timestamp = isset($_GET["timestamp"]) ? $_GET['timestamp'] : '';
         $nonce = isset($_GET["nonce"]) ? $_GET['nonce'] : '';
         $token = config('wechat.official_account.default')['token'];
         $tmpArr = array($token, $timestamp, $nonce);
         sort($tmpArr, SORT_STRING);
         $tmpStr = implode($tmpArr);
         $tmpStr = sha1($tmpStr);
         if($tmpStr == $signature){
             return true;
         }else{
             return false;
         }
     }
}
