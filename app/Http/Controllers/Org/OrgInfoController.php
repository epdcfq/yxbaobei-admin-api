<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrgInfoRepository;

class OrgInfoController extends Controller
{
	protected $org;
	public function __construct(OrgInfoRepository $org) 
	{
		$this->org = $org;
	}
	/** 
	 * 门店索引/列表管理
     *     路由：[get] xxx/org/info
	 * 
	 * @return    [type]      [description]
	 */
    public function index(Request $request)
    {
    	$data = $this->org->pageListOrg($request->all());
    	return $this->success($data);
    }

    /** 
     * 创建表单显示路由
     * 
     *     路由：[get] xxx/org/info/create
     * 
     * @return    [type]      [description]
     */
    public function create(Request $request)
    {
        $data = $this->org->getCreateField();
        
        return $this->success($data, 'create method success');
    }
    /** 
     * 保存创建的数据
     * 
     *     路由：[post] xxx/org/info
     * 
     * @return    [type]      [description]
     */
    public function store(Request $request)
    {
        $data = false;
        // $data = $this->org->storeOrg($request->all());
        if (!$data['status']) {
            return $this->fail(1001, $data, '操作失败');
        } else {
            return $this->success($data, 'store method success');
        }
    }

    /** 
     * 显示编辑页面的数据
     * 
     *     路由：[get] xxx/org/{id}
     * 
     * @return    [type]      [description]
     */
    public function show(Request $request, $id)
    {
    	if (!$id) {
    		return $this->fail(1000, ['id'=>$id, 'request'=>$request->all()]);
    	}
    	// 查询数据
    	$data = $this->org->getOrgById($id);

    	return $this->success($data);
    }

    /** 
     * 保存编辑的数据
     * 
     *     路由：[put/patch] xxx/org/{id}
     * 
     * @return    [type]      [description]
     */
    public function update(Request $request, $id)
    {
        return $this->success(['id'=>$id, 'request'=>$request->all()], 'update method success');
    }

    /** 
     * 删除数据
     * 
     *     路由：[delete] xxx/org/{id}
     * 
     * @return    [type]      [description]
     */
    public function destroy(Request $request, $id)
    {
        return $this->success(['id'=>$id, 'request'=>$request->all()], 'destroy method success');
    }
}
