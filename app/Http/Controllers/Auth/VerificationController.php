<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //设定所有动作都必须登录后才能访问
        $this->middleware('auth');
        //只有verify动作需要签名signed中间件认证
        $this->middleware('signed')->only('verify');
        //throttle:6,1 限制一分钟类最多请求6次 throttle中间件是框架提供的访问频率限制功能
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
