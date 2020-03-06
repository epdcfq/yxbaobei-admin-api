<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息
        if ($exception instanceof ValidationException) {
            return response(['error' => array_first(array_collapse($exception->errors()))], 400);
        }
        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'status' => 401,
                'code' => 401,
                'message' => '登录失效',
                'data' => $exception->errors(),
            ]);
            return response($exception->getMessage(), 401);
        }
        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklisttedException) {
            return response()->json([
                'status' => 50014,
                'code' => 50014,
                'message' => 'token登录失效',
                'data' => $exception->errors(),
            ]);
        }
        // jwttoke你登录失效判断
        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json([
                'status' => 50014,
                'code' => 50014,
                'message' => 'token登录失效',
                'data' => $exception->getMessage(),
            ]);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            return response()->json([
                'status' => 401,
                'code' => 401,
                'message' => '请刷新页面，重新登录',
                'data' => $exception->getMessage(),
            ]);
        }

        // if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        //     return response()->json([
        //         'status' => 404,
        //         'code' => 404,
        //         'message' => '页面不存在',
        //         'data' => $exception->getMessage(),
        //     ]);
        // }

        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
            return response()->json([
                'status' => 50014,
                'code' => 50014,
                'message' => 'token已失效',
                'data' => $exception->getMessage(),
            ]);
        }
        
        return parent::render($request, $exception);
    }
}
