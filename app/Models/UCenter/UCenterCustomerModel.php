<?php

namespace App\Models\UCenter;

use App\Models\BaseModel;

class UCenterCustomerModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_ucenter_customer';

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

    // 字段解释
    protected $allow_field = [
    	'org_id'            => '门店ID',
        'fam_account_id'    => '主账号ID',
        'account_id'        => '注册人手机号',
        'role_id'           => '角色ID',
        'realname'          => '真实姓名',
        'nickname'          => '昵称',
        'sex'               => '性别',
        'birthday'          => '生日',
        'identity_num'      => '身份证号',
        'province'          => '省',
        'city'              => '城市',
        'address'           => '地址',
        'vip_level'         => '会员等级',
        'source_ids'        => '来源',
        'created_at'        => '创建时间',
        'updated_at'        => '更新时间'
    ];

    protected $enums = [
        'sex'   => [
            0 => '未知',
            1 => '男',
            2 => '女'
        ],
        'status'=> [
            0 => '无效',
            1 => '有效'
        ]
    ];
}
