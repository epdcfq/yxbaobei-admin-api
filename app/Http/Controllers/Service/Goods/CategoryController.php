<?php
/** 
 * 商品分类管理控制器
 * 
 * HTTP 请求  URL 动作  作用
 * GET /users  UsersController@index   显示所有用户列表的页面
 * GET /users/{user}   UsersController@show    显示用户个人信息的页面
 * GET /users/create   UsersController@create  创建用户的页面
 * POST    /users  UsersController@store   创建用户
 * GET /users/{user}/edit  UsersController@edit    编辑用户个人资料的页面
 * PATCH   /users/{user}   UsersController@update  更新用户
 * DELETE  /users/{user}   UsersController@destroy 删除用户
 * 
 */

namespace App\Http\Controllers\Service\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Org\GoodsCategory;

class CategoryController extends Controller
{
    protected $category;
    protected $org_id;

    public function __construct(GoodsCategory $category)
    {
        $this->category = $category;
        $this->middleware('header.params');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args = $request->toArray();
        $org_id = isset($args['header.orgid']) ? $args['header.orgid'] : 0;
        $type = $request->has('type') ? $request->type : 'article';
        $result['list'] = $this->category->categoryTreeByOrgId($org_id, $type);
        $result['field'] = [
            ['prop'=>'name', 'label'=>'名称'],
            ['prop'=>'sort', 'label'=>'排序'],
            ['prop'=>'status_name', 'label'=>'状态', 'type'=>'switch'],
            ['prop'=>'opt', 'label'=>'操作', 'dropdown'=> [
                  ['name'=>'编辑', 'value'=>'edit' ],
                  ['name'=>'上架', 'value'=>'up'],
                  ['name'=>'下架', 'value'=>'down'],
                  ['name'=>'新增同级分类', 'value'=>'add'],
                  ['name'=>'新增子分类', 'value'=>'addChild']
            ]
        ]];
        $result['args'] = $args;
        $result['org_id'] = $org_id;
        return $this->success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($org_id, $type, $cate_id)
    {
        // $info = $this->category->getCategoryById($cate_id);
        // return $this->success($info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $args = $request->toArray();
        // 从头部中获取门店id
        $org_id = isset($args['header.orgid']) ? $args['header.orgid'] : 0;
        $args['org_id'] = $org_id;
        $id = $request->has('id') ? $request->id : 0;

        if ($id>0) {
            // 根据id更新分类信息
            $data = $this->category->updateCategoryById($id, $args);
        } else {
            // 新增分类信息
            $data = $this->category->addCategory($args);
        }

        // 根据入库状态，返回数据
        return $data ? $this->success($data) : $this->fail(1001, $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $cate_id)
    {
        $args = $request->toArray();
        $org_id = isset($args['header.orgid']) ? $args['header.orgid'] : 0;
        // 根据id获取分类信息
        $data = $this->category->getCategoryById($cate_id);
        // 无分类或所属分类不是此门店，返回空
        if (
            !$data ||
            $org_id != $data['org_id']) {
            return $this->success([]);
        }

        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $args = $request->toArray();
        // 从头部中获取门店id
        $org_id = isset($args['header.orgid']) ? $args['header.orgid'] : 0;
        $args['org_id'] = $org_id;
        //$id = $request->has('id') ? $request->id : 0;

        $data = [];
        if ($id>0) {
            // 根据id更新分类信息
            $data = $this->category->updateCategoryById($id, $args);
        }
        // 根据入库状态，返回数据
        return $data ? $this->success($data) : $this->fail(1001, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
