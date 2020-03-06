<?php

namespace App\Http\Controllers\Web\Pc;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\WebController;

class HomeController extends WebController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $org_id=0)
    {
        // 机构ID
        $org_id = $org_id > 0 ? intval($org_id) : 1;
        $orgInfo = $this->getOrgById($org_id);
        // 获取当前模板内容
        $navInfo = $this->getCurTempInfo();
        // print_r($navInfo);die;
        return $this->setView($navInfo['view_filename']);
    }

    /** 
     * 关于我们
     * 
     * @return    [type]      [description]
     */
    public function about(Request $request, $org_id=0)
    {
        // 机构ID
        $org_id = $org_id > 0 ? intval($org_id) : 1;
        $orgInfo = $this->getOrgById($org_id);
        // 获取当前模板内容
        $navInfo = $this->getCurTempInfo();
        // print_r($navInfo);die;
        return $this->setView($navInfo['view_filename']);
    }

    /**
     * 文章列表页
     * 
     * @return    [type]      [description]
     */
    public function article(Request $request, $org_id=0)
    {
        // 机构ID
        $org_id = $org_id > 0 ? intval($org_id) : 1;
        $orgInfo = $this->getOrgById($org_id);
        // 获取当前模板内容
        $navInfo = $this->getCurTempInfo();
        // print_r($navInfo);die;
        return $this->setView($navInfo['view_filename']);
    }

    /** 
     * 文章详情页
     * 
     * @return    [type]      [description]
     */
    public function articleShow(Request $request, $org_id=0, $article_id=0)
    {
        // 机构ID
        $org_id = $org_id > 0 ? intval($org_id) : 1;
        $orgInfo = $this->getOrgById($org_id);
        // 获取当前模板内容
        $navInfo = $this->getCurTempInfo();
        // print_r($navInfo);die;
        return $this->setView($navInfo['view_filename']);
    }
}
