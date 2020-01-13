<?php 
/** 
 * 规则验证类
 * 
 */

namespace App\Repositories\Tools;

class RuleTool {

	/** 
	 * 手机号验证
	 * 
	 * @param     [number]      $phone [手机号]
	 * @return    [boolean]
	 */
	public static function phone($phone)
	{
		return preg_match("/^1[34578]{1}\d{9}$/", $phone);
	}

	/** 
	 * 邮箱校验
	 * 
	 * @param     [string]      $email [邮箱]
	 * 
	 * @return    [type]             [description]
	 */
	public static function email($email)
	{
		return filter_var($email,FILTER_VALIDATE_EMAIL) ? true : false;
	}

	/** 
	 * 身份证严格校验
	 * 
	 * @param     [string]      $identity [身份证号]
	 * @return    [boolean]
	 */
	public static function identity($identity)
	{
		$identity = strtoupper($identity);
        $regx = "/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/";
        $arr_split = array();
        if(!preg_match($regx, $identity)) {
            return FALSE;//正则校验
        }
        if(15==strlen($identity)) {
        	//检查15位
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $identity, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
        	//检查18位
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $identity, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            //检查生日日期是否正确
            if(!strtotime($dtm_birth)) {
                return FALSE;
            } else {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ ) {
                    $b = (int) $identity{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($identity,17, 1)) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
	}

	/** 
	 * 微信授权unionid检查(openid=28位/unionid=29)
	 * 
	 * @param     [string]      $unionid [用户授权unionid]
	 * 
	 * @return    [boolean]
	 */
	public static function unionid($unionid)
	{
		return strlen($unionid)>=28 ? true : false;
	}
}