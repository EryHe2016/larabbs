<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use App\Models\UserPhoneCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request)
    {
        $captchaData = \Cache::get($request->captcha_key);
        if(!$captchaData){
            abort(403, '图片验证码已失效');
        }
        if(!hash_equals($request->captcha_code, $captchaData['code'])){
            throw new AuthenticationException('验证码错误');
        }

        $phone = $captchaData['phone'];

        if(!app()->environment('production')){
            $code = '1234';
        }else{
            //生成四位随机数 左侧补0
            $code = str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);

            try{
                UserPhoneCode::create(['phone'=>$phone, 'code'=>$code]);
            }catch(\Exception $e){
                abort(500, $e->getMessage());
            }
        }

        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(10);
        //缓存验证码10分钟过期
        \Cache::put($key, ['phone'=>$phone, 'code'=>$code], $expiredAt);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);

    }
}
