<?php 
namespace VanguardLTE\Http\Requests\Agent
{
    class UpdateAgentRequest extends \VanguardLTE\Http\Requests\Request
    {
        public function rules()
        {
            $agent = $this->user();
            return [
                'username' => 'regex:/^[A-Za-z0-9_.]+$/|nullable|unique:users,username,' . $agent->id, 
                'email' => 'nullable|unique:users,email,' . $agent->id, 
                'password' => 'min:6|confirmed', 
                'status' => \Illuminate\Validation\Rule::in(array_keys(\VanguardLTE\Support\Enum\UserStatus::lists()))
            ];
        }
    }

}
