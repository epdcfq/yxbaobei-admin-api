<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Repositories\Goods\GoodsRepository;
use App\Repositories\Goods\ProductRepository;
use App\Repositories\Goods\ArticleRepository;


class GoodsController extends Controller
{
    protected $goods;
    protected $article;
    protected $product;

    public function __construct(GoodsRepository $goods, ArticleRepository $article, ProductRepository $product) 
    {
        $this->goods    = $goods;
        $this->article  = $article;
        $this->product  = $product;
        // 获取头部门店id
        $this->middleware('header.params');
        // 初始化商品中间件
        $this->middleware('goods.check');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orgid = $request->orgid;

        // 列表类型[article:资讯; product:商品]
        $type = $request->type;
        // 标题搜序偶
        $title = $request->title;
        // 日期搜索
        $dates = $request->dates ? $request->dates : '';
        // 获取列表数据
        switch ($type) {
            case 'product':
                # 默认资讯列表
                $data = $this->article->pageArticleList($orgid, $request->all(), $request->page, $request->per_page);
                break;
            
            default:
                # 默认资讯列表
                $data = $this->article->pageArticleList($orgid, $request->all(), $request->page, $request->per_page);
                break;
        }
        
        $data['args'] = $request->all();
        
        return $this->success($data, '[article/index] 列表页访问成功');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $data = $this->
        return $this->success($data, '[create] 页访问成功');
    }

    /**
     * [新增|修改] 保存文章
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->has('id') ? intval($request->id) : 0;
        $type = $request->has('type') ? $request->type : 'article';
        if ($id) {
            // 更新文章
        } else {
            // 新增文章
        }

        // $data = $request->all();
        if ($id>0) {
           $data = $this->goods->updateGoodsAndExt($id, $request->all());
        } else {
            $data = $this->goods->createGoodsAndExt($request->all());
        }
        
        return $this->success($data, '操作成功');
    }

    /**
     * 根据id获取文章详情
     *
     * @param  int  $id     文章id
     * 
     * @return json
     */
    public function show($id)
    {
        $data = $this->article->getGoodsById($id);
        if (!$data) {
            return $this->fail(404, '未找到相关文章');
        }

        return $this->success($data, '[show] 资讯显示页访问成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        return $this->success($data, '[edit] 页访问成功');
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
        $data = [];
        return $this->success($data, '[update] 页访问成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        return $this->success($data, '[destory] 页访问成功');
    }
}
