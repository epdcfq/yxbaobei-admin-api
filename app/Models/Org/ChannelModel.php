<?php

namespace App\Models\Org;

use App\Models\BaseModel;

class ChannelModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_channel';

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

    public function plats()
    {
        return $this->hasMany(\App\Models\Org\ChannelPlatsModel::class, 'channel_id', 'id');
    }
}
