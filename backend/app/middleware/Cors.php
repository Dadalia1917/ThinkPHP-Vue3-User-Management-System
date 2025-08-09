<?php

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;

/**
 * 跨域中间件
 */
class Cors
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        // 如果是OPTIONS请求，直接返回成功响应
        if ($request->isOptions()) {
            $response = response('', 200);
            $response->header([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin',
                'Access-Control-Allow-Credentials' => 'true'
            ]);
            return $response;
        }

        // 继续处理请求
        $response = $next($request);

        // 为响应添加跨域头
        $response->header([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin',
            'Access-Control-Allow-Credentials' => 'true'
        ]);

        return $response;
    }
}
