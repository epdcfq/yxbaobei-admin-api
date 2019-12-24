<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleContentModel extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_article_content';

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

}
