<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Org\ChannelRepository;

class ChannelController extends Controller
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
        $result['fields'] = [
            ['prop'=>'name', 'label'=>'名称'],
            ['prop'=>'show_plats', 'label'=>'显示平台'],
            ['prop'=>'opt', 'label'=>'操作', 
                'dropdown' => [
                    ['name'=>'编辑', 'value'=>'edit'],
                    ['name'=>'上架', 'value'=>'up'],
                    ['name'=>'下架', 'value'=>'down']
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
    public function create(Request $request)
    {
        echo 'create';
        // $this->show($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // $id = $request->has('id') ? intval($request->id) : 0;
        // 根据id获取栏目信息
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
    public function edit(Request $request )
    {
        echo 'edit';
        // $this->show($request);
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
