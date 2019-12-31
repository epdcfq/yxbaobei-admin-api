<?php

namespace App\Models\UCenter;
use App\Models\BaseModel;

class CustomerAccountModel extends BaseModel
{
     /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_ucenter_customer_account';

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
            'fam_account_id'   => '主账号ID',
            'org_id'            => '公司ID',
            'unionid'           => 'unionID',
            'phone'             => '手机号',
            'email'             => '邮箱',
            'username'          => '登录名',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间'
        ];
}
