<?php 
namespace VanguardLTE\Http\Requests\Agent
{
    class UpdateProfileLoginDetailsRequest extends UpdateLoginDetailsRequest
    {
        protected function getUserForUpdate()
        {
            return Auth::user();
        }
    }

}
