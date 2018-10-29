<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function authorize()
    {
        // Using policy for Authorization
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|min:2'
        ];
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
