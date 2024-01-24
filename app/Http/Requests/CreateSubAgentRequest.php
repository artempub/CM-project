<?php

namespace VanguardLTE\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubAgentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'username' => 'required|regex:/^[A-Za-z0-9]+$/|unique:users,username', 
            'password' => 'required|min:6|confirmed',
        ];
        return $rules;
    }
}
