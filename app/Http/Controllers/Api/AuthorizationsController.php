<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Socialite;
use Overtrue\Socialite\AccessToken;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = Socialite::driver($type);

        if($code=$request->code){
            $oauthUser = $driver->user();
        }else{
            $access_token = $request->access_token;
            $openid = $request->openid;
            $accessToken = new AccessToken(['access_token' => $access_token, 'openid'=>$openid]);
            $oauthUser = $driver->user($accessToken);
        }

        /*
         * socialite 3.*版本以上用法
         * $driver = Socialite::create($type);
         * try{
            if($code = $request->code){
                $oauthUser = $driver->userFromCode($code);
            }else{
                //微信需要增加openid
                if($type=='wechat'){
                    $driver->withOpenid($request->openid);
                }
                $oauthUser = $driver->userFromToken($request->access_token);
            }
        }catch(\Exception $e){
            throw new AuthenticationException('参数错误，未获取用户信息');
        }*/

        if(!$oauthUser->getId()){
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch($type){
            case 'wechat':
                //$unionid = $oauthUser->getRaw()['unionid'] ?? null;

                $user = User::where('weixin_openid', $oauthUser->getId())->first();
                //没有用户默认创建一个
                if(!$user){
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                    ]);
                }
                break;
        }

        return response()->json(['token'=>$user->id]);
    }

    public function login(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;
        $credentials['password'] = $request->password;

        try{
            $result = \Auth::attempt($credentials);
        }catch(\Exception $e){
            dd($credentials);
        }
        //验证密码是否正确
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            abort(403,'用户账号或密码错误');
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL()*60
        ])->setStatusCode(201);
    }
}
