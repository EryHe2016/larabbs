<?php

namespace App\Http\Requests\Api;


class SocialAuthorizationRequest extends FormRequest
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
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without|string'
        ];

        if ($this->social_type == 'wechat' && !$this->code){
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
