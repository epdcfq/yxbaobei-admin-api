<?php 
/** 
 * 门店分类管理
 * 
 */
namespace App\Models;

use App\Models\BaseModel;
use App\Models\WxAuthSource;

class WxAuthModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_wx_auth';

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
    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $enums = [
        // 性别
        'sex' => [0=>'未知', 1=>'男', 1=>'女'],
        // 公众号关注
        'subscribe' => [0=>'未关注', 1=>'已关注'],
        // 用户来源
        'subscribe_scene' => [
            'ADD_SCENE_SEARCH'  =>'公众号搜索',
            'ADD_SCENE_ACCOUNT_MIGRATION' => '公众号迁移',
            'ADD_SCENE_PROFILE_CARD' => '名片分享',
            'ADD_SCENE_QR_CODE' =>'扫描二维码',
            'ADD_SCENE_PROFILE_LINK' =>'图文页内名称点击',
            'ADD_SCENE_PROFILE_ITEM' =>'图文页右上角菜单',
            'ADD_SCENE_PAID'    =>'支付后关注',
            'ADD_SCENE_OTHERS'  =>'其他'
        ]
    ];

    // 获取状态名称
    public function getStatusName($status)
    {
        return $this->getEnums('status', $status);
    }

    /** 
     * 字段过滤
     * 
     * @param     array       $data [description]
     * @return    [type]            [description]
     */
    public function filter_field($data=[])
    {
        $allow_field = [
            'unionid'   => 'unionID',
            'openid'    => 'openID',
            'phone'     => '授权手机号',
            'nickname'  => '昵称',
            'sex'       => '性别',
            'headimgurl'=> '微信头像',
            'country'   => '所在国家',
            'province'  => '省',
            'city'      => '城市',
            'group_id'  => '用户所在分组',
            'tagid_list'=> '用户标签',
            'language'  => '语言',
            'subscribe' => '关注公众号',
            'subscribe_time' => '关注时间',
            'subscribe_scene' => '关注渠道来源',
            'qr_scene'  => '二维码场景',
            'qr_scene_str' => '二维码场景描述',
            'remark'    => '备注',
            'created_at'=> '创建时间',
            'updated_at'=> '更新时间'
        ];

        return $data ? array_intersect_key($data, $allow_field) : $allow_field;
    }

    public function sources()
    {
        return $this->hasMany('App\Models\Wx\WxAuthSource');
    }
}
