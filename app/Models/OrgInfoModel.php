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

    protected $enums = [
        'status' => [0=>'无效', 1=>'有效']
    ];

    // 获取状态名称
    public function getStatusName($status)
    {
        return $this->getEnums('status', $status);
    }

    public function filter_field($data=[])
    {
        $allow_field = [
            'name'    => '名称',
            'desc'     => '描述',
            'icon_img'  => '图标',
            'channel_id'   => '频道id',
            'parentid' => '父级id',
            'sort' => '排序',
            'ext'   => '扩展信息',
            'status' => '状态',
            'created_at'    => '创建时间',
            'updated_at'  => '更新时间'
        ];

        return $data ? array_intersect_key($data, $allow_field) : $allow_field;
    }
}
