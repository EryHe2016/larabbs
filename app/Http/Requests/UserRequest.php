<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,8|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,'.Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:png.jpg,jpeg,gif|dimensions:min_width=208,min_height=208',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名已经存在，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3-8 字符之间。',
            'email.required' => '邮箱不能为空。',
            'email.email' => '请填写正确格式的邮箱。',
            'introduction' => '个人简介不能超过80个字符。',
            'avatar.mimes' => '头像必须是png，jpg，jpeg，gif格式的图片',
            'avatar.dimensions' => "图片清晰度不够，宽和高需要208px以上"
        ];
    }
}
