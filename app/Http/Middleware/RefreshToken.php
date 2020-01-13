<?php 
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshToken extends BaseMiddleware
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
      //检查请求中是否带有token 如果没有token值则抛出异常
      $this->checkForToken($request);   
      try{
          //从请求中获取token并且验证token是否过期  若是不过期则请求到控制器处理业务逻辑  若是过期则进行刷新
          if ($request->user = JWTAuth::parseToken()->authenticate()) {       
              return $next($request);
          }
          throw new UnauthorizedHttpException('jwt-auth', '未登录');
      }catch (TokenExpiredException $exception){
          try{
              //首先获得过期token 接着刷新token 再接着设置token并且验证token合法性
              $token = JWTAuth::refresh(JWTAuth::getToken());
              JWTAuth::setToken($token);
              $request->user = JWTAuth::authenticate($token);
              $request->headers->set('Authorization','Bearer '.$token); // token被刷新之后，保证本次请求在controller中需要根据token调取登录用户信息能够执行成功
          }catch (JWTException $exception){
              throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
          }
      }
      //将token值返回到请求头
      return $this->setAuthenticationHeader($next($request), $token);
  }
}