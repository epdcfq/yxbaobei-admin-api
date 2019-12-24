<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_org_client_menu';

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
    protected $fillable = ['org_id', 'plat_type', 'name','type','url','parent_id','level','key','media_id','appid', 'sort'];

    protected $enums = [
        'plat_type'  => [
            'official'=> '公众号',
            'h5'     => 'H5端',
            'minp'   => '小程序',
            'pc'     => 'PC端'
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

    protected function getEnums($key, $id)
    {
        if (!$key) {
            return $this->enums;
        }
        return $id !== '' && isset($this->enums[$key][$id]) ? $this->enums[$key][$id] : '';
    }

    public function getPlatType($plat_type='')
    {
        return $this->getEnums('plat_type', $plat_type);
    }

    public function filter_field($data=[])
    {
        $allow_field = [
            'org_id' => '门店ID', 
            'plat_type' => '平台类型', 
            'name' => '菜单名称',
            'type'=>'菜单类型',
            'url'=>'地址',
            'parent_id'=>'父类id',
            'level'=>'分类层级',
            'key'=>'菜单key',
            'media_id'=>'素材id',
            'appid'=>'小程序AppID', 
            'sort'=>'排序'
        ];

        return $data ? array_intersect_key($data, $allow_field) : $allow_field;
    }
}
