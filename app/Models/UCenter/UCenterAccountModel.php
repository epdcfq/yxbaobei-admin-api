<?php
/** 
 * 登录账号 jwt model类
 */
namespace App\Models\UCenter;
use App\Models\BaseModel;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UCenterAccountModel extends Authenticatable implements JWTSubject
{
     /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'f_ucenter_account';

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    protected $platid = 1;

    // 字段解释
    protected $allow_field = [
        'fam_account_id'   => '主账号ID',
        'org_id'            => '公司ID',
        'unionid'           => 'unionID',
        'phone'             => '手机号',
        'email'             => '邮箱',
        'username'          => '登录名',
        'created_at'        => '创建时间',
        'updated_at'        => '更新时间'
    ];

    /** 
     * 字段解释及过滤
     * @param     array       $data [description]
     * @return    [type]            [description]
     */
    public function filter_field($data=[])
    {
        return $data ? array_intersect_key($data, $this->allow_field) : $this->allow_field;
    }

    public function setPlat($platId)
    {
        $this->platid = $platId;
    }

    public function getPlat($platId)
    {
        $this->platid = $platId;
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['platid'=>$this->platid];
    }
}
