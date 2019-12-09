<?php

namespace App\Http\Controllers\Adm\Sys;

use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sys\RoleList;
use App\Http\Requests\Sys\RoleRequest;

class RolesController extends Controller
{
    /** 
     * [列表] 角色列表
     * 
     *     @api [get] /roles
     * 
     * @return    [type]      [description]
     */
    public function index(Request $request, RoleList $role)
    {
        $data = $role::where('status' ,'1')->get();
        
        return $this->success($data);
    }

    /** 
     * [详情]显示指定角色
     * 
     * @return    [type]      [description]
     */
    public function show($id)
    {

    }

    /******************** [创建/保存]角色 ********************/
    /** 
     * [新增-视图] 创建角色单页面
     * 
     *     @api [get] /roles/create
     *     @route_name=roles.create
     * 
     * @return    [type]      [description]
     */
    public function create()
    {
    	$data = RoleList::all();
    	return $this->success();
    }

    /** 
     * [新增-入库操作] 将新创建的角色存储到存储器
     * 
     * @return    [type]      [description]
     */
    public function store(RoleRequest $request)
    {
        if ($error = $request->validated()) {
            return $error;
        }

        return $this->success();
    }


    /******************** [编辑/修改]角色 ********************/

    /**
     * [编辑-显示视图] 指定文章的表单页面
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * [编辑-更新入库] 在存储器中更新指定角色
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) 
    {

    }

    /**
     * [删除] 从存储器中移除指定文章
     * 
     * @param int $id
     * @return Response
     */
    public function destory($id)
    {

    }
}
