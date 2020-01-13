<?php 
/** 
 * 门店分类管理
 * 
 */

namespace App\Models;

use App\Models\BaseModel;

class WechatMessageModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_wechat_message';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

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
        'org_id'   => '公司ID',
        'msg_id'            => '消息ID',
        'msg_type'          => '消息类型',
        'from_user_name'    => '发送方账号',
        'to_user_name'      => '接收方账号',
        'content'           => '内容',
        'data_json'         => '更新时间',
        'create_time'       => '时间',
        'status'            => '状态'
    ];
}
