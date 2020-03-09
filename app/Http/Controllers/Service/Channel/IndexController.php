<?php
/** 
 * 栏目管理控制器
 * 
 * 请求       URL             动作                                  作用
 * [GET]    /channel/index          UsersController@index   [列表]
 * [GET]    /channel/index/{id}   UsersController@show      [单个数据详情]
 * [GET]    /channel/index/create   UsersController@create  [显示创建] 创建用户的页面
 * [POST]   /channel/index          UsersController@store   [创建提交] 创建用户
 * [GET]    /channel/index/{id}/edit  UsersController@edit  [显示修改页面]
 * [PATCH]  /channel/index/{id}   UsersController@update    [更新提交]
 * [DELETE] /channel/index/{id}   UsersController@destroy   [删除操作]
 * 
 */
namespace App\Http\Controllers\Service\Channel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Org\ChannelRepository;

class IndexController extends Controller
{
    protected $channel;

    public function __construct(ChannelRepository $channel) {
        // 集成父构造类
        parent::__construct();

        $this->channel = $channel;

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

        $org_id = isset($args['org_id']) && $args['org_id'] ? $args['org_id'] : $args['header.orgid'];
        $plat = $request->has('plat') ? $request->plat : 'pc';
        $result['list'] = $this->channel->getOrgPlatChannel($org_id, $plat, $args);
        // $data = $this->channel->getChannelById(1);
        $result['field'] = [
            ['prop'=>'name', 'label'=>'名称'],
            ['prop'=>'sort', 'label'=>'排序'],
            ['prop'=>'show_plats', 'label'=>'显示平台'],
            ['prop'=>'status_name', 'label'=>'状态'],
            ['prop'=>'opt', 'label'=>'操作', 
                'dropdown' => [
                    ['name'=>'编辑', 'value'=>'edit'],
                    ['name'=>'上架', 'value'=>'up'],
                    ['name'=>'下架', 'value'=>'down'],
                    ['name'=>'新增同级分类', 'value'=>'addLevel'],
                    ['name'=>'新增子分类', 'value'=>'addChild']
                ]
            ]
        ];
        $result['args'] = $args;
        return $this->success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $args = $request->all();
        if (isset($args['id']) && $args['id']>0) {
            $data = $this->channel->updateChannelAndPlats($args['id'], $args);
        } else {
            $data = $this->channel->createChannelAndPlats($args);
        }

        // 根据更新数据，判断是否保存失败
        if ($data) {
            return $this->success($data);
        } else {
            return $this->fail(1001);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->channel->getChannelById($id);
        if (!$id || !$data) {
            return $this->fail(404);
        }

        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->fail(1000);
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
        // return $this->success($request->all(), 'update ');
        $args = $request->all();
        if (!$id || !is_numeric($id)) {
            return $this->fail(1000, ['args'=>$args]);
        }
        // 更新栏目信息
        $data = $this->channel->updateChannelAndPlats($id, $args);
        // 根据更新数据，判断是否保存失败
        if ($data) {
            return $this->success($data);
        } else {
            return $this->fail(1001);
        }
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
