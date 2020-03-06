<?php

namespace App\Http\Middleware;

use Closure;

class GoodsCheck
{
    protected $allowTypes = ['article', 'product'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = [];
        // 商品查询类型(默认文章article)
        if (!$request->has('type') || !in_array($request->type, $this->allowTypes)) {
            $params['type'] = 'article';
        }
        // 当前页码
        if (!$request->has('page')) {
            $params['page'] = 1;
        }
        // 分页条数
        if (!$request->has('per_page')) {
            $params['per_page'] = 10;
        }
        if ($request->has('header.orgid') && $org_id = $request->get('header.orgid')) {
            $params['org_id'] = $org_id;
        }
        // 合并默认请求参数
        $params && $request->merge($params);

        return $next($request);
    }
}
