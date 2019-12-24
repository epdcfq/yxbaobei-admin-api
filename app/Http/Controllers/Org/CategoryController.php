<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrgCategoryRepository;

class CategoryController extends Controller
{
	protected $cate;
    public function __construct(OrgCategoryRepository $cate)
    {
    	$this->cate = $cate;
    }

    public function index(Request $request)
    {
    	$data = $this->cate->listCategory($request);
    	return $this->success($data);
    }

    public function options(Request $request)
    {
    	return $this->success([]);
    }

    public function show()
    {

    }
    public function create()
    {

    }
    public function store(Request $request)
    {
    	$result = $this->cate->storeCategory($request->all());
    	return $this->success(['result'=>$result], 'store method success');
    }

    public function destroy(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return $this->fail(1000, ['request'=>$request->all()]);
        }
        // 更新status状态为已删除
        $result = $this->cate->removeCate($request->id);
        if ($result) {
            return $this->success(['result'=>$result]);
        } else {
            return $this->fail(1001, ['result'=>$result]);
        }
    }
}
