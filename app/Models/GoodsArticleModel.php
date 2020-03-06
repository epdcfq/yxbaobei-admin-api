<?php

namespace App\Models;

use App\Models\BaseModel;

class GoodsArticleModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_goods_article';

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
    /** 
     * 可批量赋值的字段
     * 
     */
    protected $fillable = ['goods_id','content','created_at', 'updated_at'];
}
