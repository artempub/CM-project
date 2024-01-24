<?php 
namespace VanguardLTE\Http\Requests\Agent
{
    class UpdateLoginDetailsRequest extends \VanguardLTE\Http\Requests\Request
    {
        public function rules()
        {
            $agent = $this->getUserForUpdate();
            return [
                'username' => 'regex:/^[A-Za-z0-9]+$/|nullable|unique:users,username,' . $agent->id, 
                'password' => 'nullable|min:6|confirmed'
            ];
        }
        protected function getUserForUpdate()
        {
            return $this->route('agent');
        }
    }

}
