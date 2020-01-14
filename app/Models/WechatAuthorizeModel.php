<?php 
/** 
 * 门店分类管理
 * 
 */

namespace App\Models;

use App\Models\BaseModel;

class WechatAuthorizeModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_wechat_authorize';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    // 禁止更新字段
    protected $guarded = ['id'];

    protected $fillable = ['customer_account_id','org_id','unionid','openid','phone','nickname','sex','headimgurl','country','province','city','group_id','tagid_list','language','subscribe','subscribe_time','unsubscribe_time','subscribe_scene','qr_scene_str','remark','created_at','updated_at'];

    // 字段解释
    protected $allow_field = [
        'customer_account_id' => '账号ID',
        'org_id'            => '公司ID',
        'unionid'           => 'unionID',
        'openid'            => 'openID',
        'phone'             => '授权手机号',
        'nickname'          => '昵称',
        'sex'               => '性别',
        'headimgurl'        => '头像',
        'country'           => '国家',
        'province'          => '省',
        'city'              => '市',
        'group_id'          => '用户所在分组',
        'tagid_list'        => '标签列表',
        'language'          => '语言',
        'subscribe'         => '关注公众号',
        'subscribe_time'    => '关注时间',
        'unsubscribe_time'  => '取消关注时间',
        'subscribe_scene'   => '渠道来源',
        'qr_scene'          => '二维码扫码场景',
        'qr_scene_str'      => '二维码扫码描述',
        'remark'            => '备注名称',
        'created_at'        => '创建时间',
        'updated_at'        => '更新时间'
    ];
}
