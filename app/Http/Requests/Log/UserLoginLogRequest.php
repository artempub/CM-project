<?php

namespace VanguardLTE\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginLogRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required'
        ];
    }
}
