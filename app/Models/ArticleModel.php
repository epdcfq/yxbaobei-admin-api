<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\ArticleContentModel;

class ArticleModel extends BaseModel
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_article';

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

    /** 
     * 可批量赋值的字段
     * 
     */
    protected $fillable = ['org_id','title','summary','focus_img', 'swiper_imgs','cate_id','cate_pids','start_time','end_time','imp_star','tag_ids', 'tag_names','author_id','author','status', 'published', 'published_platids', 'created_at', 'updated_at'];

    protected $enums = [
        'status'    => [
            '0'     => '已删除',
            '1'     => '草稿',
            '99'    => '正常'
        ],
        'published' => [
            '0'     => '待发布',
            '1'     => '已发布'
        ],
        'imp_star'  => [
            '0'     => '默认',
            '1'     => '1星级',
            '2'     => '2星级',
            '3'     => '3星级',
            '4'     => '4星级',
            '5'     => '5星级'
        ]
    ];

    public function contents()
    {
    	return $this->hasOne(ArticleContentModel::class, 'article_id', 'id');
    }

    

    public function getStatusName($status='')
    {
        return $this->getEnums('status', $status);
    }

    public function getImpStarName($imp_star)
    {
        return $this->getEnums('imp_star', $imp_star);
    }

    public function getPublishedName($published)
    {
        return $this->getEnums('published', $published);
    }

    public function filter_field($data=[])
    {
        $allow_field = [
            'org_id'    => '门店ID',
            'title'     => '标题',
            'summary'   => '摘要',
            'focus_img' => '焦点图',
            'swiper_imgs' => '轮播图',
            'cate_id'   => '分类',
            'cate_pids' => '多级分类父id',
            'start_time'    => '开始时间',
            'end_time'  => '结束时间',
            'imp_star'  => '推荐星级',
            'author_id' => '作者id',
            'author'    => '作者',
            'status'    => '发布状态',
            'published'=> '发布状态',
            'published_platids' => '发布平台',
            'author_id' => '作者id',
            'author'    => '作者名称',
            'is_draft'  => '草稿状态',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间'
        ];

        return $data ? array_intersect_key($data, $allow_field) : $allow_field;
    }
}
