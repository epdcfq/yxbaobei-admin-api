<?php 
/** 
 * 门店分类管理
 * 
 */
namespace App\Models;

use App\Models\BaseModel;

class OrgInfoModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_info';

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
            'short_name'    => '公司简称',
            'reg_name'      => '注册人姓名',
            'reg_phone'     => '注册人手机号',
            'legal_name'    => '法人姓名',
            'legal_phone'   => '法人手机号',
            'business_name' => '公司注册名称',
            'business_license_num'  => '营业执照信用代码',
            'business_license_img'  => '营业执照图片',
            'business_desc' => '经营范围',
            'status'        => '状态',
            'created_account_id'    => '创建账号ID',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间'
        ];

    protected $enums = [
        'status' => [0=>'无效', 1=>'有效']
    ];

    // 获取状态名称
    public function getStatusName($status)
    {
        return $this->getEnums('status', $status);
    }

    public function templates()
    {
        return $this->hasMany(\App\Models\Org\OrgTemplateModel::class, 'org_id', 'id');
    }
}
