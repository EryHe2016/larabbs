<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //3个判断调价
        //1.用户已经登录  2.用户邮箱未认证  3.访问url不是email验证相关或者退出url
        if($request->user() &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->is('email/*','logout')){
            //根据客户端返回对应的内容
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
