<?php

namespace App\Http\Middleware;

use Closure;

class HeaderParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = ['header.orgid' => 0, 'header.token' => ''];
        // 获取orgid
        if ($orgid = $request->header('orgid')) {
            $header['header.orgid'] = intval($orgid);
        }
        if ($authorization = $request->header('authorization')) {
            if (is_string($authorization)) {
                list($bearer, $header['header.token']) = explode(' ', $authorization);
            }
        }

        // 合并request变量
        $request->merge($header);
        return $next($request);
    }
}
