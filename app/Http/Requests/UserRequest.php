<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|between:3,24|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name, .Auth::id()',
            'email' => 'required|email',
            'introduction' => 'max:80'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已重复，请重新填写',
            'name.between' => '用户名介于 3 - 24 个字符之间',
            'name.regex' => '用户名只支持数组、字母和下划线足证',
            'name.required' => '用户名不能为空'
        ];
    }
}
