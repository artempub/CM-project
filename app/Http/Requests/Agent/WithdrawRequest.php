<?php 
namespace VanguardLTE\Http\Requests\Agent
{
    class WithdrawRequest extends \VanguardLTE\Http\Requests\Request
    {
        public function rules()
        {
            return [
                'txtamount' => 'required', 
                'txtcurrency' => 'required', 
			];
        }
        public function messages()
        {
            return [

			];
        }
    }

}
