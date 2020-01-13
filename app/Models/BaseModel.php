<?php 
namespace App\Models;

use \DB;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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

    // 枚举型字段
    protected $enums = [];

    // 字段解释
    protected $allow_field = [];

    /** 
     * 字段解释及过滤
     * @param     array       $data [description]
     * @return    [type]            [description]
     */
    public function filter_field($data=[])
    {
        return $data ? array_intersect_key($data, $this->allow_field) : $this->allow_field;
    }

    public function getEnums($key, $id)
    {
        if (!$key) {
            return $this->enums;
        }
        return $id !== '' && isset($this->enums[$key][$id]) ? $this->enums[$key][$id] : '';
    }

    /** 
     * 查询原生SQL
     * 
     * @param     [string]      $sql    [查询sql]
     * @param     [array]       $params [查询参数]
     * 
     * @return    [array]
     */
    public function queryAll($sql, $params=[], $orderby='', $page=1, $pagesize=20)
    {
        if ($orderby) {
            $sql .= ' ORDER BY '.$orderby;
        }
        $sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
        if (isset($_GET['debug'])) {
            echo $sql.chr(10);
        }
        // 查询原生SQL
        $result = DB::select($sql, $params);
        // 将stdObj对象转换为数组格式
        return array_map('get_object_vars', $result);
    }

    /** 
     * @Author    Hybrid
     * @DateTime  2019-12-24
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @param     [type]      $sql    [description]
     * @param     array       $params [description]
     * @return    [type]              [description]
     */
    public function queryOne($sql, $params=[])
    {
        $query = $this->queryAll($sql, $params);

        return $query ? array_shift($query) : $query;
    }

    public function queryCount($sql, $params=[])
    {
        $result = DB::select($sql, $params)->get();
        return $result;
    }
}