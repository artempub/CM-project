<?php

namespace VanguardLTE\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'oPass' => 'required',
            'nPass' => 'required|min:6|confirmed',
        ];
        return $rules;
    }
}
