<?php

namespace App\Http\Controllers\UCenter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UCenter\CustomerAccountRepository;

class CustomerController extends Controller
{
	protected $customer;
	public function __construct(CustomerAccountRepository $customer) 
	{
		$this->customer = $customer;
	}
	/** 
	 * 门店索引/列表管理
     *     路由：[get] xxx/customer/info
	 * 
	 * @return    [type]      [description]
	 */
    public function index(Request $request)
    {
    	$data = $this->customer->pageListCustomer($request->all());
    	return $this->success($data);
    }

    /** 
     * 创建表单显示路由
     * 
     *     路由：[get] xxx/customer/info/create
     * 
     * @return    [type]      [description]
     */
    public function create(Request $request)
    {
        $data = [];
        // $data = $this->customer->getCreateField();
        
        return $this->success($data, 'create method success');
    }
    /** 
     * 保存创建的数据
     * 
     *     路由：[post] xxx/customer/info
     * 
     * @return    [type]      [description]
     */
    public function store(Request $request)
    {
        $data = false;
        // $data = $this->customer->storecustomer($request->all());
        if (!$data['status']) {
            return $this->fail(1001, $data, '操作失败');
        } else {
            return $this->success($data, 'store method success');
        }
    }

    /** 
     * 显示编辑页面的数据
     * 
     *     路由：[get] xxx/customer/{id}
     * 
     * @return    [type]      [description]
     */
    public function show(Request $request, $id)
    {
    	if (!$id) {
    		return $this->fail(1000, ['id'=>$id, 'request'=>$request->all()]);
    	}
    	// 查询数据
    	$data = $this->customer->getcustomerById($id);

    	return $this->success($data);
    }

    /** 
     * 保存编辑的数据
     * 
     *     路由：[put/patch] xxx/customer/{id}
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
     *     路由：[delete] xxx/customer/{id}
     * 
     * @return    [type]      [description]
     */
    public function destroy(Request $request, $id)
    {
        return $this->success(['id'=>$id, 'request'=>$request->all()], 'destroy method success');
    }
}
