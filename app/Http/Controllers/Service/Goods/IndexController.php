<?php
/** 
 * 商品资源管理控制器（增、删、改、列表查询）
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
use App\Http\Resources\ArticleResource;
use App\Repositories\Goods\GoodsRepository;
use App\Repositories\Goods\ProductRepository;
use App\Repositories\Goods\ArticleRepository;
use App\Repositories\Org\GoodsCategory;

class IndexController extends Controller
{
    protected $goods;
    protected $article;
    protected $product;
    protected $category;

    /** 
     * 构造类
     * 
     * @return    [type]      [description]
     */
    public function __construct(GoodsRepository $goods, ArticleRepository $article, ProductRepository $product, GoodsCategory $category) {
        // 初始化服务类
        $this->goods    = $goods;
        $this->article  = $article;
        $this->product  = $product;

        // 获取头部门店id
        $this->middleware('header.params');
        // 初始化商品中间件
        $this->middleware('goods.check');
    }

    // 必要参数校验
    protected function argsRule($args)
    {
        # 门店id
        $args['org_id'] = isset($args['header.orgid']) ? intval($args['header.orgid']) : 0;
        // 列表类型[article:资讯; product:商品]
        $args['type'] = isset($args['type']) ? $args['type'] : '';
        // 参数必要性校验
        if (!$args['org_id'] || !$args['type']) {
            return [];
        }

        return $args;
    }

    /**
     * 商品列表页
     * 
     * @pathinfo get api/service/goods/index
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$args = $this->argsRule($request->all())) {
            return $this->fail(1000, ['args'=>$request->all()]);
        }

        // 标题搜序偶
        $title = $request->title;
        // 日期搜索
        $dates = $request->dates ? $request->dates : '';
        // 获取列表数据
        switch ($args['type']) {
            case 'product':
                # 默认资讯列表
                $data = $this->article->pageArticleList($args['org_id'], $args, $request->page, $request->per_page);
                break;
            
            default:
                # 默认资讯列表
                $data = $this->article->pageArticleList($args['org_id'], $args, $request->page, $request->per_page);
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
        if (!$args = $this->argsRule($request->all())) {
            return $this->fail(1000, ['args'=>$request->all()]);
        }
        // 获取分类列表
        $result = ['cate'=>[], 'info'=>[]];
        $result['cate'] = $this->category->categoryTreeByOrgId($args['org_id'], $args['type']);
        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->article->getGoodsById($id);
        if (!$data) {
            return $this->fail(404, '未找到相关文章');
        }
        // 转换成数组
        $data = $data->toArray();
        // 内容补全
        $type = $data['type'];
        if (!isset($data[$type]) || !is_array($data[$type]) || !isset($data[$type]['content'])){
            $data[$type] = ['content'=>''];
        }

        return $this->success($data, '[show2] 资讯显示页访问成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
