<?php

namespace App\Http\Controllers\Adm\Sys;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolePageController extends Controller
{
    public function list()
    {
    	return $this->success();
    }

    public function add()
    {
    	return $this->success();
    }

    public function edit()
    {
		return $this->success();
    }

    /**
     * 菜单权限 控制
     */
    public function permission(Request $request)
    {
    	$pageid = $request->input['pageid'];

    	return $this->success();
    }
}
