<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArticleModel;
use App\Http\Resources\ArticleResource;
use App\Repositories\ArticleRepository;

class ArticleController extends Controller
{
    protected $article;

    public function __construct(ArticleRepository $article) 
    {
        $this->article = $article;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 标题搜序偶
        $title = $request->title;
        // 日期搜索
        $dates = $request->dates ? $request->dates : '';

        $data = $this->article->articleList($request);
        
        return $this->success($data, '[article/index] 列表页访问成功');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        if ($id) {
            // 更新文章
        } else {
            // 新增文章
        }

        // $data = $request->all();
        $data = $this->article->modifyArticle($request->all());
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
        $data = $this->article->getArticleById($id);
        if (!$data) {
            return $this->fail(404, '未找到相关文章');
        }

        return $this->success(new ArticleResource($data), '[show] 资讯显示页访问成功');
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
