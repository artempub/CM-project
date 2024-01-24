<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use VanguardLTE\Http\Controllers\Controller;
use VanguardLTE\StatGame;
use VanguardLTE\User;
use VanguardLTE\Transaction;

class UserCashController extends Controller
{
    private $users = null;
    public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
    {
        $this->middleware('auth');
        $this->users = $users;
    }

    public function index(Request $req){
        $auth_id= auth()->user()->role_id == 10 ?auth()->user()->parent_id : auth()->user()->id;
        $result=User::where('role_id', 1)->where('parent_id', $auth_id);

        if($req->username){
            $result=$result->where('username', 'like', '%'.$req->username.'%');
        }
        $result=$result->get();

        $from_date=  $req->from_date ? date('Y-m-d H:i', strtotime($req->from_date)): date('Y-m-d 00:00:00');
        $to_date = $req->to_date ? date('Y-m-d H:i', strtotime($req->to_date)): date('Y-m-d 23:59:59');
      
        $start_date = $from_date;
        $end_date = $to_date;


        foreach ($result as $key => $value) {
           $total_game= StatGame::where('user_id' , $value->id);
            $total_game = $total_game->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date);

            $total_win=0-$total_game->sum('win');

            $total_bet=$total_game->where(function($query){
                $query->where('percent', '>', '0')
                    ->orWhere('game', 'like', '% DG');
            })->sum('bet');

            $pl=$total_bet+$total_win;
            $value->total_bet=$total_bet;
            $value->total_win=$total_win;
            $value->pl=$pl;
        }
        return view('backend.user.cash.cash', compact('result', 'start_date','end_date'));
    }

    public function charge(Request $request){
        $statuses = ['' => trans('app.all')] + \VanguardLTE\Support\Enum\UserStatus::lists();
        $roles = auth()->user()->available_roles();
        // $roles->prepend(trans('app.all'), '0');
        $users = \VanguardLTE\User::orderBy('created_at', 'DESC')->where(['role_id'=> 1, 'parent_id' => auth()->user()->id]);
        
        if( $request->search != '' ) 
        {
            $users = $users->where('username', 'like', '%' . $request->search . '%');
        }
        if( $request->status != '' ) 
        {
            $users = $users->where('status', $request->status);
        }
        if( $request->role ) 
        {
            $users = $users->where('role_id', $request->role);
        }
        $users = $users->paginate(20);
        $happyhour = \VanguardLTE\HappyHour::where([
            'shop_id' => auth()->user()->shop_id, 
            'time' => date('G')
        ])->first();
        return view('backend.agent.charge', compact('users', 'statuses', 'roles', 'happyhour'));
    }

    public function create(){
        $happyhour = \VanguardLTE\HappyHour::where([
            'shop_id' => auth()->user()->shop_id, 
            'time' => date('G')
        ])->first();
        $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<', \Illuminate\Support\Facades\Auth::user()->level())->pluck('name', 'id');
        $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
        $shops = auth()->user()->shops();
        $availibleUsers = [];
        if( auth()->user()->hasRole('admin') ) 
        {
            $availibleUsers = \VanguardLTE\User::get();
        }
        if( auth()->user()->hasRole('owner') ) 
        {
            $me = \VanguardLTE\User::where('id', \Illuminate\Support\Facades\Auth::id())->get();
            $distributors = \VanguardLTE\User::where([
                'parent_id' => auth()->user()->id, 
                'role_id' => 4
            ])->get();
            if( $shopsIds = \Illuminate\Support\Facades\Auth::user()->shops(true) ) 
            {
                $users = \VanguardLTE\ShopUser::whereIn('shop_id', $shopsIds)->pluck('user_id');
                if( $users ) 
                {
                    $availibleUsers = \VanguardLTE\User::whereIn('id', $users)->whereIn('role_id', [
                        2, 
                        3
                    ])->get();
                }
            }
            $me = $me->merge($distributors);
            $availibleUsers = $me->merge($availibleUsers);
        }
        if( auth()->user()->hasRole([
            'distributor', 
            'manager', 
            'agent'
        ]) ) 
        {
            $me = \VanguardLTE\User::where('id', \Illuminate\Support\Facades\Auth::id())->get();
            if( $shopsIds = \Illuminate\Support\Facades\Auth::user()->shops(true) ) 
            {
                $users = \VanguardLTE\ShopUser::whereIn('shop_id', $shopsIds)->pluck('user_id');
                if( $users ) 
                {
                    $availibleUsers = \VanguardLTE\User::whereIn('id', $users)->whereIn('role_id', [
                        2, 
                        3
                    ])->get();
                }
            }
            $availibleUsers = $me->merge($availibleUsers);
        }
        return view('backend.user.cash.cash-create', compact('roles', 'statuses', 'shops', 'availibleUsers', 'happyhour'));
    }

    public function store(\VanguardLTE\Http\Requests\User\CreateUserRequest $request)
    {
        return "ss";
        if( $request->role_id <= 3 && !$request->shop_id ) 
        {
            return redirect()->route('backend.user.list')->withErrors([trans('app.choose_shop')]);
        }
        $data = $request->all() + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE];
        if( trim($data['username']) == '' ) 
        {
            $data['username'] = null;
        }
        if( !$request->parent_id ) 
        {
            $data['parent_id'] = \Illuminate\Support\Facades\Auth::user()->id;
        }
        if( $request->balance && $request->balance > 0 ) 
        {
            $shop = \VanguardLTE\Shop::find(\Illuminate\Support\Facades\Auth::user()->shop_id);
            $sum = floatval($request->balance);
            if( $shop->balance < $sum ) 
            {
                return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                    'name' => $shop->name, 
                    'balance' => $shop->balance
                ])]);
            }
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }
        }

        if (isset($data['role_id']) && in_array($data['role_id'], auth()->user()->available_roles(false, true))) {
            $role_id = $data['role_id'];
        } else {
            $role_id = auth()->user()->role_id - 1;
        }

        $data['role_id'] = $role_id;
        $role = \jeremykenedy\LaravelRoles\Models\Role::find($role_id);

        $user = $this->users->create($data);
        $user->detachAllRoles();
        $user->attachRole($role);
        if( $request->shop_id && $request->shop_id > 0 && !empty($request->shop_id) ) 
        {
            \VanguardLTE\ShopUser::create([
                'shop_id' => $request->shop_id, 
                'user_id' => $user->id
            ]);
        }
        if( $request->balance && $request->balance > 0 ) 
        {
            $happyhour = \VanguardLTE\HappyHour::where([
                'shop_id' => auth()->user()->shop_id, 
                'time' => date('G')
            ])->first();
            $balance = $sum;
            if( $happyhour ) 
            {
                $transactionSum = $sum * intval(str_replace('x', '', $happyhour->multiplier));
                $bonus = $transactionSum - $sum;
                $wager = $bonus * intval(str_replace('x', '', $happyhour->wager));
                \VanguardLTE\Transaction::create([
                    'user_id' => $user->id, 
                    'system' => 'HH ' . $happyhour->multiplier, 
                    'summ' => $transactionSum, 
                    'shop_id' => ($user->hasRole('user') ? $user->shop_id : 0)
                ]);
                $user->increment('wager', $wager);
                $user->increment('bonus', $bonus);
                $user->increment('count_bonus', $bonus);
                $balance = $transactionSum;
            }
            else
            {
                \VanguardLTE\Transaction::create([
                    'user_id' => $user->id, 
                    'payeer_id' => \Illuminate\Support\Facades\Auth::id(), 
                    'summ' => $sum, 
                    'shop_id' => auth()->user()->shop_id
                ]);
            }
            $user->update([
                'balance' => $balance, 
                'count_balance' => $sum, 
                'total_in' => $sum, 
                'count_return' => \VanguardLTE\Lib\Functions::count_return($sum, $user->shop_id)
            ]);
            $shop->update(['balance' => $shop->balance - $sum]);
            $open_shift->increment('balance_out', abs($sum));
            $open_shift->increment('money_in', abs($sum));
        }
        if( !$user->shop_id && $user->hasRole([
            'agent', 
            'user'
        ]) ) 
        {
            $shops = $user->shops(true);
            if( count($shops) ) 
            {
                $shop_id = $shops->first();
                $user->update(['shop_id' => $shop_id]);
            }
        }
        return redirect()->route('backend.user.cash')->withSuccess(trans('app.user_created'));
    }

    public function massadd(\Illuminate\Http\Request $request)
    {
        $shop = \VanguardLTE\Shop::find(\Illuminate\Support\Facades\Auth::user()->shop_id);
        $count = \VanguardLTE\User::where([
            'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
            'role_id' => 1
        ])->count();
        if( isset($request->count) && is_numeric($request->count) && isset($request->balance) && is_numeric($request->balance) ) 
        {
            if( $request->balance > 0 ) 
            {
                if( $shop->balance < ($request->count * $request->balance) ) 
                {
                    return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                        'name' => $shop->name, 
                        'balance' => $shop->balance
                    ])]);
                }
                $open_shift = \VanguardLTE\OpenShift::where([
                    'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                    'end_date' => null
                ])->first();
                if( !$open_shift ) 
                {
                    return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
                }
            }
            if( \Illuminate\Support\Facades\Auth::user()->hasRole('agent') ) 
            {
                $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
                for( $i = 0; $i < $request->count; $i++ ) 
                {
                    $number = rand(111111111, 999999999);
                    $data = [
                        'username' => $number, 
                        'password' => $number, 
                        'role_id' => $role->id, 
                        'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                        'parent_id' => \Illuminate\Support\Facades\Auth::user()->id, 
                        'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id
                    ];
                    $newUser = $this->users->create($data);
                    $newUser->attachRole($role);
                    \VanguardLTE\ShopUser::create([
                        'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                        'user_id' => $newUser->id
                    ]);
                    if( $request->balance > 0 ) 
                    {
                        $happyhour = \VanguardLTE\HappyHour::where([
                            'shop_id' => auth()->user()->shop_id, 
                            'time' => date('G')
                        ])->first();
                        $balance = $sum = $request->balance;
                        if( $happyhour ) 
                        {
                            $transactionSum = $sum * intval(str_replace('x', '', $happyhour->multiplier));
                            $bonus = $transactionSum - $sum;
                            $wager = $bonus * intval(str_replace('x', '', $happyhour->wager));
                            \VanguardLTE\Transaction::create([
                                'user_id' => $newUser->id, 
                                'system' => 'HH ' . $happyhour->multiplier, 
                                'summ' => $transactionSum, 
                                'shop_id' => $newUser->shop_id
                            ]);
                            $newUser->increment('wager', $wager);
                            $newUser->increment('bonus', $bonus);
                            $newUser->increment('count_bonus', $bonus);
                            $balance = $transactionSum;
                        }
                        else
                        {
                            \VanguardLTE\Transaction::create([
                                'user_id' => $newUser->id, 
                                'payeer_id' => \Illuminate\Support\Facades\Auth::id(), 
                                'summ' => $request->balance, 
                                'shop_id' => $newUser->shop_id
                            ]);
                        }
                        $newUser->update([
                            'balance' => $balance, 
                            'count_balance' => $sum, 
                            'total_in' => $sum, 
                            'count_return' => \VanguardLTE\Lib\Functions::count_return($sum, $newUser->shop_id)
                        ]);
                        $shop->decrement('balance', $request->balance);
                        $open_shift->increment('balance_out', $request->balance);
                        $open_shift->increment('money_in', $request->balance);
                        $newUser->refresh();
                    }
                }
            }
        }
        return redirect()->route('backend.user.list')->withSuccess(trans('app.user_created'));
    }
    
}