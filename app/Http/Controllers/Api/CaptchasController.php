<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchasRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchasRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(5);
        \Cache::put($key, ['phone'=>$phone, 'code'=>$captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return response()->json($result)->setStatusCode(201);
    }
}
