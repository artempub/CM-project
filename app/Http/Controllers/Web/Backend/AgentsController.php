<?php

namespace VanguardLTE\Http\Controllers\Web\Backend {
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
    class AgentsController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $agents = null;
        private $max_agents = 1000000;

        public function __construct(\VanguardLTE\Repositories\User\UserRepository $agents)
        {
            $this->middleware([
                'auth',
                '2fa'
            ]);
            $this->middleware('permission:access.admin.panel');
            $this->agents = $agents;
        }

        public function index(\Illuminate\Http\Request $request)
        {
            $statuses = ['' => trans('app.all')] + \VanguardLTE\Support\Enum\UserStatus::lists();
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<', auth()->user()->level())->where('level', '>', 1)->pluck('name', 'id');
            $roles->prepend(trans('app.all'), '0');
            $agents = \VanguardLTE\User::where('users.ancestry', 'LIKE',  auth()->user()->ancestry . auth()->user()->id . '/%')->where('users.role_id', '>', 1)->orderBy('created_at', 'DESC');
            if ($request->search) {
                $request->search = str_replace('_', '\_', $request->search);
                $agents = $agents->where('users.username', 'like', '%' . $request->search . '%');
            }
            if ($request->role) {
                $agents = $agents->where('users.role_id', $request->role);
            }
            if ($request->status) {
                $agents = $agents->where('users.status', $request->status);
            }
            if ($request->parent) {
                $parentIds = \VanguardLTE\User::where('username', 'LIKE', $request->parent . '%')->where('role_id', '>', 1)->pluck('id');
                $agents = $agents->whereIn('users.parent_id', $parentIds);
            }
            if ($request->active) {
                if ($request->active == 1) {
                    $agents = $agents->whereHas('users.sessions');
                } else {
                    $agents = $agents->whereDoesntHave('users.sessions');
                }
            }
            if (count($agents->pluck('id'))) {
                $activeAgents = \VanguardLTE\User::whereIn('id', $agents->pluck('id'))->whereHas('sessions')->pluck('id');
            } else {
                $activeAgents = \VanguardLTE\User::where('id', 0)->whereHas('sessions')->pluck('id');
            }
            $agents = $agents->leftJoin('users AS P', 'P.id', 'users.parent_id')
                ->select('users.*', 'P.username as parent_username')
                ->paginate(20)->withQueryString();
            $happyhour = false;
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            if ($shop && $shop->happyhours_active) {
                $happyhour = \VanguardLTE\HappyHour::where([
                    'shop_id' => auth()->user()->shop_id,
                    'time' => date('G')
                ])->first();
            }
            return view('backend.agent.list', compact('agents', 'statuses', 'roles', 'happyhour', 'activeAgents'));
        }

        public function get_balance()
        {
            $agents = \VanguardLTE\User::where('users.ancestry', 'LIKE',  auth()->user()->ancestry . auth()->user()->id . '/%')->where('users.role_id', '>', 1)->orderBy('created_at', 'DESC');
            $data = [];
            foreach ($agents as $agent) {
                $data[$agent->id] = [
                    'balance' => number_format(floatval($agent->balance), 2, '.', ''),
                    'shop_limit' => $agent->shop_limit
                ];
            }
            return json_encode($data);
        }

        public function tree()
        {
            // $agents = \VanguardLTE\User::where('id', auth()->user()->id)->get();
            // if (auth()->user()->hasRole('admin')) {
            // $agents = \VanguardLTE\User::where('role_id', auth()->user()->role_id - 1)->get();
            // }

            if (auth()->user()->hasRole('admin')) {
                $_agents = \VanguardLTE\User::where('role_id', auth()->user()->role_id - 1)->get();
                $agents = $this->get_child_tree($_agents, auth()->user()->id, 0);
            } else if (auth()->user()->hasRole('distributor')) {
                $_agents = \VanguardLTE\User::where('role_id', auth()->user()->role_id)->get();
                $agents = [];
                foreach ($_agents as $_agent) {
                    if ($_agent->id == auth()->user()->id) {
                        array_push($agents, [
                            "agent" => $_agent,
                            "prev" => 0
                        ]);
                        break;
                    }
                }
                $agents = array_merge($agents, $this->get_child_tree($_agents, auth()->user()->id, 1));
            } else {
                $agents = [];
            }

            $role = \jeremykenedy\LaravelRoles\Models\Role::where('id', auth()->user()->role_id - 1)->first();
            return view('backend.agent.tree', compact('agents', 'role'));
        }

        private function get_child_tree($_agents, $parent_id, $prev)
        {
            $agents = [];
            foreach ($_agents as $_agent) {
                if ($_agent->parent_id == $parent_id) {
                    $agent = [
                        "agent" => $_agent,
                        "prev" => $prev
                    ];
                    array_push($agents, $agent);
                    $agents = array_merge($agents, $this->get_child_tree($_agents, $_agent->id, $prev + 1));
                }
            }
            return $agents;
        }

        public function view(\VanguardLTE\User $agent, \VanguardLTE\Repositories\Activity\ActivityRepository $activities)
        {
            $userActivities = $activities->getLatestActivitiesForUser($agent->id, 10);
            if (auth()->user()->role_id < $agent->role_id) {
                return redirect()->route('backend.agent.list');
            }
            return view('backend.agent.view', compact('agent', 'userActivities'));
        }

        public function create()
        {
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            $happyhour = false;
            if ($shop && $shop->happyhours_active) {
                $happyhour = \VanguardLTE\HappyHour::where([
                    'shop_id' => auth()->user()->shop_id,
                    'time' => date('G')
                ])->first();
            }
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<=', auth()->user()->level())->where('level', '>', 1)->pluck('name', 'id');
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            $shops = auth()->user()->shops_array();
            $availibleUsers = [];
            if (auth()->user()->hasRole('admin')) {
                $availibleUsers = \VanguardLTE\User::get();
            }
            if (auth()->user()->hasRole([
                'distributor',
                'agent'
            ])) {
                $me = \VanguardLTE\User::where('id', auth()->user()->id)->get();
                if ($shopsIds = auth()->user()->shops(true)) {
                    $agents = \VanguardLTE\ShopUser::whereIn('shop_id', $shopsIds)->pluck('user_id');
                    if ($agents) {
                        $availibleUsers = \VanguardLTE\User::whereIn('id', $agents)->whereIn('role_id', [
                            2
                        ])->get();
                    }
                }
                $availibleUsers = $me->merge($availibleUsers);
            }
            return view('backend.agent.add', compact('roles', 'statuses', 'shops', 'availibleUsers', 'happyhour'));
        }

        public function store(\VanguardLTE\Http\Requests\User\CreateUserRequest $request)
        {
            if ($request->role_id < 3 && !$request->shop_id) {
                return redirect()->route('backend.agent.list')->withErrors([trans('app.choose_shop')]);
            }
            $data = $request->only([
                'email',
                'username',
                'language',
                'status',
                'shop_id',
                'role_id',
                'parent_id',
                'is_blocked',
                'password',
                'password_confirmation'
            ]) + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE];
            if (isset($data['email']) && ($return = \VanguardLTE\Lib\Filter::domain_filtered($data['email']))) {
                return redirect()->back()->withErrors([__('app.blocked_domain_zone', ['zone' => $return['domain']])]);
            }
            if (trim($data['username']) == '') {
                $data['username'] = null;
            }
            $count = \VanguardLTE\User::where('shop_id', auth()->user()->shop_id)->where('role_id', '>', 1)->count();
            if ($this->max_agents <= $count) {
                return redirect()->route('backend.agent.list')->withErrors([trans('app.max_agents', ['max' => $this->max_agents])]);
            }
            if (!$request->parent_id) {
                $data['parent_id'] = auth()->user()->id;
            }
            $parent = \VanguardLTE\User::find($data['parent_id']);
            if (!$parent) {
                return redirect()->back()->withErrors([trans('app.wrong_parent')]);
            }
            $data['ancestry'] = $parent->ancestry . $parent->id . '/';
            if ($request->balance && $request->balance > 0) {
                if (!$parent->hasRole('admin')) {
                    $sum = floatval($request->balance);
                    if ($parent->balance < $sum) {
                        return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_agent_balance', [
                            'name' => $parent->username,
                            'balance' => $parent->balance
                        ])]);
                    }
                    /*if ($parent->hasRole('agent')) {
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'shop_id' => auth()->user()->shop_id, 
                            'user_id' => auth()->user()->id, 
                            'end_date' => null
                        ])->first();
                        if( !$open_shift ) 
                        {
                            return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
                        }
                    }*/
                }
            }
            if ($request->fight && $request->fight > 0) {
                $fight = min(100, floatval($request->fight));
                if (!$parent->hasRole('admin')) {
                    $available_fight = $parent->fight;
                    if ($available_fight < $fight) {
                        return redirect()->back()->withErrors([trans('app.not_enough_in_the_agent_fight', [
                            'name' => $parent->username,
                            'fight' => $available_fight
                        ])]);
                    }
                }
                $data['fight'] = $fight;
            }
            if (auth()->user()->hasRole('distributor')) {
                $role_id = isset($data['role_id']) ? $data['role_id'] : auth()->user()->role_id;
            } else {
                $role_id = (isset($data['role_id']) && $data['role_id'] < auth()->user()->role_id ? $data['role_id'] : auth()->user()->role_id - 1);
            }
            $data['role_id'] = $role_id;

            $role = \jeremykenedy\LaravelRoles\Models\Role::find($role_id);
            $user = $this->agents->create($data + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE]);
            $user->detachAllRoles();
            $user->attachRole($role);
            if ($request->shop_id && $request->shop_id > 0 && !empty($request->shop_id)) {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $request->shop_id,
                    'user_id' => $user->id
                ]);
            }
            if ($request->balance && $request->balance > 0) {
                $user->addBalance('add', $request->balance);
                if (auth()->user()->hasRole('agent')) {
                    auth()->user()->hierarchyUsers(false, true);
                }
            }
            if (!$user->shop_id && $user->hasRole('agent')) {
                $shops = $user->shops(true);
                if (count($shops)) {
                    $shop_id = $shops->first();
                    $user->update(['shop_id' => $shop_id]);
                }
            }
            return redirect()->route('backend.agent.list')->withSuccess(trans('app.agent_created'));
        }

        public function edit(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Activity\ActivityRepository $activitiesRepo, \VanguardLTE\User $agent)
        {
            $edit = true;
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<=', auth()->user()->level())->where('level', '>', 1)->pluck('name', 'id');
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            $shops = $agent->shops();
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            $userActivities = \VanguardLTE\Services\Logging\UserActivity\Activity::where([
                'user_id' => $agent->id,
                'type' => 'user'
            ])->orderBy('created_at', 'DESC')->paginate(30)->withQueryString();
            $users = auth()->user()->availableUsers();
            if (count($users) && !in_array($agent->id, $users)) {
                abort(404);
            }
            if (auth()->user()->role_id < $agent->role_id) {
                return redirect()->route('backend.agent.list');
            }
            $hasActivities = $this->hasActivities($agent);
            $langs = [];
            foreach (glob(resource_path() . '/lang/*', GLOB_ONLYDIR) as $fileinfo) {
                $dirname = basename($fileinfo);
                $langs[$dirname] = $dirname;
            }
            if ($agent->sms_token != '') {
                $now = \Carbon\Carbon::now();
                $times = $now->diffInSeconds(\Carbon\Carbon::parse($agent->sms_token_date), false);
                if ($times <= 0) {
                    $agent->update([
                        'phone' => '',
                        'phone_verified' => 0,
                        'sms_token' => ''
                    ]);
                }
            }
            $google2fa = app('pragmarx.google2fa');
            $QR_Image = '';
            $secret = $agent->google2fa_secret;
            if ($agent->google2fa_enable) {
                $secret = $google2fa->generateSecretKey();
                $QR_Image = $google2fa->getQRCodeInline(config('app.name'), $agent->email, $secret);
            }
            $happyhour = false;
            if ($shop && $shop->happyhours_active) {
                $happyhour = \VanguardLTE\HappyHour::where([
                    'shop_id' => auth()->user()->shop_id,
                    'time' => date('G')
                ])->first();
            }
            return view('backend.agent.edit', compact('edit', 'agent', 'roles', 'statuses', 'shops', 'userActivities', 'hasActivities', 'langs', 'QR_Image', 'secret', 'happyhour'));
        }

        public function send_phone_code()
        {
            $code = rand(11111, 99999);
            $sender = \VanguardLTE\Lib\SMS_sender::send(auth()->user()->phone, 'Verification code: ' . $code, auth()->user()->id);
            if (isset($sender['error'])) {
                if (isset($sender['text'])) {
                    return redirect()->back()->withErrors($sender['text']);
                }
                return redirect()->back()->withErrors('Error sending message');
            }
            if (!isset($sender['success'])) {
                return redirect()->back()->withErrors(__('app.something_went_wrong'));
            }
            if (!$sender['success']) {
                return redirect()->back()->withErrors($sender['message']);
            }
            \VanguardLTE\SMS::create([
                'user_id' => auth()->user()->id,
                'message' => $code,
                'message_id' => $sender['message_id'],
                'shop_id' => auth()->user()->shop_id,
                'type' => 'verification',
                'status' => 'Sent'
            ]);
            auth()->user()->update([
                'sms_token' => $code,
                'sms_token_date' => \Carbon\Carbon::now()->addMinutes(settings('smsto_time'))
            ]);
            return redirect()->back()->withSuccess('Code sent');
        }

        public function updateDetails(\VanguardLTE\User $agent, \VanguardLTE\Http\Requests\Agent\UpdateDetailsRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $users = auth()->user()->availableUsers();
            $google2fa = app('pragmarx.google2fa');
            if (count($users) && !in_array($agent->id, $users)) {
                abort(404);
            }
            if (auth()->user()->role_id < $agent->role_id) {
                return redirect()->route('backend.agent.list');
            }
            $data = $request->only([
                'email',
                'username',
                'language',
                'shop_id',
                'status',
                'is_blocked',
                'password',
                'password_confirmation',
                'google2fa_enable'
            ]);
            if (isset($request->secret_key) && isset($request->google_2fa_code) && $request->google_2fa_code != '') {
                $code = $request->google_2fa_code;
                $key = $agent->google2fa_secret;
                if ($agent->google2fa_secret == null) {
                    $key = $request->secret_key;
                }
                $verify = $google2fa->verifyGoogle2FA($key, $code);
                if ($verify) {
                    if ($agent->google2fa_enable) {
                        $agent->update(['google2fa_secret' => $key]);
                    } else {
                        $agent->update([
                            'google2fa_secret' => null,
                            'google2fa_enable' => 0
                        ]);
                    }
                    $google2fa->logout();
                } else {
                    return redirect()->route('backend.agent.edit', $agent->id)->withInput(['google_tab' => true])->withErrors(['Code is wrong']);
                }
            }
            $validator = \Illuminate\Support\Facades\Validator::make($data, [
                'username' => 'required|unique:users,username,' . $agent->id,
                'email' => 'nullable|unique:users,email,' . $agent->id,
                'phone' => 'nullable|unique:users,phone,' . $agent->id
            ]);
            if ($validator->fails()) {
                return redirect()->route('backend.agent.edit', $agent->id)->withErrors($validator)->withInput();
            }
            if (empty($data['password']) || empty($data['password_confirmation'])) {
                unset($data['password']);
                unset($data['password_confirmation']);
            }
            if (!(auth()->user()->hasRole('admin') && $agent->hasRole([
                'distributor',
                'agent',
            ]))) {
                unset($data['is_blocked']);
            } else if (isset($data['is_blocked'])) {
                $users = \VanguardLTE\User::whereIn('id', [$agent->id] + $agent->hierarchyUsers())->get();
                if ($users) {
                    foreach ($users as $userElem) {
                        \DB::table('sessions')->where('user_id', $userElem->id)->delete();
                        $userElem->update([
                            'remember_token' => null,
                            'is_blocked' => $data['is_blocked']
                        ]);
                    }
                }
                $myShops = \VanguardLTE\Shop::whereIn('id', $agent->availableShops())->get();
                if ($myShops) {
                    foreach ($myShops as $myShop) {
                        $myShop->update(['is_blocked' => $data['is_blocked']]);
                    }
                }
            }
            if ($request->status != $agent->status) {
                if ($request->status == \VanguardLTE\Support\Enum\UserStatus::ACTIVE && $agent->status == \VanguardLTE\Support\Enum\UserStatus::BANNED) {
                    event(new \VanguardLTE\Events\User\UserUnBanned($agent));
                }
                if ($request->status == \VanguardLTE\Support\Enum\UserStatus::ACTIVE && $agent->status == \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED) {
                    event(new \VanguardLTE\Events\User\UserConfirmed($agent));
                }
                if ($request->status == \VanguardLTE\Support\Enum\UserStatus::BANNED) {
                    event(new \VanguardLTE\Events\User\Banned($agent));
                }
            }
            if (isset($data['email']) && !$agent->hasRole('admin') && ($return = \VanguardLTE\Lib\Filter::domain_filtered($data['email']))) {
                return redirect()->route('backend.agent.edit', $agent->id)->withErrors([__('app.blocked_domain_zone', ['zone' => $return['domain']])]);
            }
            if (isset($request->phone) && $request->phone) {
                $phone = preg_replace('/[^0-9]/', '', $request->phone);
                $code = null;
                if ($phone != '' && !$agent->phone) {
                    $code = rand(1111, 9999);
                    $data['phone'] = $phone;
                }
                if ($agent->phone && $agent->phone != $phone && !$agent->phone_verified) {
                    $code = rand(1111, 9999);
                    $data['phone'] = $phone;
                }
                if ($agent->phone_verified && auth()->user()->hasRole('admin') && $agent->phone != $phone) {
                    $code = rand(1111, 9999);
                    $data['phone'] = $phone;
                    $data['phone_verified'] = 0;
                }
                if ($code) {
                    $sender = \VanguardLTE\Lib\SMS_sender::send($phone, 'Verification code: ' . $code, $agent->id);
                    $this->agents->update($agent->id, [
                        'sms_token' => $code,
                        'sms_token_date' => \Carbon\Carbon::now()->addMinutes(settings('smsto_time'))
                    ]);
                    if (isset($sender['message_id'])) {
                        \VanguardLTE\SMS::create([
                            'user_id' => $agent->id,
                            'message' => $code,
                            'message_id' => $sender['message_id'],
                            'shop_id' => $agent->shop_id,
                            'type' => 'verification',
                            'status' => 'Sent'
                        ]);
                    }
                }
            } else {
                $data['phone'] = '';
                $data['phone_verified'] = 0;
                $data['sms_token'] = null;
            }
            if (auth()->user()->id != $agent->id && $request->fight && $request->fight > 0) {
                $parent = \VanguardLTE\User::find($agent->parent_id);
                $fight = min(100, floatval($request->fight));
                if (!$parent->hasRole('admin')) {
                    $available_fight = $parent->fight;
                    if ($available_fight < $fight) {
                        return redirect()->back()->withErrors([trans('app.not_enough_in_the_agent_fight', [
                            'name' => $parent->username,
                            'fight' => $available_fight
                        ])]);
                    }
                }
                $data['fight'] = $fight;
            }
            $this->agents->update($agent->id, $data);
            if ($agent->hasRole([
                'distributor',
                'agent'
            ]) && $request->shops && count($request->shops)) {
                foreach ($request->shops as $shop) {
                    \VanguardLTE\ShopUser::create([
                        'shop_id' => $shop,
                        'user_id' => $agent->id
                    ]);
                }
            }
            if ($request->sms_token) {
                if ($request->sms_token == $agent->sms_token) {
                    $now = \Carbon\Carbon::now();
                    $seconds = $now->diffInSeconds(\Carbon\Carbon::parse($agent->sms_token_date), false);
                    if ($seconds <= 0) {
                        return redirect()->route('backend.agent.edit', $agent->id)->withErrors(trans('app.time_is_up'));
                    }
                    $agent->update([
                        'sms_token' => null,
                        'phone_verified' => 1
                    ]);
                    return redirect()->route('backend.agent.edit', $agent->id)->withSuccess(trans('app.phone_verified'));
                } else {
                    return redirect()->route('backend.agent.edit', $agent->id)->withErrors(trans('app.phone_verification_code_is_wrong'));
                }
            }
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($agent));
            if ($this->userIsBanned($agent, $request)) {
                event(new \VanguardLTE\Events\User\Banned($agent));
            }
            return redirect()->route('backend.agent.edit', $agent->id)->withSuccess(trans('app.agent_updated'));
        }

        public function updateBalance(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if (!array_get($data, 'type')) {
                $data['type'] = 'add';
            }
            if (auth()->user()->hasRole('admin') && auth()->user()->google2fa_secret != null && auth()->user()->google2fa_enable) {
                if (!$request->google_2fa_code) {
                    return redirect()->back()->withErrors([__('app.wrong_code') . ' ' . __('app.google_2fa')]);
                }
                $google2fa = app('pragmarx.google2fa');
                $verify = $google2fa->verifyGoogle2FA(auth()->user()->google2fa_secret, $request->google_2fa_code);
                if (!$verify) {
                    return redirect()->back()->withErrors([__('app.wrong_code') . ' ' . __('app.google_2fa')]);
                }
            }
            $user = \VanguardLTE\User::find($request->agent_id);
            if (!$user) {
                return redirect()->back()->withErrors([__('app.wrong_user')]);
            }
            $request->summ = floatval($request->summ);
            if ($request->all && $request->all == '1') {
                $request->summ = $user->balance;
            }
            $result = $user->addBalance($data['type'], $request->summ);
            $result = json_decode($result, true);
            if ($data['type'] == 'add') {
                event(new \VanguardLTE\Events\User\MoneyIn($user, $request->summ));
            } else {
                event(new \VanguardLTE\Events\User\MoneyOut($user, $request->summ));
            }
            if ($result['status'] == 'error') {
                return redirect()->back()->withErrors([$result['message']]);
            }
            return redirect()->back()->withSuccess($result['message']);
        }

        public function updateLimit(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if (!array_get($data, 'type')) {
                $data['type'] = 'add';
            }
            if (auth()->user()->hasRole('admin') && auth()->user()->google2fa_secret != null && auth()->user()->google2fa_enable) {
                dd('i am an admin with 2fa');
                if (!$request->google_2fa_code) {
                    return redirect()->back()->withErrors([__('app.wrong_code') . ' ' . __('app.google_2fa')]);
                }
                $google2fa = app('pragmarx.google2fa');
                $verify = $google2fa->verifyGoogle2FA(auth()->user()->google2fa_secret, $request->google_2fa_code);
                if (!$verify) {
                    return redirect()->back()->withErrors([__('app.wrong_code') . ' ' . __('app.google_2fa')]);
                }
            }
            $user = \VanguardLTE\User::find($request->user_id);

            if (!$user) {
                return redirect()->back()->withErrors([__('app.wrong_user')]);
            }
            $request->summ = floatval($request->summ);
            if ($request->all && $request->all == '1') {
                $request->summ = $user->balance;
            }
            $result = $user->addLimit($data['type'], $request->summ);
            $result = json_decode($result, true);
            if ($result['status'] == 'error') {
                return redirect()->back()->withErrors([$result['message']]);
            }
            return redirect()->back()->withSuccess($result['message']);
        }

        public function statistics(\VanguardLTE\User $agent, \Illuminate\Http\Request $request)
        {
            $user = $agent;
            $statistics = \VanguardLTE\Statistic::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();
            return view('backend.stat.pay_stat', compact('user', 'statistics'));
        }
        private function userIsBanned(\VanguardLTE\User $agent, \Illuminate\Http\Request $request)
        {
            return $agent->status != $request->status && $request->status == \VanguardLTE\Support\Enum\UserStatus::BANNED;
        }
        public function specauth(\Illuminate\Http\Request $request, \VanguardLTE\User $agent)
        {
            if (!$agent) {
                return redirect()->route('backend.auth.login')->withErrors([trans('app.wrong_agent')]);
            }
            if ($agent->auth_token == $request->token && auth()->user()->hasRole('admin') && !$agent->hasRole('admin')) {
                if (auth()->user()->shop && auth()->user()->shop->pending) {
                    return redirect()->route('backend.dashboard')->withErrors(__('app.shop_is_creating'));
                }
                session(['beforeUser' => auth()->user()->id]);
                \Illuminate\Support\Facades\Auth::loginUsingId($agent->id);
                if (!$agent->hasRole('user')) {
                    if (!auth()->user()->hasPermission('dashboard')) {
                        return redirect()->route('backend.agent.list');
                    }
                    return redirect()->route('backend.dashboard');
                }
                return redirect()->intended();
            }
            return redirect()->route('backend.auth.login')->withErrors([trans('app.wrong_agent')]);
        }
        public function back_login(\Illuminate\Http\Request $request)
        {
            if ($request->session()->exists('beforeUser')) {
                \Illuminate\Support\Facades\Auth::loginUsingId(session('beforeUser'));
                $request->session()->forget('beforeUser');
                return redirect()->route('backend.dashboard');
            }
            return redirect()->route('backend.dashboard')->withErrors([trans('app.wrong_user')]);
        }
        public function updateAvatar(\VanguardLTE\User $agent, \VanguardLTE\Services\Upload\UserAvatarManager $avatarManager, \Illuminate\Http\Request $request)
        {
            $this->validate($request, ['avatar' => 'image']);
            $name = $avatarManager->uploadAndCropAvatar($agent, $request->file('avatar'), $request->get('points'));
            if ($name) {
                $this->agents->update($agent->id, ['avatar' => $name]);
                event(new \VanguardLTE\Events\User\UpdatedByAdmin($agent));
                return redirect()->route('backend.agent.edit', $agent->id)->withSuccess(trans('app.avatar_changed'));
            }
            return redirect()->route('backend.agent.edit', $agent->id)->withErrors(trans('app.avatar_not_changed'));
        }
        public function updateAvatarExternal(\VanguardLTE\User $agent, \Illuminate\Http\Request $request, \VanguardLTE\Services\Upload\UserAvatarManager $avatarManager)
        {
            $avatarManager->deleteAvatarIfUploaded($agent);
            $this->agents->update($agent->id, ['avatar' => $request->get('url')]);
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($agent));
            return redirect()->route('backend.agent.edit', $agent->id)->withSuccess(trans('app.avatar_changed'));
        }
        public function updateLoginDetails(\VanguardLTE\User $agent, \VanguardLTE\Http\Requests\User\UpdateLoginDetailsRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $data = $request->all();
            if (trim($data['password']) == '') {
                unset($data['password']);
                unset($data['password_confirmation']);
            }
            if (!(auth()->user()->hasRole('admin') && $agent->hasRole([
                'distributor',
                'agent'
            ]))) {
                unset($data['is_blocked']);
            } else {
                $users = \VanguardLTE\User::whereIn('id', [$agent->id] + $agent->hierarchyUsers())->get();
                if ($users) {
                    foreach ($users as $userElem) {
                        \DB::table('sessions')->where('user_id', $userElem->id)->delete();
                        $userElem->update([
                            'remember_token' => null,
                            'is_blocked' => 1
                        ]);
                    }
                }
            }
            $this->agents->update($agent->id, $data);
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($agent));
            return redirect()->route('backend.agent.edit', $agent->id)->withSuccess(trans('app.login_updated'));
        }

        public function delete(\VanguardLTE\User $agent)
        {
            if (!(auth()->user()->hasRole('admin') && $agent->hasRole(['distributor', 'agent'])
                || auth()->user()->hasRole('distributor') && $agent->hasRole('agent'))) {
                abort(403);
            }
            if (auth()->user()->id == $agent->id) {
                return redirect()->route('backend.agent.list')->withErrors(trans('app.you_cannot_delete_yourself'));
            }
            if ($agent->balance > 0) {
                return redirect()->route('backend.agent.list')->withErrors([trans('app.balance_not_zero')]);
            }
            if ($count = \VanguardLTE\User::where('parent_id', $agent->id)->count()) {
                return redirect()->route('backend.agent.list')->withErrors([trans('app.has_users', ['name' => $agent->username])]);
            }
            if ($this->hasActivities($agent)) {
                return redirect()->route('backend.agent.list')->withErrors([trans('app.has_stats', ['name' => $agent->username])]);
            }
            $agent->detachAllRoles();
            \VanguardLTE\Statistic::where('user_id', $agent->id)->delete();
            \VanguardLTE\StatisticAdd::where('user_id', $agent->id)->delete();
            \VanguardLTE\ShopUser::where('user_id', $agent->id)->delete();
            \VanguardLTE\StatGame::where('user_id', $agent->id)->delete();
            \VanguardLTE\GameLog::where('user_id', $agent->id)->delete();
            \VanguardLTE\UserActivity::where('user_id', $agent->id)->delete();
            \VanguardLTE\Session::where('user_id', $agent->id)->delete();
            \VanguardLTE\Info::where('user_id', $agent->id)->delete();
            $agent->delete();
            return redirect()->route('backend.agent.list')->withSuccess(trans('app.user_deleted'));
        }

        public function hard_delete(\VanguardLTE\User $agent)
        {
            if (!(auth()->user()->hasRole('admin') && $agent->hasRole(['distributor', 'agent'])
                || auth()->user()->hasRole('distributor') && $agent->hasRole('agent'))) {
                abort(403);
            }
            if ($agent->id == auth()->user()->id) {
                return redirect()->route('backend.agent.list')->withErrors(trans('app.you_cannot_delete_yourself'));
            }
            if ($agent->hasRole('distributor')) {
                if ($agent->rel_shops) {
                    foreach ($agent->rel_shops as $shop) {
                        $shop->shop->delete();
                        \VanguardLTE\Task::create([
                            'category' => 'shop',
                            'action' => 'delete',
                            'item_id' => $shop->shop_id,
                            'user_id' => auth()->user()->id,
                            'shop_id' => auth()->user()->shop_id
                        ]);
                        $usersToDelete = \VanguardLTE\User::whereIn('role_id', [
                            1,
                            2
                        ])->where('shop_id', $shop->shop_id)->get();
                        if ($usersToDelete) {
                            foreach ($usersToDelete as $userDelete) {
                                $userDelete->delete();
                            }
                        }
                    }
                }
                $agent->delete();
            }
            if ($agent->hasRole('agent')) {
                $usersToDelete = \VanguardLTE\User::whereIn('role_id', [
                    1,
                    2
                ])->where('ancestry', 'LIKE',  $agent->ancestry . $agent->id . '/%')->get();
                if ($usersToDelete) {
                    foreach ($usersToDelete as $userDelete) {
                        $userDelete->delete();
                    }
                }
                $agent->delete();
            }
            if (auth()->user()->hasRole('admin')) {
                $admin = \VanguardLTE\User::find(auth()->user()->id);
                $admin->update(['shop_id' => 0]);
                \VanguardLTE\Jobs\UpdateTreeCache::dispatch($admin->hierarchyUsers());
            }
            return redirect()->route('backend.agent.list')->withSuccess(trans('app.user_deleted'));
        }

        public function hasActivities($user)
        {
            if ($user->hasRole([
                'distributor',
                'agent'
            ])) {
                $stats = \VanguardLTE\Statistic::where('user_id', $user->id)->count();
                if ($stats) {
                    return true;
                }
                $stats = \VanguardLTE\StatGame::where('user_id', $user->id)->count();
                if ($stats) {
                    return true;
                }
                /*$open_shifts = \VanguardLTE\OpenShift::where('user_id', $user->id)->count();
                if( $open_shifts ) 
                {
                    return true;
                }*/
            }
            return false;
        }
        public function sessions(\VanguardLTE\User $agent, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $user = $agent;
            $adminView = true;
            $sessions = $sessionRepository->getUserSessions($user->id);
            return view('backend.agent.sessions', compact('sessions', 'user', 'adminView'));
        }
        public function invalidateSession(\VanguardLTE\User $agent, $session, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $sessionRepository->invalidateSession($session->id);
            return redirect()->route('backend.agent.sessions', $agent->id)->withSuccess(trans('app.session_invalidated'));
        }
        public function action($action)
        {
            if (!auth()->user()->hasRole('agent')) {
                abort(403);
            }
            /*$open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => auth()->user()->shop_id, 
                'user_id' => auth()->user()->id, 
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }*/
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            if ($action && in_array($action, ['users_out'])) {
                switch ($action) {
                    case 'users_out':
                        $users = \VanguardLTE\User::where('shop_id', $shop->id)->get();
                        foreach ($users as $user) {
                            $sum = $user->balance;
                            if ($sum <= 0) {
                                continue;
                            }
                            $user->addBalance('out', $sum, $user->referral);
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                }
            }
        }
    }
}
