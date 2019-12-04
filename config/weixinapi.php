<?php 
/**
 * 微信接口列表配置
 * 
 */

// GET获取Access token
return [
	// 接口域名
	'uri' => 'https://api.weixin.qq.com',
	// [GET] 获取Access token(参数:app_id/secret),返回参数:{"access_token":"ACCESS_TOKEN","expires_in":7200}
	'token' => '/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
	// [GET] 获取微信callback IP地址
	'callback_ip' => '/cgi-bin/getcallbackip?access_token=%s',
	// [POST] 网络检查
	'callback_check' => '/cgi-bin/callback/check?access_token=%s',
	// [POST] 创建菜单(返回{"errcode":0,"errmsg":"ok"})
	'menu_create' => '/cgi-bin/menu/create?access_token=%s',
	// [POST] 创建个性化菜单(千人千面)
	'menu_add_conditional' => '/cgi-bin/menu/addconditional?access_token=%s',
	// [GET] 查询菜单
	'menu_info' => '/cgi-bin/get_current_selfmenu_info?access_token=%s',
	// [GET] 获取自定义菜单配置
	'menu_get'	=> '/cgi-bin/menu/get?access_token=%s',
	// [GET] 删除所有自定义菜单(返回：{"errcode":0,"errmsg":"ok"})
	'menu_del_all' => '/cgi-bin/menu/delete?access_token=%s',
	
];