<?php

namespace VanguardLTE\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'begin_time' => 'required',
            'end_time' => 'required',
        ];
    }
}
