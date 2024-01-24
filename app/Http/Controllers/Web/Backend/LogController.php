<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use VanguardLTE\Http\Controllers\Controller;
use VanguardLTE\Http\Requests\Log\CashRequest;
use VanguardLTE\Http\Requests\Log\GameRequest;
use VanguardLTE\Http\Requests\Log\UserLoginLogRequest;
use VanguardLTE\StatGame;
use VanguardLTE\Transaction;
use VanguardLTE\User;
use VanguardLTE\UserActivity;

class LogController extends Controller
{

    function sub_childs($parents_id)
    {
        $childs = User::where('role_id', 2)->whereIn('parent_id', $parents_id)->pluck('id');
        if (count($childs) == 0) {
            return $parents_id;
        } else {
            $childs_array = [];
            foreach ($childs as $key => $value) {
                array_push($childs_array, $value);
            }
            return array_merge($parents_id, $this->sub_childs($childs_array));
        }
    }

    public function game_log(){
        return view('backend.log.game-search');
    }

    public function game_log_view(GameRequest $request){
        $gameLogs=StatGame::orderBy('user_id');

        $username=$request->username;
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $begin_time=$request->begin_time;
        $end_time=$request->end_time;

        if($username){
            $users=User::where('role_id',1)->where('username', 'like', '%'.$username.'%');
            if(auth()->user()->role_id == 2){
                $users=$users->where('parent_id', auth()->user()->id)->pluck('id');
            }elseif(auth()->user()->role_id == 10){
                $users=$users->where('parent_id', auth()->user()->parent_id)->pluck('id');
            }else{
                $agents = User::where('role_id', 2)->where('parent_id', auth()->user()->id)->pluck('id');
                $agents_array = [];
                foreach ($agents as $key => $agent) {
                    array_push($agents_array, $agent);
                }

                $all_agents_id = $this->sub_childs($agents_array);
                $users = $users->whereIn('parent_id', $all_agents_id)->pluck('id');
            }
            $gameLogs->whereIn('user_id', $users)->get();
        }


        $start_date=date('Y-m-d', strtotime($start_date));
        $end_date=date('Y-m-d', strtotime($end_date));
        $start_point=$start_date.' '.$begin_time;
        $end_point=$end_date.' '.$end_time;
        $gameLogs->where('date_time', '>', $start_point)
                ->where('date_time', '<', $end_point);
        
        $data['gameLogs']=$gameLogs->get();

        return view('backend.log.game-view', $data);
    }

    public function cash_log(){
        return view('backend.log.cash-search');
    }

    public function cash_log_view(CashRequest $request){
        $cashLogs=Transaction::orderBy('user_id');

        $username=$request->username;
        $start_date=$request->start_date;
        $start_time=$request->start_time;
        $end_date=$request->end_date;
        $end_time=$request->end_time;

        if($username){
            $users=User::where('role_id',1)->where('username', 'like', '%'.$username.'%');
            if(auth()->user()->role_id == 2){
                $users=$users->where('parent_id', auth()->user()->id)->pluck('id');
            }elseif(auth()->user()->role_id == 10){
                $users=$users->where('parent_id', auth()->user()->parent_id)->pluck('id');
            }else{
                $agents = User::where('role_id', 2)->where('parent_id', auth()->user()->id)->pluck('id');
                $agents_array = [];
                foreach ($agents as $key => $agent) {
                    array_push($agents_array, $agent);
                }

                $all_agents_id = $this->sub_childs($agents_array);
                $users = $users->whereIn('parent_id', $all_agents_id)->pluck('id');
            }
            $cashLogs->whereIn('user_id', $users)->get();
        }


        $start_date=date('Y-m-d', strtotime($start_date));
        $end_date=date('Y-m-d', strtotime($end_date));
        $start_point=$start_date.' '.$start_time;
        $end_point=$end_date.' '.$end_time;
        $cashLogs->where('created_at', '>', $start_point)
                ->where('created_at', '<', $end_point);
        
        
        $data['cashLogs']=$cashLogs->get();
        return view('backend.log.cash-view', $data);
    }

    public function user_login_log(){
        return view('backend.log.user_login_log_search');
    }

    public function user_login_log_view(UserLoginLogRequest $request){
        $userLogs=UserActivity::orderBy('created_at', 'DESC');

        $username=$request->username;

        if($username){
            $users=User::where('role_id',1)->where('username', 'like', '%'.$username.'%');
            if(auth()->user()->role_id == 2){
                $users=$users->where('parent_id', auth()->user()->id)->pluck('id');
            }elseif(auth()->user()->role_id == 10){
                $users=$users->where('parent_id', auth()->user()->parent_id)->pluck('id');
            }else{
                $agents = User::where('role_id', 2)->where('parent_id', auth()->user()->id)->pluck('id');
                $agents_array = [];
                foreach ($agents as $key => $agent) {
                    array_push($agents_array, $agent);
                }

                $all_agents_id = $this->sub_childs($agents_array);
                $users = $users->whereIn('parent_id', $all_agents_id)->pluck('id');
            }
            $userLogs->whereIn('user_id', $users)->get();
        }

        
        $data['userLogs']=$userLogs->limit(10)->get();
        return view('backend.log.user-login-log-view', $data);
    }

    public function agent_login_log_view(){
        $agentLogs=UserActivity::orderBy('created_at','DESC');

        $users=User::where('role_id',2);
        if(auth()->user()->role_id == 2){
            $agentLogs->where('user_id', auth()->user()->id)->get();
        }elseif(auth()->user()->role_id == 10){
            $users=$users->where('parent_id', auth()->user()->parent_id)->pluck('id');
        }else{
            $agents = User::where('role_id', 2)->where('parent_id', auth()->user()->id)->pluck('id');
            $agents_array = [];
            foreach ($agents as $key => $agent) {
                array_push($agents_array, $agent);
            }
            $all_agents_id = $this->sub_childs($agents_array);
            $users = $users->whereIn('id', $all_agents_id)->pluck('id');
            $agentLogs->whereIn('user_id', $users)->get();
        }
        
        $data['agentLogs']=$agentLogs->limit(1000)->get();
        
        return view('backend.log.agent-login-log', $data);
    }
}
