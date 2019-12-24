<?php 
/** 
 * 门店分类管理
 * 
 */
namespace App\Models;

use App\Models\BaseModel;

class WxAuthSourceModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_wx_auth_source';

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

    public function filter_field($data=[])
    {
        $allow_field = [
            'unionid'   => 'unionId',
            'openId'    => 'openid',
            'org_id'    => '门店id',
            'source_id' => '来源id',
            'source_tag'=> '来源标识',
            'coll_user_id' => '采单人员id',
            'saler_id'  => '顾问id',
            'created_at'=> '创建时间',
            'updated_at'=> '更新时间'
        ];

        return $data ? array_intersect_key($data, $allow_field) : $allow_field;
    }
}
