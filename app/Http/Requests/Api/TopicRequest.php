<?php

namespace App\Http\Requests\Api;


class TopicRequest extends FormRequest
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
            'title' => 'required|string',
            'content' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id'
        ];
    }

    public function attributes()
    {
        return [
            'title' => '话题标题',
            'content' => '话题内容',
            'category_id' => '话题分类'
        ];
    }
}
