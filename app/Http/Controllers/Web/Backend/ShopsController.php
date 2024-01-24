<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use DataTables;
use Session;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Http\Traits\RecursiveTrait;

{
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
    class ShopsController extends \VanguardLTE\Http\Controllers\Controller
    {
        // use RecursiveTrait;

        private $users = null;
        private $max_shops = 200;
        private $child_users_array = array();
        private $parent_users_array = array();
        private $users_count = 0;
        private $shops_count = 0;
        private $operators_count = 0;
        private $user_roleId = 0;
        private $shop_roleId = 0;

        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware([
                'auth',
                '2fa'
            ]);
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:users.manage');
            $this->users = $users;
        }

        public function index(\Illuminate\Http\Request $request)
        {
            $shops = \VanguardLTE\Shop::select('shops.*', 'shops.id AS shop_id');
            if ($shopIds = auth()->user()->shops(true)) {
                $shops = $shops->whereIn('shops.id', $shopIds);
            } else {
                $shops = $shops->where('shops.id', 0);
            }
            if ($request->name != '') {
                $shops = $shops->where('shops.name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->credit_from != '') {
                $shops = $shops->where('shops.balance', '>=', $request->credit_from);
            }
            if ($request->credit_to != '') {
                $shops = $shops->where('shops.balance', '<=', $request->credit_to);
            }
            if ($request->frontend != '') {
                $shops = $shops->where('shops.frontend', $request->frontend);
            }
            if ($request->percent_from != '') {
                $shops = $shops->where('shops.percent', '>=', $request->percent_from);
            }
            if ($request->percent_to != '') {
                $shops = $shops->where('shops.percent', '<=', $request->percent_to);
            }
            if ($request->order != '') {
                $shops = $shops->where('shops.orderby', $request->order);
            }
            if ($request->currency != '') {
                $shops = $shops->where('shops.currency', $request->currency);
            }
            if ($request->status != '') {
                $shops = $shops->where('shops.is_blocked', !$request->status);
            }
            if ($request->categories) {
                $shops = $shops->join('shop_categories', 'shop_categories.shop_id', '=', 'shops.id');
                $shops = $shops->whereIn('shop_categories.category_id', $request->categories);
            }
            if ($request->users != '') {
                $request->users = str_replace('_', '\_', $request->users);
                $shops = $shops->join('shops_user', 'shops_user.shop_id', '=', 'shops.id');
                $tempUsers = \VanguardLTE\User::whereIn('id', auth()->user()->availableUsers())->where('username', 'LIKE', '%' . $request->users . '%')->get();
                if ($tempUsers) {
                    $shops = $shops->whereIn('shops_user.user_id', $tempUsers->pluck('id'));
                } else {
                    $shops = $shops->where('shops_user.user_id', 0);
                }
            }
            $shops = $shops->groupBy('shops.id')->paginate(15)->withQueryString();
            $categories = \VanguardLTE\Category::where(['parent' => 0])->get();
            $directories = [];
            foreach (glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo) {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $stats = [
                'shops' => $shops->count(),
                'agents' => 1,
                'distributors' => 0,
                'managers' => 0,
                'cashiers' => 0,
                'users' => 0,
                'credit' => $shops->sum('balance')
            ];
            $countAgents = [];
            $countDistributors = [];
            if ($shops) {
                foreach ($shops as $shop) {
                    if ($shop->users) {
                        foreach ($shop->users as $user) {
                            if ($user = $user->user) {
                                if ($user->hasRole('agent')) {
                                    $countAgents[$user->username] = 1;
                                }
                                if ($user->hasRole('distributor')) {
                                    $countDistributors[$user->username] = 1;
                                }
                                if ($user->hasRole('manager')) {
                                    $stats['managers']++;
                                }
                                if ($user->hasRole('cashier')) {
                                    $stats['cashiers']++;
                                }
                                if ($user->hasRole('user')) {
                                    $stats['users']++;
                                }
                            }
                        }
                    }
                }
            }
            if (auth()->user()->hasRole('admin')) {
                $stats['agents'] = \VanguardLTE\User::where('role_id', 5)->count();
                $stats['distributors'] = \VanguardLTE\User::where('role_id', 4)->count();
            }
            if (auth()->user()->hasRole('agent')) {
                $stats['distributors'] = \VanguardLTE\User::where([
                    'role_id' => 4,
                    'parent_id' => auth()->user()->id
                ])->count();
            }
            if (auth()->user()->hasRole('distributor')) {
                $stats['distributors'] = 1;
            }
            if (auth()->user()->hasRole('manager')) {
                $stats['distributors'] = 1;
            }
            if (count($request->all())) {
                $stats['agents'] = count($countAgents);
                $stats['distributors'] = count($countDistributors);
            }
            $agents = \VanguardLTE\User::where('role_id', 5)->pluck('username', 'id')->toArray();
            $distributors = [];
            if (auth()->user()->hasRole(['admin'])) {
                $distributors = \VanguardLTE\User::where('role_id', 4)->pluck('username', 'id')->toArray();
            } else if (auth()->user()->hasRole(['agent'])) {
                $distributors = \VanguardLTE\User::where([
                    'role_id' => 4,
                    'parent_id' => auth()->user()->id
                ])->pluck('username', 'id')->toArray();
            }
            return view('backend.shops.list', compact('shops', 'categories', 'stats', 'agents', 'distributors', 'directories'));
        }

        public function get_hierarchy_childs_ids($child_users, $role_id = 0)
        {
            foreach ($child_users as $key => $child_user) {
                if ($role_id == 0) {
                    array_push($this->child_users_array, $child_user->id);
                } else {
                    if ($child_user->role_id == $role_id) {
                        array_push($this->child_users_array, $child_user->id);
                    }
                }

                if ($child_user->role_id == $this->user_roleId) {
                    $this->users_count++;
                } elseif ($child_user->role_id == $this->shop_roleId) {
                    $this->shops_count++;
                } else {
                    $this->operators_count++;
                }
                if (count($child_user->childs)) {
                    $this->get_hierarchy_childs_ids($child_user->childs, $role_id);
                }
            }
        }
        public function get_upper_parents_names($parent_user)
        {
            array_push($this->parent_users_array, $parent_user->username);
            if ($parent_user->parent) {
                $this->get_upper_parents_names($parent_user->parent);
            }
            return $this->parent_users_array;
        }
        public function get_upper_parents_ids($parent_user)
        {
            array_push($this->parent_users_array, $parent_user->id);
            if ($parent_user->parent) {
                $this->get_upper_parents_ids($parent_user->parent);
            }
            return $this->parent_users_array;
        }
        //use for new hierarchy

        public function index_new(\Illuminate\Http\Request $request)
        {

            $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'shop')->first()->id;
            $operator_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
            
            $child_users = \VanguardLTE\User::find(isset($request->id)? $request->id : auth()->user()->id)->childs;
            //get all operator username and id
            $all_operator_info = \VanguardLTE\User::where('role_id', $operator_role_id)->get();

            if ($request->ajax()) {

                $this->get_hierarchy_childs_ids($child_users, $user_role_id);
                if (isset($request->sel_action)) {
                    # get the row id and update corresponding Enabled/Panic column
                    $sel_action = 'success';
                    $action_type = 'panic';
                    switch ($request->sel_action) {
                        case 'enable_panic':
                            $sel_action = 'success';
                            $action_type = 'panic';
                            break;
                        case 'disable_panic':
                            $sel_action = 'danger';
                            $action_type = 'panic';
                            break;
                        case 'enable_user':
                            $sel_action = 'success';
                            $action_type = 'enabled';
                            break;
                        case 'disable_user':
                            $sel_action = 'danger';
                            $action_type = 'enabled';
                            break;
                        case 'change_parent_operator':
                            $action_type = 'parent_id';
                            break;

                        default:
                            break;
                    }

                    if ($action_type == 'parent_id' && isset($request->sel_new_parent_operator)) { //15/02/22 for 'Change Family by Amdin' logic
                        # change the parent_ids over all selected Shops
                        foreach ($request->sel_rows as  $sel_row_id) {
                            \VanguardLTE\User::where('id', $sel_row_id)->update([$action_type => $request->sel_new_parent_operator]);
                        }
                    } else {
                        foreach ($request->sel_rows as  $sel_row_id) {
                            \VanguardLTE\User::where('id', $sel_row_id)->update([$action_type => $sel_action]);
                        }
                    }
                }

                $users = \VanguardLTE\User::select('id', 'username', 'email', 'balance', 'last_online', 'ip_address', 'enabled', 'panic')->whereIn('id', $this->child_users_array)->get();
                return Datatables::of($users)
                    // ->addIndexColumn()
                    // ->addColumn('action', function($row){
                    //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                    //     return $btn;
                    // })
                    // ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.shops.list_new', compact('all_operator_info'));
        }

        public function get_shop_total_sum()
        {
            $shop_total_in = \VanguardLTE\Transaction::whereIn('to_userId', $this->child_users_array)
                ->where('from_userId', auth()->user()->id)
                ->groupBy('from_userId')
                ->selectRaw('SUM(in_amount) as sum_in');
            if ($shop_total_in->first()) {
                $shop_total_in = $shop_total_in->first()->sum_in;
            } else {
                $shop_total_in = 0;
            }
            $shop_total_out = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_users_array)
                ->where('to_userId', auth()->user()->id)
                ->groupBy('to_userId')
                ->selectRaw('SUM(out_amount) as sum_out');
            if ($shop_total_out->first()) {
                $shop_total_out = $shop_total_out->first()->sum_out;
            } else {
                $shop_total_out = 0;
            }
            return array($shop_total_in, $shop_total_out);
        }

        public function show_profile(\Illuminate\Http\Request $request)
        {
            // var_dump('e');die;
            // Session::put('sel_operator_id', $request->user_id);
            Session::put('sel_operator_id', auth()->user()->id);
            Session::put('sel_shop_id', $request->user_id);
            //check if the recent Operator(logged user) has permission to edit them
            // $jpg_permission_operator = \VanguardLTE\Operator_permissions::where('description', 'operator_edit_jackpot')->withCount('per_op');
            $edit_jpg_permissionId = \VanguardLTE\Operator_permissions::where('description', 'operator_edit_jackpot')->first()->id;
            $jpg_permission_operator = \VanguardLTE\Permissions_operator::where('user_id', auth()->user()->id)
                ->where('permission_id', $edit_jpg_permissionId);
            if ($jpg_permission_operator->first()) {
                $jpg_permission_operator = true;
            } else {
                $jpg_permission_operator = false;
            }
            $shop_info = \VanguardLTE\Shop::where('user_id', $request->user_id)->first();
            $shop_balance = \VanguardLTE\User::find($request->user_id)->balance;
            // $currencies = \VanguardLTE\Currency::all();
            // $currencies = array_merge(\VanguardLTE\Shop::$values['currency'], ['ALL']);
            $currencies = \VanguardLTE\Shop::$values['currency'];
            $timezones = \VanguardLTE\Timezones::all();
            //get all parents IDs
            $parent_user = \VanguardLTE\User::find($request->user_id)->parent;
            $this->parent_users_array = [];
            $parent_username_array = array_reverse($this->get_upper_parents_names($parent_user));
            array_shift($parent_username_array);
            //get total_in/out
            $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;
            $child_users = \VanguardLTE\User::find(auth()->user()->id)->childs;
            $this->get_hierarchy_childs_ids($child_users, $user_role_id);
            $shop_total_in = ($this->get_shop_total_sum())[0];
            $shop_total_out = ($this->get_shop_total_sum())[0];
            $shop_total_sum = $shop_total_in - $shop_total_out;
            //get user total credits
            $shop_total_user = \VanguardLTE\User::where('parent_id', $request->user_id)
                ->groupBy('parent_id')
                ->selectRaw('SUM(balance) as total_balance');
            if ($shop_total_user->first()) {
                $shop_total_user_credits = $shop_total_user->first()->total_balance;
            } else {
                $shop_total_user_credits = 0;
            }

            if ($request->ajax()) {
                if (isset($request->sel_action) && $request->sel_action == 'edit_jpg_list') {

                    if (isset($request->sel_rows)) {

                        #edit the jackpots info
                        $data = $request->only(
                            'start_balance',
                            'jpg_trigger',
                            'percent',
                            'user_id',
                        );
                        foreach ($request->sel_rows as  $sel_row_id) {
                            \VanguardLTE\JPG::where('id', $sel_row_id)->update($data);
                        }
                    }
                }
                if (isset($request->sel_action) && $request->sel_action == 'win_jackpot') {
                    //update user balance
                    $recent_balance = \VanguardLTE\User::find($request->user_id)->balance;
                    $recent_balance += $request->jackpot;
                    \VanguardLTE\User::where('id', $request->user_id)->update(['balance' => $recent_balance]);
                    return response()->json([
                        'status' => 'ok',
                        'balance' => $recent_balance
                    ]);
                }
                # get the 3 jackpots for the shop selected
                $shop_jackpots = \VanguardLTE\JPG::where('user_id', $request->user_id)->get();
                return Datatables::of($shop_jackpots)
                    ->make(true);
            }
            // 23/05/22, Provider allocation logic
            // $providers_names = [];
            // $providers_deluxecasino_names = [];
            // $providers_vipcasino_names = [];
            // $providers_deluxelivecasino_names = [];
            // $providers_viplivecasino_names = [];
            // $providers_virtual_names = [];
            // $providers_lotto_names = [];
            // $providers_fiable_names = [];

            // $arr_providers_banned = [];
            // $providers_banned_list = \VanguardLTE\Gameproviders_shop::where('user_id', $parent_user->id)->get();
            // if (count($providers_banned_list)) {
            //     foreach ($providers_banned_list as $key => $value) {
            //         array_push($arr_providers_banned, $value->provider_disabled);
            //     }
            // }

            #final category allocation logic on 14/06/22
            $flag_1operator = false;
            #check if the logged operator is my father
            if (auth()->user()->id == $parent_user->id) {
                $flag_1operator = true;
            }

            $categories = \VanguardLTE\Category::where('user_id', $request->user_id)->get();
            // $providers_deluxecasino = \VanguardLTE\Gameproviders::where('category_id', 1)->groupBy('name')->get();
            // $providers_vipcasino = \VanguardLTE\Gameproviders::where('category_id', 2)->groupBy('name')->get();
            // $providers_deluxelivecasino = \VanguardLTE\Gameproviders::where('category_id', 3)->groupBy('name')->get();
            // $providers_viplivecasino = \VanguardLTE\Gameproviders::where('category_id', 7)->groupBy('name')->get();
            // $providers_virtual = \VanguardLTE\Gameproviders::where('category_id', 4)->groupBy('name')->get();
            // $providers_lotto = \VanguardLTE\Gameproviders::where('category_id', 5)->groupBy('name')->get();
            // $providers_fiable = \VanguardLTE\Gameproviders::where('category_id', 8)->groupBy('name')->get();
            // if (count($providers)) {
            //     foreach ($providers as $key => $value) {
            //         array_push($providers_deluxecasino_names, $value->name);
            //     }
            // }
            // if (count($providers_deluxecasino)) {
            //     foreach ($providers_deluxecasino as $key => $value) {
            //         array_push($providers_deluxecasino_names, $value->name);
            //     }
            // }
            // if (count($providers_vipcasino)) {
            //     foreach ($providers_vipcasino as $key => $value) {
            //         array_push($providers_vipcasino_names, $value->name);
            //     }
            // }
            // if (count($providers_deluxelivecasino)) {
            //     foreach ($providers_deluxelivecasino as $key => $value) {
            //         array_push($providers_deluxelivecasino_names, $value->name);
            //     }
            // }
            // if (count($providers_viplivecasino)) {
            //     foreach ($providers_viplivecasino as $key => $value) {
            //         array_push($providers_viplivecasino_names, $value->name);
            //     }
            // }
            // if (count($providers_virtual)) {
            //     foreach ($providers_virtual as $key => $value) {
            //         array_push($providers_virtual_names, $value->name);
            //     }
            // }
            // if (count($providers_lotto)) {
            //     foreach ($providers_lotto as $key => $value) {
            //         array_push($providers_lotto_names, $value->name);
            //     }
            // }
            // if (count($providers_fiable)) {
            //     foreach ($providers_fiable as $key => $value) {
            //         array_push($providers_fiable_names, $value->name);
            //     }
            // }

            // $providers_disabled = [];
            // $providers_disabled_list = \VanguardLTE\Gameproviders_shop::where('user_id', $request->user_id)->get();
            // if (count($providers_disabled_list)) {
            //     foreach ($providers_disabled_list as $key => $value) {
            //         array_push($providers_disabled, $value->provider_disabled);
            //     }
            // }

            return view('backend.shops.profile', compact('flag_1operator', 'categories', 'jpg_permission_operator', 'shop_info', 'shop_balance', 'currencies', 'timezones', 'shop_total_in', 'shop_total_out', 'shop_total_sum', 'shop_total_user_credits', 'parent_username_array'));
            // return view('backend.shops.profile', compact('arr_providers_banned', 'flag_1operator', 'providers_deluxecasino_names', 'providers_vipcasino_names', 'providers_deluxelivecasino_names', 'providers_viplivecasino_names', 'providers_virtual_names', 'providers_lotto_names', 'providers_fiable_names', 'providers_disabled', 'jpg_permission_operator', 'shop_info', 'shop_balance', 'currencies', 'timezones', 'shop_total_in', 'shop_total_out', 'shop_total_sum', 'shop_total_user_credits', 'parent_username_array'));
        }

        public function show_gamecategories(\Illuminate\Http\Request $request)
        {
            if (isset($request->sel_action) && $request->sel_action == 'edit_gamecategory_list') { //game categories allowcation request by Operator
                //edit the permission row to permissions_operator table
                $category_check = \VanguardLTE\Gamecategories_shop::where('gamecategory_id', $request->sel_category)
                    ->where('user_id', $request->user_id);
                switch ($request->sel_status) {
                    case 0:
                        # remove the recent category
                        if (!($category_check->first())) {
                            \VanguardLTE\Gamecategories_shop::create([
                                'gamecategory_id' => $request->sel_category,
                                'user_id' => $request->user_id
                            ]);
                        } else {
                            $category_check->update(['enabled' => 0]);
                        }

                        break;
                    case 1:
                        # enable the permission selected
                        if (!($category_check->first())) {
                            \VanguardLTE\Gamecategories_shop::create([
                                'gamecategory_id' => $request->sel_category,
                                'user_id' => $request->user_id
                            ]);
                        } else {
                            $category_check->update(['enabled' => 1]);
                        }

                        break;
                    default:
                        break;
                }
            }
            $parent_info = \VanguardLTE\User::find($request->user_id)->parent;
            $arr_parent_categories = [];
            $parent_categories_info = \VanguardLTE\Gamecategories_shop::where(['user_id' => $parent_info->id, 'enabled' => 1])->get();
            if (count($parent_categories_info)) {
                # get the categories IDs allowed from parent Operator
                foreach ($parent_categories_info as $key => $category_info) {
                    array_push($arr_parent_categories, $category_info->gamecategory_id);
                }
            }
            // $array_categories = \VanguardLTE\Shop_gamecategories::withCount('per_operator')->get();
            if (isset($request->sel_location) && $request->sel_location == 'from_shop_profile') {
                $array_categories = \VanguardLTE\Shop_gamecategories::whereIn('id', $arr_parent_categories)->withCount('per_shop')->get();
            } else {
                $array_categories = \VanguardLTE\Shop_gamecategories::whereIn('id', $arr_parent_categories)->withCount('per_operator')->get();
            }
            if (count($array_categories) == 0) {
                $array_categories = null;
            }
            return Datatables::of($array_categories)
                ->make(true);
        }
        public function show_gameproviders(\Illuminate\Http\Request $request)
        {
            if (isset($request->sel_action) && isset($request->providerChecked) && isset($request->user_id)) { //game provider allowcation request by Operator
                switch ($request->sel_action) {
                    case 'enabled':
                        #remove the provider from the banned list
                        \VanguardLTE\Category::where([
                            'user_id' => $request->user_id,
                            'href' => $request->providerChecked
                        ])->update(['enabled' => 1]);
                        return response()->json([
                            'status' => 'enabled the provider'
                        ]);
                        break;
                    case 'disabled':
                        # add the provider to the banned list
                        \VanguardLTE\Category::where([
                            'user_id' => $request->user_id,
                            'href' => $request->providerChecked
                        ])->update(['enabled' => 0]);
                        return response()->json([
                            'status' => 'disabled the provider'
                        ]);
                        break;
                    default:
                        break;
                }
            } else {
                return response()->json([
                    'status' => 'failed'
                ]);
            }
        }

        public function edit_profile(Request $request)
        {
            //form_submit part
            $shop_info = \VanguardLTE\Shop::where('user_id', $request->user_id)->first();
            $user_info = \VanguardLTE\User::find($request->user_id);
            $parent_operator_info = $user_info->parent;
            $parent_operator_balance = 0;
            if ($parent_operator_info) {
                $parent_operator_balance = $parent_operator_info->balance;
            }

            if (isset($request->edit_shop_profile)) {
                $data = $request->only([
                    'timezone',
                    'currency',
                    'bonus',
                    'bank',
                ]);

                \VanguardLTE\Shop::where('user_id', $request->user_id)->update($data);
                return response()->json([
                    'timezone' => $data['timezone'],
                    'currency' => $data['currency'],
                    'bonus' => $data['bonus'],
                    'bank' => $data['bank'],
                ]);
            }
            if ($request->reset_shop_credits || $request->reset_shop_startCredits) {
                $shop_credits_reset = false;
                $shop_startCredits_reset = false;
                if ($request->reset_shop_credits) {
                    $user_balance = $user_info->balance;
                    $parent_operator_balance += $user_balance;
                    $parent_operator_info->balance = $parent_operator_balance;
                    $parent_operator_info->save();
                    $user_info->update(['balance' => 0]);
                    $shop_credits_reset = true;
                }
                if ($request->reset_shop_startCredits) {
                    $shop_startCredits = $shop_info->start_credits;
                    $parent_operator_balance += $shop_startCredits;
                    $parent_operator_info->balance = $parent_operator_balance;
                    $parent_operator_info->save();
                    \VanguardLTE\Shop::where('user_id', $request->user_id)->update(['start_credits' => 0]);
                    $shop_startCredits_reset = true;
                }
                return response()->json([
                    'shop_credits_reset' => $shop_credits_reset,
                    'shop_startCredits_reset' => $shop_startCredits_reset,
                ]);
            }

            if ($request->shop_credits_in) {

                if ($request->shop_credits_in > $parent_operator_balance) {
                    return response()->json([
                        'status' => 'lack_operator_credits',
                    ]);
                } else {
                    $parent_operator_balance -= $request->shop_credits_in;
                    \VanguardLTE\User::whereId($parent_operator_info->id)->update(['balance' => $parent_operator_balance]);

                    $shop_balance = $user_info->balance;
                    $shop_balance += $request->shop_credits_in;
                    \VanguardLTE\User::whereId($request->user_id)->update(['balance' => $shop_balance]);

                    return response()->json([
                        'shop_credits' => $shop_balance,
                    ]);
                }
            } elseif ($request->shop_credits_out) {
                $shop_balance = 0;
                if ($user_info) {
                    $shop_balance = $user_info->balance;
                }
                if ($request->shop_credits_out > $shop_balance) {
                    return response()->json([
                        'status' =>  'lack_user_credits',
                    ]);
                } else {
                    $shop_balance -= $request->shop_credits_out;
                    \VanguardLTE\User::whereId($request->user_id)->update(['balance' => $shop_balance]);
                    $parent_operator_balance = $parent_operator_info->balance;
                    $parent_operator_balance += $request->shop_credits_out;
                    \VanguardLTE\User::whereId($parent_operator_info->id)->update(['balance' => $parent_operator_balance]);

                    return response()->json([
                        'shop_credits' => $shop_balance,
                    ]);
                }
            } elseif ($request->shop_account_in) {
                $shop_account_limit = $shop_info->account_limit;
                $shop_account_limit += $request->shop_account_in;
                \VanguardLTE\Shop::where('user_id', $request->user_id)->update(['account_limit' => $shop_account_limit]);
                return response()->json([
                    'shop_account_limits' => $shop_account_limit,
                ]);
            } elseif ($request->shop_account_out) {
                $shop_account_limit = $shop_info->account_limit;
                $shop_account_limit -= $request->shop_account_out;
                \VanguardLTE\Shop::where('user_id', $request->user_id)->update(['account_limit' => $shop_account_limit]);
                return response()->json([
                    'shop_account_limits' => $shop_account_limit,
                ]);
            } elseif ($request->sel_action && $request->sel_action == 'edit_jpg_list') {
            } else {
            }
        }

        public function create_transaction($from_userId, $to_userId, $in_amount, $out_amount)
        {

            $trans_info['from_userId'] = $from_userId;
            $trans_info['to_userId'] = $to_userId;
            $trans_info['in_amount'] = $in_amount;
            $trans_info['out_amount'] = $out_amount;
            \VanguardLTE\Transaction::create($trans_info);
        }

        //For Shop Role
        public function home(Request $request)
        {
            $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;
            $child_users = \VanguardLTE\User::find(auth()->user()->id)->childs;
            $this->get_hierarchy_childs_ids($child_users, $user_role_id);

            //get total in/out/balance
            $shop_balance = \VanguardLTE\User::find(auth()->user()->id)->balance;
            $shop_bonus = \VanguardLTE\User::find(auth()->user()->id)->shop->bonus;
            $shop_total_in = ($this->get_shop_total_sum())[0];
            $shop_total_out = ($this->get_shop_total_sum())[1];
            $shop_total_sum = $shop_total_in - $shop_total_out;

            if ($request->ajax()) {

                $exception_shop_lack_credit = false;
                $exception_user_lack_credit = false;

                if (isset($request->shop_balance_changing)) {
                    # return the balances changed
                    return response()->json([
                        'shop_balance' => $shop_balance,
                        'shop_total_in' => $shop_total_in,
                        'shop_total_out' => $shop_total_out,
                        'shop_total_sum' => $shop_total_sum,
                    ]);
                }
                if (isset($request->sel_action_alarm)) {
                    $action_type = 'enabled';
                    $sel_action = 'danger';
                    if ($request->sel_action_alarm == 'danger') {
                        $sel_action = 'success';
                    }
                    \VanguardLTE\User::where('id', $request->sel_userid)->update([$action_type => $sel_action]);
                }
                if (isset($request->sel_action) && $request->sel_action == 'edit_user') {
                    $data = $request->only([
                        'username',
                        // 'password',
                    ]);
                    \VanguardLTE\User::where('id', $request->sel_userid)->update($data);
                }
                if (isset($request->sel_action) && $request->sel_action == 'del_user') {
                    # remove the user and move its balance to parent Shop
                    $user_info = \VanguardLTE\User::find($request->sel_userid);
                    $user_balance = $user_info->balance;
                    if ($user_balance != 0) {
                        $parent_shop = $user_info->parent;
                        \VanguardLTE\User::whereId($parent_shop->id)->update(['balance' => $parent_shop->balance + $user_balance]);
                    }
                    $user_deleted = $user_info->delete(); //returns true/false
                }
                if (isset($request->sel_credit)) {

                    $user_balance = \VanguardLTE\User::find($request->sel_userid)->balance;

                    switch ($request->sel_action) {
                        case 'in_credits':
                            if ($shop_balance >= $request->sel_credit) {
                                $user_balance += $request->sel_credit;
                                $shop_balance -= $request->sel_credit;
                                $this->create_transaction(auth()->user()->id, $request->sel_userid, $request->sel_credit, 0);
                                if ($shop_bonus != 0) { //add bonus as 'happy_bonus' option
                                    $additional_happy_bonus = $request->sel_credit * $shop_bonus / 100;
                                    #deduce it from Admin balance and add it to user happyhour field
                                    // $admin_info = \VanguardLTE\User::where('username', 'Admin')->first();
                                    $admin_info = \VanguardLTE\User::where('role_id', 6)->first();
                                    $old_admin_balance = $admin_info->balance;
                                    $admin_info->update(['balance' => $old_admin_balance - $additional_happy_bonus]);
                                    $old_happy_bonus = \VanguardLTE\User::find($request->sel_userid)->happyhours;
                                    \VanguardLTE\User::find($request->sel_userid)->update(['happyhours' => $old_happy_bonus + $additional_happy_bonus]);
                                }
                            } else {
                                $exception_shop_lack_credit = true;
                                return response()->json([
                                    'shop_lack_credits' => true,
                                ]);
                            }
                            break;
                        case 'out_credits':
                            if ($user_balance >= $request->sel_credit) {
                                # code...
                                $user_balance -= $request->sel_credit;
                                $shop_balance += $request->sel_credit;
                                $this->create_transaction($request->sel_userid, auth()->user()->id,  0, $request->sel_credit);
                                if ($shop_bonus != 0) { //add bonus as 'happy_bonus' option
                                    $additional_happy_bonus = $request->sel_credit * $shop_bonus / 100;
                                    #Increase Admin balance and reduce it from user happyhour field
                                    // $admin_info = \VanguardLTE\User::where('username', 'Admin')->first();
                                    $admin_info = \VanguardLTE\User::where('role_id', 6)->first();
                                    $old_admin_balance = $admin_info->balance;
                                    $admin_info->update(['balance' => $old_admin_balance + $additional_happy_bonus]);
                                    $old_happy_bonus = \VanguardLTE\User::find($request->sel_userid)->happyhours;
                                    \VanguardLTE\User::find($request->sel_userid)->update(['happyhours' => $old_happy_bonus - $additional_happy_bonus]);
                                }
                            } else {
                                $exception_user_lack_credit = true;
                                return response()->json([
                                    'user_lack_credits' => true,
                                ]);
                            }
                            break;
                        default:
                            break;
                    }
                    \VanguardLTE\User::find($request->sel_userid)->update(['balance' => $user_balance]);
                    \VanguardLTE\User::find(auth()->user()->id)->update(['balance' => $shop_balance]);
                }

                $users = \VanguardLTE\User::whereIn('users.id', $this->child_users_array)
                    // ->orderBy('time', 'DESC')
                    // ->orderBy('user_id', 'ASC')
                    // ->groupBy('id')
                    ->leftJoin('game_log as game_log', 'users.id', '=', 'game_log.user_id')
                    // ->groupBy('game_log.user_id')
                    ->leftJoin('games as game_table', 'game_table.id', '=', 'game_log.game_id')
                    // ->latest('game_log.time')
                    ->select('users.id as id', 'users.username as username', 'users.balance as credits', 'users.happyhours as bonus', 'users.enabled as enabled', 'game_table.title as title')
                    ->get();

                // if ($exception_shop_lack_credit) {
                //     return response()->json([
                //         'shop_lack_credits' => true,
                //     ]);
                // }
                // if ($exception_user_lack_credit) {
                //     return response()->json([
                //         'user_lack_credits' => true,
                //     ]);
                // }

                return Datatables::of($users)
                    // ->addColumn('intro', $exception_shop_lack_credit)
                    // ->with('shop_lack_credits', $exception_shop_lack_credit)
                    // ->toJson();
                    ->make(true);
            }
            return view('backend.shops.roles.home', compact('shop_balance', 'shop_total_in', 'shop_total_out', 'shop_total_sum'));
        }
        public function show_cash(Request $request)
        {

            //Store the role IDs to Global vars
            $this->user_roleId =  \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;

            if ($request->ajax()) {

                Session::put('currency', 'ALL');

                if (isset($request->startdate)) {
                    //change date range
                    $start_date = date('Y-m-d H:i:s', strtotime($request->startdate));
                    $end_date = date('Y-m-d H:i:s', strtotime($request->enddate));
                    Session::put('startdate', $start_date);
                    Session::put('enddate', $end_date);
                } else {
                    if (Session::get('startdate') == null) {
                        $_start_date = date('Y-m-d H:i:s', strtotime(now()));
                        $_end_date = date('Y-m-d H:i:s', strtotime(now()));
                        Session::put('startdate', $_start_date);
                        Session::put('enddate', $_end_date);
                    }
                }

                $child_users = \VanguardLTE\User::find(auth()->user()->id)->childs;
                $this->child_users_array = array();
                $this->get_hierarchy_childs_ids($child_users, $this->user_roleId);

                if (Session::get('startdate') == Session::get('enddate')) { //now()
                    # get today's trans only
                    if (Session::get('currency') == 'ALL') {
                        // $players = \VanguardLTE\GamesHistory::whereIn('user_id', $this->child_users_array)
                        //     ->whereYear('games_history.created_at', '>=', now()->year)
                        //     ->whereMonth('games_history.created_at', '>=', now()->month)
                        //     ->whereDay('games_history.created_at', '>=', now()->day)
                        //     ->leftJoin('users as user_table', 'user_table.id', '=', 'games_history.user_id')
                        //     ->leftJoin('gamelist as gamelist_table', 'gamelist_table.game_slug', '=', 'games_history.game_id')
                        //     ->select('user_table.username as username', 'gamelist_table.game_name as game_name', 'games_history.in_amount as in_amount', 'games_history.out_amount as out_amount', 'games_history.created_at as created_at')
                        //     ->get();
                        $players = \VanguardLTE\StatGame::whereIn('user_id', $this->child_users_array)
                            ->whereBetween('stat_game.date_time', [Session::get('startdate'), Session::get('enddate')])
                            ->leftJoin('users as user_table', 'user_table.id', '=', 'stat_game.user_id')
                            ->leftJoin('games as games_table', 'games_table.name', '=', 'stat_game.game')
                            ->select('user_table.username as username', 'games_table.title as game_name', 'stat_game.bet as in_amount', 'stat_game.win as out_amount', 'stat_game.date_time as created_at')
                            ->get();
                    } else {
                    }
                } else {
                    # get trans for the date range
                    if (Session::get('currency') == 'ALL') {
                        // $players = \VanguardLTE\GamesHistory::whereIn('user_id', $this->child_users_array)
                        // ->whereBetween('games_history.created_at', [Session::get('startdate'), Session::get('enddate')])
                        // ->leftJoin('users as user_table', 'user_table.id','=','games_history.user_id')
                        // ->leftJoin('gamelist as gamelist_table', 'gamelist_table.game_slug','=','games_history.game_id')
                        // ->select('user_table.username as username','gamelist_table.game_name as game_name', 'games_history.in_amount as in_amount','games_history.out_amount as out_amount','games_history.created_at as created_at')
                        // ->get();
                        $players = \VanguardLTE\StatGame::whereIn('user_id', $this->child_users_array)
                            ->whereBetween('stat_game.date_time', [Session::get('startdate'), Session::get('enddate')])
                            ->leftJoin('users as user_table', 'user_table.id', '=', 'stat_game.user_id')
                            ->leftJoin('games as games_table', 'games_table.name', '=', 'stat_game.game')
                            ->select('user_table.username as username', 'games_table.title as game_name', 'stat_game.bet as in_amount', 'stat_game.win as out_amount', 'stat_game.date_time as created_at')
                            ->get();
                    } else {
                    }
                }

                return Datatables::of($players)
                    ->make(true);
            }
            return view('backend.shops.roles.cash');
        }

        public function transactions(Request $request)
        {

            if ($request->ajax()) {
                $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;
                $child_users = \VanguardLTE\User::find(auth()->user()->id)->childs;
                $this->get_hierarchy_childs_ids($child_users, $user_role_id);

                $users_trans = \VanguardLTE\Transaction::whereIn('to_userId', $this->child_users_array)
                    ->Where('from_userId', auth()->user()->id)
                    // ->with('shop_user')->with('shop_to_user')
                    ->leftJoin('users as from_table', 'from_table.id', '=', 'transaction.from_userId')
                    ->leftJoin('users as to_table', 'to_table.id', '=', 'transaction.to_userId')
                    ->select('from_table.username as from_username', 'to_table.username as to_username', 'transaction.in_amount as in_amount', 'transaction.out_amount as out_amount', 'transaction.created_at as created_at', 'transaction.ip_address as ip_address')
                    ->get();


                return Datatables::of($users_trans)
                    ->make(true);
            }
            return view('backend.shops.roles.transaction');
        }
        public function operator_transactions(Request $request)
        {

            if ($request->ajax()) {
                $parent_id = \VanguardLTE\User::find(auth()->user()->id)->parent_operator()->first()->id;
                $array_ids = array($parent_id, auth()->user()->id);
                $users_trans = \VanguardLTE\Transaction::whereIn('to_userId', $array_ids)
                    ->WhereIn('from_userId', $array_ids)

                    // ->with('shop_user')->with('shop_to_user')
                    ->leftJoin('users as from_table', 'from_table.id', '=', 'transaction.from_userId')
                    ->leftJoin('users as to_table', 'to_table.id', '=', 'transaction.to_userId')
                    ->select('from_table.username as from_username', 'to_table.username as to_username', 'transaction.in_amount as in_amount', 'transaction.out_amount as out_amount', 'transaction.created_at as created_at')
                    ->get();


                return Datatables::of($users_trans)
                    ->addIndexColumn()
                    ->make(true);
            }
            return view('backend.shops.roles.op_transactions');
        }
        public function reset(Request $request)
        {
            $shop_id = auth()->user()->id;
            $shop_name = \VanguardLTE\User::find(auth()->user()->id)->username;
            $shop_total_in = 0;
            $shop_total_out = 0;
            if ($request->shop_id) {
                return response()->json([
                    'shop_name' => $shop_name,
                    'shop_total_in' => $shop_total_in,
                    'shop_total_out' => $shop_total_out
                ]);
            }
            return view('backend.shops.roles.reset', compact('shop_id', 'shop_name', 'shop_total_in', 'shop_total_out'));
        }
        public function shifts(Request $request)
        {
            if ($request->ajax()) {
                $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;
                $child_users = \VanguardLTE\User::find(auth()->user()->id)->childs;
                $this->get_hierarchy_childs_ids($child_users, $user_role_id);

                $users_trans = \VanguardLTE\Transaction::whereIn('to_userId', $this->child_users_array)
                    ->Where('from_userId', auth()->user()->id)
                    // ->with('shop_user')->with('shop_to_user')
                    ->leftJoin('users as from_table', 'from_table.id', '=', 'transaction.from_userId')
                    ->select('from_table.username as from_username', 'transaction.in_amount as in_amount', 'transaction.out_amount as out_amount', 'transaction.created_at as created_at')
                    ->get();


                return Datatables::of($users_trans)
                    ->make(true);
            }
            return view('backend.shops.roles.shifts');
        }
        public function detail(Request $request)
        {
            $shop_name = \VanguardLTE\User::find(auth()->user()->id)->username;
            if ($request->ajax()) {
                // $userData = $request->only(["new_password"]);
                // $userData['password'] = Hash::make($userData['new_password']);
                $data['username'] = ($request->username);
                $data['password'] = ($request->password);
                $data['password-confirmation'] = ($request->password);
                $userId = auth()->user()->id;
                $this->users->update($userId, $data);
                // $user->password = bcrypt($request->post('new_password'));
                // $user->save();
                return response()->json([
                    'status' => 'success',
                ]);
            }
            return view('backend.shops.roles.profile', compact('shop_name'));
        }






















        public function create()
        {
            $directories = [];
            foreach (glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo) {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $categories = \VanguardLTE\Category::where(['parent' => 0])->get();
            $shop = new \VanguardLTE\Shop();
            $availibleUsers = [];
            if (auth()->user()->hasRole('admin')) {
                $me = \VanguardLTE\User::where('id', auth()->user()->id)->get();
                $availibleUsers = \VanguardLTE\User::whereIn('role_id', [
                    4,
                    5
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($availibleUsers);
            }
            if (auth()->user()->hasRole('agent')) {
                $me = \VanguardLTE\User::where('id', auth()->user()->id)->get();
                $distributors = \VanguardLTE\User::where([
                    'parent_id' => auth()->user()->id,
                    'role_id' => 4
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($distributors);
            }
            if (auth()->user()->hasRole('distributor')) {
                $availibleUsers = \VanguardLTE\User::where('id', auth()->user()->id)->has('rel_shops')->get();
            }
            $blocks = [];
            if (auth()->user()->hasPermission('shops.unblock')) {
                $blocks[0] = __('app.unblock');
            }
            if (auth()->user()->hasPermission('shops.block')) {
                $blocks[1] = __('app.block');
            }
            return view('backend.shops.add', compact('directories', 'categories', 'shop', 'availibleUsers', 'blocks'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $shops = \VanguardLTE\Shop::select('shops.*', 'shops.id AS shop_id')->where(['user_id' => auth()->user()->id])->get();

            if (auth()->user()->shop_limit <= 0 && false) {
                return redirect()->route('backend.shop.list')->withErrors(['You don\'t have limit']);
            }

            if (!auth()->user()->hasRole('distributor') && !auth()->user()->hasRole('agent')) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }

            $data = $request->only([
                'name',
                'percent',
                'frontend',
                'currency',
                'is_blocked',
                'orderby',
                'max_win',
                'shop_limit',
            ]);
            $already =  \VanguardLTE\Shop::where('name', $request->name)->exists();
            if (!$already) {
                $shop = \VanguardLTE\Shop::create($data + ['user_id' => auth()->user()->id]);
                $user = \VanguardLTE\User::find(auth()->user()->id);

                $progress = \VanguardLTE\Progress::where('shop_id', 0)->get();
                if (count($progress)) {
                    foreach ($progress as $item) {
                        $newProgress = $item->replicate();
                        $newProgress->shop_id = $shop->id;
                        $newProgress->save();
                    }
                }
                $welcomebonuses = \VanguardLTE\WelcomeBonus::where('shop_id', 0)->get();
                if (count($welcomebonuses)) {
                    foreach ($welcomebonuses as $item) {
                        $newWelcomeBonus = $item->replicate();
                        $newWelcomeBonus->shop_id = $shop->id;
                        $newWelcomeBonus->save();
                    }
                }
                $smsbonuses = \VanguardLTE\SMSBonus::where('shop_id', 0)->get();
                if (count($smsbonuses)) {
                    foreach ($smsbonuses as $item) {
                        $newSMSBonus = $item->replicate();
                        $newSMSBonus->shop_id = $shop->id;
                        $newSMSBonus->save();
                    }
                }

                if (isset($request->categories) && count($request->categories)) {
                    foreach ($request->categories as $category) {
                        \VanguardLTE\ShopCategory::create([
                            'shop_id' => $shop->id,
                            'category_id' => $category
                        ]);
                    }
                }
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => auth()->user()->id
                ]);
                $user->update(['shop_id' => $shop->id]);

                \VanguardLTE\Task::create([
                    'category' => 'shop',
                    'action' => 'create',
                    'item_id' => $shop->id,
                    'shop_id' => auth()->user()->shop_id
                ]);
                \VanguardLTE\GameBank::create([
                    'shop_id' => $shop->id
                ]);
                \VanguardLTE\FishBank::create([
                    'shop_id' => $shop->id
                ]);
                $g_table = (new \VanguardLTE\Game)->getTable();
                \DB::statement('insert ' . \DB::getTablePrefix() . $g_table . '(name,title, shop_id, jpg_id,label, device,gamebank,chanceFirepot1,chanceFirepot2,chanceFirepot3,fireCount1,fireCount2,fireCount3,lines_percent_config_spin, lines_percent_config_spin_bonus, lines_percent_config_bonus,lines_percent_config_bonus_bonus, rezerv,cask,advanced,bet, scaleMode, slotViewState, view,denomination,category_temp, original_id, bids,stat_in, stat_out) (select name, title, ' . $shop->id . ' as shop_id, jpg_id, label, device,gamebank,chanceFirepot1,chanceFirepot2,chanceFirepot3,fireCount1,fireCount2,fireCount3,lines_percent_config_spin, lines_percent_config_spin_bonus, lines_percent_config_bonus,lines_percent_config_bonus_bonus, rezerv,cask,advanced,bet, scaleMode, slotViewState, view,denomination,category_temp, original_id, bids,stat_in, stat_out from ' . \DB::getTablePrefix() . $g_table . ' where shop_id=0)');
                $jpg_table = (new \VanguardLTE\JPG)->getTable();
                \DB::statement('insert ' . \DB::getTablePrefix() . $jpg_table . '(name,balance,start_balance,pay_sum,percent,shop_id) (select name,balance,start_balance,pay_sum,percent, ' . $shop->id . ' as shop_id from ' . \DB::getTablePrefix() . $jpg_table . ' where shop_id=0)');
                // $me = \VanguardLTE\User::where('id', auth()->user()->id);
                // $me->decrement('shop_limit', 1);

                return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_created'));
            } else {
                return redirect()->route('backend.shop.list')->withErrors(trans('app.shop_already_exists'));
            }
        }
        public function fast_shop()
        {
            if (!auth()->user()->hasRole('admin')) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }
            $sleep = 0;
            $rand = rand(111111111, 999999999);
            $shop = [
                'name' => 'DEMO_' . $rand,
                'percent' => 90,
                'frontend' => 'Default',
                'orderby' => 'AZ',
                'currency' => 'USD',
                'access' => 1
            ];
            $agent = [
                'username' => 'A_' . $rand,
                'password' => 'A_' . $rand,
                'status' => 'Active'
            ];
            $distributor = [
                'username' => 'D_' . $rand,
                'balance' => 0,
                'password' => 'D_' . $rand,
                'status' => 'Active'
            ];
            $manager = [
                'username' => 'M_' . $rand,
                'password' => 'M_' . $rand,
                'status' => 'Active'
            ];
            $cashier = [
                'username' => 'C_' . $rand,
                'password' => 'C_' . $rand,
                'status' => 'Active'
            ];
            $users = ['count' => 10];
            $distributorBalance = 5000;
            $agentBalance = 10000;
            $shopBalance = 5000;
            $userBalance = 100;
            $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
            $sleep++;
            $agent = \VanguardLTE\User::create($agent + [
                'parent_id' => auth()->user()->id,
                'role_id' => 5,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $agent->attachRole($roles->find(5));
            $sleep++;
            $distributor = \VanguardLTE\User::create($distributor + [
                'parent_id' => $agent->id,
                'role_id' => 4,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $distributor->attachRole($roles->find(4));
            $sleep++;
            $manager = \VanguardLTE\User::create($manager + [
                'parent_id' => $distributor->id,
                'role_id' => 3,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $manager->attachRole($roles->find(3));
            $sleep++;
            $cashier = \VanguardLTE\User::create($cashier + [
                'parent_id' => $manager->id,
                'role_id' => 2,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $cashier->attachRole($roles->find(2));
            $shop = \VanguardLTE\Shop::create($shop + ['user_id' => $distributor->id]);
            $progress = \VanguardLTE\Progress::where('shop_id', 0)->get();
            if (count($progress)) {
                foreach ($progress as $item) {
                    $newProgress = $item->replicate();
                    $newProgress->shop_id = $shop->id;
                    $newProgress->save();
                }
            }
            $welcomebonuses = \VanguardLTE\WelcomeBonus::where('shop_id', 0)->get();
            if (count($welcomebonuses)) {
                foreach ($welcomebonuses as $item) {
                    $newWelcomeBonus = $item->replicate();
                    $newWelcomeBonus->shop_id = $shop->id;
                    $newWelcomeBonus->save();
                }
            }
            $smsbonuses = \VanguardLTE\SMSBonus::where('shop_id', 0)->get();
            if (count($smsbonuses)) {
                foreach ($smsbonuses as $item) {
                    $newSMSBonus = $item->replicate();
                    $newSMSBonus->shop_id = $shop->id;
                    $newSMSBonus->save();
                }
            }
            $open_shift = \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(),
                'balance' => 0,
                'user_id' => $cashier->id,
                'shop_id' => $shop->id
            ]);
            if ($agentBalance > 0) {
                $agent->addBalance('add', $agentBalance);
            }
            if ($distributorBalance > 0) {
                $distributor->addBalance('add', $distributorBalance, $agent);
            }
            if ($shopBalance > 0) {
                $open_shift->increment('balance_in', $shopBalance);
                $distributor->decrement('balance', $shopBalance);
                $shop->increment('balance', $shopBalance);
                \VanguardLTE\Statistic::create([
                    'user_id' => $distributor->id,
                    'shop_id' => $shop->id,
                    'sum' => $shopBalance,
                    'type' => 'add',
                    'system' => 'shop'
                ]);
            }
            foreach ([
                $agent,
                $distributor,
                $manager,
                $cashier
            ] as $user) {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $user->id
                ]);
                $user->update(['shop_id' => $shop->id]);
            }
            $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
            for ($i = 0; $i < $users['count']; $i++) {
                $sleep++;
                $number = rand(111111111, 999999999);
                $data = [
                    'username' => $number,
                    'password' => $number,
                    'role_id' => $role->id,
                    'status' => 'Active',
                    'shop_id' => $shop->id,
                    'parent_id' => $cashier->id,
                    'created_at' => time() + $sleep
                ];
                $newUser = \VanguardLTE\User::create($data);
                $newUser->attachRole($role);
                if ($userBalance > 0) {
                    $newUser->addBalance('add', $userBalance, $cashier);
                }
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $newUser->id
                ]);
                $newUser->update(['shop_id' => $shop->id]);
            }
            foreach ([0] as $category) {
                \VanguardLTE\ShopCategory::create([
                    'shop_id' => $shop->id,
                    'category_id' => $category
                ]);
            }
            \VanguardLTE\Task::create([
                'category' => 'shop',
                'action' => 'create',
                'item_id' => $shop->id,
                'shop_id' => auth()->user()->shop_id
            ]);
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_created'));
        }
        public function admin_create()
        {
            if (!auth()->user()->hasRole('admin')) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }
            $directories = [];
            foreach (glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo) {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0,
                'shop_id' => 0
            ])->get();
            $shop = new \VanguardLTE\Shop();
            $availibleUsers = [];
            if (auth()->user()->hasRole('admin')) {
                $me = \VanguardLTE\User::where('id', auth()->user()->id)->get();
                $availibleUsers = \VanguardLTE\User::whereIn('role_id', [
                    4,
                    5
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($availibleUsers);
            }
            $blocks = [];
            if (auth()->user()->hasPermission('shops.unblock')) {
                $blocks[0] = __('app.unblock');
            }
            if (auth()->user()->hasPermission('shops.block')) {
                $blocks[1] = __('app.block');
            }
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            return view('backend.shops.admin', compact('directories', 'categories', 'shop', 'availibleUsers', 'blocks', 'statuses'));
        }
        public function admin_store(\Illuminate\Http\Request $request)
        {
            $sleep = 0;
            $shop = $request->only([
                'name',
                'percent',
                'frontend',
                'orderby',
                'currency',
                'max_win',
                'categories',
                'balance',
                'country',
                'city',
                'os',
                'device',
                'access',
                'shop_limit',
                'rules_terms_and_conditions',
                'rules_privacy_policy',
                'rules_general_bonus_policy',
                'rules_why_bitcoin',
                'rules_responsible_gaming'
            ]);
            $agent = $request->input('agent');
            $distributor = $request->input('distributor');
            $manager = $request->input('manager');
            $cashier = $request->input('cashier');
            $users = $request->input('users');
            if ($this->max_shops <= \VanguardLTE\Shop::count()) {
                return redirect()->back()->with('blockError', 'SHOP')->withErrors([trans('max_shops', ['max' => config('limits.max_shops')])])->withInput();
            }
            $request->validate([
                'name' => 'required|unique:shops|max:255',
                'currency' => 'present|in:' . implode(',', \VanguardLTE\Shop::$values['currency']),
                'orderby' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['orderby'])
            ]);
            foreach ([
                'agent',
                'distributor',
                'manager',
                'cashier'
            ] as $role_name) {
                $validator = \Illuminate\Support\Facades\Validator::make($request->input($role_name), [
                    'username' => 'required|regex:/^[A-Za-z0-9]+$/|unique:users,username',
                    'password' => 'required|min:6'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->with('blockError', $role_name)->withInput();
                }
            }
            $validator = \Illuminate\Support\Facades\Validator::make($users, [
                'count' => 'required',
                'balance' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->with('blockError', 'Users')->withInput();
            }
            $usersBalance = floatval($users['balance'] * $users['count']);
            $distributorBalance = floatval($distributor['balance']);
            $agentBalance = floatval($agent['balance']);
            $shopBalance = floatval($shop['balance']);
            $shop['balance'] = 0;
            $manager['balance'] = $shop['balance'];
            $distributor['balance'] = $manager['balance'];
            $agent['balance'] = $distributor['balance'];
            if ($usersBalance < 0 || $distributorBalance < 0 || $agentBalance < 0 || $shopBalance < 0) {
                return redirect()->back()->withErrors(['Error balance < 0'])->withInput();
            }
            if ($usersBalance > 0 && ($shopBalance <= 0 || $shopBalance < $usersBalance)) {
                return redirect()->back()->withErrors(['Error balance: Users > Shop'])->withInput();
            }
            if ($shopBalance > 0 && ($distributorBalance <= 0 || $distributorBalance < $shopBalance)) {
                return redirect()->back()->withErrors(['Error balance: Manager+shop > Distributor'])->withInput();
            }
            if ($distributorBalance > 0 && ($agentBalance <= 0 || $agentBalance < $distributorBalance)) {
                return redirect()->back()->withErrors(['Error balance: Distributor > Agent'])->withInput();
            }
            $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
            $sleep++;
            $agent = \VanguardLTE\User::create($agent + [
                'parent_id' => auth()->user()->id,
                'role_id' => 5,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $agent->attachRole($roles->find(5));
            $sleep++;
            $distributor = \VanguardLTE\User::create($distributor + [
                'parent_id' => $agent->id,
                'role_id' => 4,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $distributor->attachRole($roles->find(4));
            $sleep++;
            $manager = \VanguardLTE\User::create($manager + [
                'parent_id' => $distributor->id,
                'role_id' => 3,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $manager->attachRole($roles->find(3));
            $sleep++;
            $cashier = \VanguardLTE\User::create($cashier + [
                'parent_id' => $manager->id,
                'role_id' => 2,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $cashier->attachRole($roles->find(2));
            $temp = $request->only([
                'country',
                'os',
                'device'
            ]);
            if (count($temp)) {
                foreach ($temp as $key => $item) {
                    $shop[$key] = implode(',', $item);
                }
            }
            $shop = \VanguardLTE\Shop::create($shop + ['user_id' => $distributor->id]);
            if ($request->country) {
                foreach ($request->country as $country) {
                    \VanguardLTE\ShopCountry::create([
                        'shop_id' => $shop->id,
                        'country' => $country
                    ]);
                }
            }
            if ($request->os) {
                foreach ($request->os as $os) {
                    \VanguardLTE\ShopOS::create([
                        'shop_id' => $shop->id,
                        'os' => $os
                    ]);
                }
            }
            if ($request->device) {
                foreach ($request->device as $device) {
                    \VanguardLTE\ShopDevice::create([
                        'shop_id' => $shop->id,
                        'device' => $device
                    ]);
                }
            }
            $progress = \VanguardLTE\Progress::where('shop_id', 0)->get();
            if (count($progress)) {
                foreach ($progress as $item) {
                    $newProgress = $item->replicate();
                    $newProgress->shop_id = $shop->id;
                    $newProgress->save();
                }
            }
            $welcomebonuses = \VanguardLTE\WelcomeBonus::where('shop_id', 0)->get();
            if (count($welcomebonuses)) {
                foreach ($welcomebonuses as $item) {
                    $newWelcomeBonus = $item->replicate();
                    $newWelcomeBonus->shop_id = $shop->id;
                    $newWelcomeBonus->save();
                }
            }
            $smsbonuses = \VanguardLTE\SMSBonus::where('shop_id', 0)->get();
            if (count($smsbonuses)) {
                foreach ($smsbonuses as $item) {
                    $newSMSBonus = $item->replicate();
                    $newSMSBonus->shop_id = $shop->id;
                    $newSMSBonus->save();
                }
            }
            $open_shift = \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(),
                'balance' => 0,
                'user_id' => $cashier->id,
                'shop_id' => $shop->id
            ]);
            if ($agentBalance > 0) {
                $agent->addBalance('add', $agentBalance);
            }
            if ($distributorBalance > 0) {
                $distributor->addBalance('add', $distributorBalance, $agent);
            }
            if ($shopBalance > 0) {
                $open_shift->increment('balance_in', $shopBalance);
                $distributor->decrement('balance', $shopBalance);
                $shop->increment('balance', $shopBalance);
                \VanguardLTE\Statistic::create([
                    'user_id' => $distributor->id,
                    'shop_id' => $shop->id,
                    'sum' => $shopBalance,
                    'type' => 'add',
                    'system' => 'shop'
                ]);
            }
            foreach ([
                $agent,
                $distributor,
                $manager,
                $cashier
            ] as $user) {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $user->id
                ]);
                $user->update(['shop_id' => $shop->id]);
            }
            $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
            for ($i = 0; $i < $users['count']; $i++) {
                $sleep++;
                $number = rand(111111111, 999999999);
                $data = [
                    'username' => $number,
                    'password' => $number,
                    'role_id' => $role->id,
                    'status' => 'Active',
                    'shop_id' => $shop->id,
                    'parent_id' => $cashier->id,
                    'created_at' => time() + $sleep
                ];
                $newUser = \VanguardLTE\User::create($data);
                $newUser->attachRole($role);
                if ($users['balance'] > 0) {
                    $newUser->addBalance('add', $users['balance'], $cashier);
                }
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $newUser->id
                ]);
                $newUser->update(['shop_id' => $shop->id]);
            }
            if ($request->input('categories') && count($request->input('categories'))) {
                foreach ($request->input('categories') as $category) {
                    \VanguardLTE\ShopCategory::create([
                        'shop_id' => $shop->id,
                        'category_id' => $category
                    ]);
                }
            }
            \VanguardLTE\Task::create([
                'category' => 'shop',
                'action' => 'create',
                'item_id' => $shop->id,
                'shop_id' => auth()->user()->shop_id
            ]);
            \VanguardLTE\GameBank::create([
                'shop_id' => $shop->id
            ]);
            \VanguardLTE\FishBank::create([
                'shop_id' => $shop->id
            ]);

            $g_table = (new \VanguardLTE\Game)->getTable();
            \DB::statement('insert ' . \DB::getTablePrefix() . $g_table . '(name,title, shop_id, jpg_id,label, device,gamebank,chanceFirepot1,chanceFirepot2,chanceFirepot3,fireCount1,fireCount2,fireCount3,lines_percent_config_spin, lines_percent_config_spin_bonus, lines_percent_config_bonus,lines_percent_config_bonus_bonus, rezerv,cask,advanced,bet, scaleMode, slotViewState, view,denomination,category_temp, original_id, bids,stat_in, stat_out) (select name, title, ' . $shop->id . ' as shop_id, jpg_id, label, device,gamebank,chanceFirepot1,chanceFirepot2,chanceFirepot3,fireCount1,fireCount2,fireCount3,lines_percent_config_spin, lines_percent_config_spin_bonus, lines_percent_config_bonus,lines_percent_config_bonus_bonus, rezerv,cask,advanced,bet, scaleMode, slotViewState, view,denomination,category_temp, original_id, bids,stat_in, stat_out from ' . \DB::getTablePrefix() . $g_table . ' where shop_id=0)');
            $jpg_table = (new \VanguardLTE\JPG)->getTable();
            \DB::statement('insert ' . \DB::getTablePrefix() . $jpg_table . '(name,balance,start_balance,pay_sum,percent,shop_id) (select name,balance,start_balance,pay_sum,percent, ' . $shop->id . ' as shop_id from ' . \DB::getTablePrefix() . $jpg_table . ' where shop_id=0)');
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_created'));
        }
        public function edit($shop)
        {
            $shop = \VanguardLTE\Shop::where('id', $shop)->first();
            if (!$shop) {
                abort(404);
            }
            $categories = \VanguardLTE\Category::where(['parent' => 0])->get();
            if (auth()->user()->hasRole([
                'agent',
                'distributor',
                'manager',
                'cashier'
            ])) {
                $ids = \VanguardLTE\ShopUser::where('user_id', auth()->user()->id)->pluck('shop_id')->toArray();
                if (!(count($ids) && in_array($shop->id, $ids))) {
                    abort(404);
                }
            }
            $directories = [];
            foreach (glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo) {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $cats = \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->pluck('category_id')->toArray();
            $blocks = [];
            if (auth()->user()->hasPermission('shops.unblock')) {
                $blocks[0] = __('app.unblock');
            }
            if (auth()->user()->hasPermission('shops.block')) {
                $blocks[1] = __('app.block');
            }
            $activity = \VanguardLTE\Services\Logging\UserActivity\Activity::where([
                'system' => 'shop',
                'item_id' => $shop->id
            ])->take(2)->get();
            return view('backend.shops.edit', compact('shop', 'directories', 'categories', 'cats', 'blocks', 'activity'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository, \VanguardLTE\Shop $shop)
        {
            $user = \VanguardLTE\User::find(auth()->id());
            if (auth()->user()->hasRole([
                'agent',
                'distributor',
                'manager',
                'cashier'
            ])) {
                $ids = \VanguardLTE\ShopUser::where('user_id', auth()->user()->id)->pluck('shop_id')->toArray();
                if (!(count($ids) && in_array($shop->id, $ids))) {
                    abort(404);
                }
            }
            $fields = [
                'is_blocked',
                'currency'
            ];
            if ($user->hasRole('admin')) {
                $fields[] = 'shop_limit';
            }
            if ($user->hasPermission('shops.title')) {
                $fields[] = 'name';
            }
            if ($user->hasPermission('shops.percent')) {
                $fields[] = 'percent';
            }
            if ($user->hasPermission('shops.frontend')) {
                $fields[] = 'frontend';
            }
            if ($user->hasPermission('shops.currency')) {
                $fields[] = 'currency';
            }
            if ($user->hasPermission('shops.order')) {
                $fields[] = 'orderby';
            }
            if ($user->hasPermission('shops.max_win')) {
                $fields[] = 'max_win';
            }

            if ($request->password) {
                // update Cashier password and update shop password
                $fields[] = 'password';
                $user = \VanguardLTE\User::where(['username' => $request->name])->first();
                $user->password = $request->password;
                $user->save();
            }

            $data = $request->only($fields);
            $validatedData = $request->validate([
                'name' => 'sometimes|required|unique:shops,name,' . $shop->id,
                'currency' => 'sometimes|required|in:' . implode(',', \VanguardLTE\Shop::$values['currency']),
                'orderby' => 'sometimes|required|in:' . implode(',', \VanguardLTE\Shop::$values['orderby'])
            ]);
            $shop->update($data);
            \VanguardLTE\ShopCountry::where('shop_id', $shop->id)->delete();
            \VanguardLTE\ShopOS::where('shop_id', $shop->id)->delete();
            \VanguardLTE\ShopDevice::where('shop_id', $shop->id)->delete();

            if (isset($request->categories) && count($request->categories)) {
                \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->delete();
                foreach ($request->categories as $category) {
                    \VanguardLTE\ShopCategory::create([
                        'shop_id' => $shop->id,
                        'category_id' => $category
                    ]);
                }
            }



            if ($request->is_blocked) {
                $users = \VanguardLTE\User::where('shop_id', $shop->id)->whereIn('role_id', [1])->get();
                if ($users) {
                    foreach ($users as $user) {
                        $sessions = $sessionRepository->getUserSessions($user->id);
                        if (count($sessions)) {
                            foreach ($sessions as $session) {
                                $sessionRepository->invalidateSession($session->id);
                            }
                        }
                    }
                }
            }
            $data = $request->only([
                'access',
                'country',
                'os',
                'device'
            ]);
            $users = \VanguardLTE\User::where([
                'shop_id' => $shop->id,
                'role_id' => 1
            ])->get();
            if ($users) {
                foreach ($users as $user) {
                    $activity = \VanguardLTE\Services\Logging\UserActivity\Activity::where([
                        'type' => 'user',
                        'user_id' => $user->id
                    ])->orderBy('id', 'DESC')->first();
                    if ($activity) {
                        foreach ([
                            'countries' => 'country',
                            'oss' => 'os',
                            'devices' => 'device'
                        ] as $index => $item) {
                            if (!count($shop->$index)) {
                                continue;
                            }
                            if (!($shop->access && $shop->$index->filter(function ($value, $key) use ($activity, $item) {
                                return $value->$item == $activity->$item || strpos($activity->$item, $value->$item) !== false;
                            })->count() || !$shop->access && !$shop->$index->filter(function ($value, $key) use ($activity, $item) {
                                return $value->$item == $activity->$item || strpos($activity->$item, $value->$item) !== false;
                            })->count())) {
                                $sessions = $sessionRepository->getUserSessions($user->id);
                                if (count($sessions)) {
                                    foreach ($sessions as $session) {
                                        $sessionRepository->invalidateSession($session->id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_updated'));
        }
        public function get_demo()
        {
            if (!auth()->user()->phone_verified && false) {
                return redirect()->route('backend.user.edit', ['user' => auth()->user()->id])->withErrors([__('app.phone_is_not_verified')]);
            }
            if (auth()->user()->free_demo) {
                return redirect()->back()->withErrors([__('app.only_1_demo')]);
            }
            $sleep = 0;
            $rand = rand(111111111, 999999999);
            $data = [
                'shop' => [
                    'name' => 'DEMO_' . $rand,
                    'percent' => 90,
                    'frontend' => 'Default',
                    'orderby' => 'AZ',
                    'currency' => 'USD',
                    'categories' => [0],
                    'balance' => 100,
                    'max_win' => 100,
                    'shop_limit' => 200
                ],
                'agent' => ['balance' => 150],
                'distributor' => [
                    'username' => 'D_' . $rand,
                    'balance' => 100,
                    'password' => 'D_' . $rand,
                    'status' => 'Active'
                ],
                'manager' => [
                    'username' => 'M_' . $rand,
                    'password' => 'M_' . $rand,
                    'status' => 'Active'
                ],
                'cashier' => [
                    'username' => 'C_' . $rand,
                    'password' => 'C_' . $rand,
                    'status' => 'Active'
                ],
                'users' => [
                    'count' => 10,
                    'balance' => 10
                ]
            ];
            $usersBalance = floatval($data['users']['balance'] * $data['users']['count']);
            $distributorBalance = floatval($data['distributor']['balance']);
            $agentBalance = floatval($data['agent']['balance']);
            $shopBalance = floatval($data['shop']['balance']);
            $data['shop']['balance'] = 0;
            $manager['balance'] = $data['shop']['balance'];
            $data['distributor']['balance'] = $manager['balance'];
            $data['agent']['balance'] = $data['distributor']['balance'];
            if ($usersBalance < 0 || $distributorBalance < 0 || $agentBalance < 0 || $shopBalance < 0) {
                return redirect()->back()->withErrors(['Error balance < 0'])->withInput();
            }
            if ($usersBalance > 0 && ($shopBalance <= 0 || $shopBalance < $usersBalance)) {
                return redirect()->back()->withErrors(['Error balance: Users > Shop'])->withInput();
            }
            if ($shopBalance > 0 && ($distributorBalance <= 0 || $distributorBalance < $shopBalance)) {
                return redirect()->back()->withErrors(['Error balance: Manager+shop > Distributor'])->withInput();
            }
            if ($distributorBalance > 0 && ($agentBalance <= 0 || $agentBalance < $distributorBalance)) {
                return redirect()->back()->withErrors(['Error balance: Distributor > Agent'])->withInput();
            }
            $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
            $sleep++;
            $agent = \VanguardLTE\User::find(auth()->user()->id);
            $distributor = \VanguardLTE\User::create($data['distributor'] + [
                'parent_id' => $agent->id,
                'role_id' => 4,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $distributor->attachRole($roles->find(4));
            $sleep++;
            $manager = \VanguardLTE\User::create($data['manager'] + [
                'parent_id' => $distributor->id,
                'role_id' => 3,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $manager->attachRole($roles->find(3));
            $sleep++;
            $cashier = \VanguardLTE\User::create($data['cashier'] + [
                'parent_id' => $manager->id,
                'role_id' => 2,
                'created_at' => time() + $sleep,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE
            ]);
            $cashier->attachRole($roles->find(2));
            $shop = \VanguardLTE\Shop::create($data['shop'] + ['user_id' => $distributor->id]);
            $progress = \VanguardLTE\Progress::where('shop_id', 0)->get();
            if (count($progress)) {
                foreach ($progress as $item) {
                    $newProgress = $item->replicate();
                    $newProgress->shop_id = $shop->id;
                    $newProgress->save();
                }
            }
            $welcomebonuses = \VanguardLTE\WelcomeBonus::where('shop_id', 0)->get();
            if (count($welcomebonuses)) {
                foreach ($welcomebonuses as $item) {
                    $newWelcomeBonus = $item->replicate();
                    $newWelcomeBonus->shop_id = $shop->id;
                    $newWelcomeBonus->save();
                }
            }
            $smsbonuses = \VanguardLTE\SMSBonus::where('shop_id', 0)->get();
            if (count($smsbonuses)) {
                foreach ($smsbonuses as $item) {
                    $newSMSBonus = $item->replicate();
                    $newSMSBonus->shop_id = $shop->id;
                    $newSMSBonus->save();
                }
            }
            $open_shift = \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(),
                'balance' => 0,
                'user_id' => $cashier->id,
                'shop_id' => $shop->id
            ]);
            if ($agentBalance > 0) {
                $payeer = \VanguardLTE\User::find(1);
                $agent->addBalance('add', $agentBalance, $payeer);
            }
            if ($distributorBalance > 0) {
                $distributor->addBalance('add', $distributorBalance, $agent);
            }
            if ($shopBalance > 0) {
                $open_shift->increment('balance_in', $shopBalance);
                $distributor->decrement('balance', $shopBalance);
                $shop->increment('balance', $shopBalance);
                \VanguardLTE\Statistic::create([
                    'user_id' => $distributor->id,
                    'shop_id' => $shop->id,
                    'sum' => $shopBalance,
                    'type' => 'add',
                    'system' => 'shop'
                ]);
            }
            foreach ([
                $agent,
                $distributor,
                $manager,
                $cashier
            ] as $user) {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $user->id
                ]);
                $user->update(['shop_id' => $shop->id]);
            }
            $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
            for ($i = 0; $i < $data['users']['count']; $i++) {
                $sleep++;
                $number = rand(111111111, 999999999);
                $params = [
                    'username' => $number,
                    'password' => $number,
                    'role_id' => $role->id,
                    'status' => 'Active',
                    'shop_id' => $shop->id,
                    'parent_id' => $cashier->id,
                    'created_at' => time() + $sleep
                ];
                $newUser = \VanguardLTE\User::create($params);
                $newUser->attachRole($role);
                if ($data['users']['balance'] > 0) {
                    $newUser->addBalance('add', $data['users']['balance'], $cashier);
                }
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id,
                    'user_id' => $newUser->id
                ]);
                $newUser->update(['shop_id' => $shop->id]);
            }
            foreach ($data['shop']['categories'] as $category) {
                \VanguardLTE\ShopCategory::create([
                    'shop_id' => $shop->id,
                    'category_id' => $category
                ]);
            }
            \VanguardLTE\Task::create([
                'category' => 'shop',
                'action' => 'create',
                'item_id' => $shop->id,
                'shop_id' => auth()->user()->shop_id
            ]);
            $agent->update(['free_demo' => 1]);
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_created'));
        }
        public function delete($shop)
        {
            $usersWithBalance = \VanguardLTE\User::where('shop_id', $shop)->where('role_id', 1)->where('balance', '>', 0)->count();
            if ($usersWithBalance) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.users_with_balance', ['count' => $usersWithBalance])]);
            }
            $gamesWithBalance = \VanguardLTE\GameBank::where('shop_id', $shop)->where(function ($query) {
                return $query->where('slots', '>', 0)->orWhere('little', '>', 0)->orWhere('table_bank', '>', 0)->orWhere('bonus', '>', 0);
            })->count();
            if ($gamesWithBalance) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.games_with_gamebank', ['count' => $gamesWithBalance])]);
            }
            $gamesWithBalance = \VanguardLTE\FishBank::where('shop_id', $shop)->where('fish', '>', 0)->count();
            if ($gamesWithBalance) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.games_with_gamebank', ['count' => $gamesWithBalance])]);
            }
            $jackpotsWithBalance = \VanguardLTE\JPG::where('shop_id', $shop)->where('balance', '>', 0)->count();
            if ($jackpotsWithBalance) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.jackpots_with_balance', ['count' => $jackpotsWithBalance])]);
            }
            $pincodesWithBalance = \VanguardLTE\Pincode::where('shop_id', $shop)->where('nominal', '>', 0)->count();
            if ($pincodesWithBalance) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.pincodes_with_nominal', ['count' => $pincodesWithBalance])]);
            }
            $shopInfo = \VanguardLTE\Shop::find($shop);
            if ($shopInfo && $shopInfo->balance > 0) {
                return redirect()->route('backend.shop.list')->withErrors([trans('app.shop_balance')]);
            }
            $distributors = \VanguardLTE\User::where('role_id', 4)->whereHas('rel_shops', function ($query) use ($shop) {
                $query->where('shop_id', $shop);
            })->pluck('id')->toArray();
            if (count($distributors)) {
                $distributorsWithBalance = \VanguardLTE\User::whereIn('id', $distributors)->where('balance', '>', 0)->get();
                foreach ($distributorsWithBalance as $distributor) {
                    if (count($distributor->shops()) == 1 && $distributor->shop_id == $shopInfo->id) {
                        return redirect()->route('backend.shop.list')->withErrors([trans('app.distributors_with_balance', ['count' => count($distributorsWithBalance)])]);
                    }
                }
            }
            $item = \VanguardLTE\Shop::find($shop);
            $item->delete();
            \VanguardLTE\Shop::where('id', $shop)->delete();
            \VanguardLTE\ShopUser::where('shop_id', $shop)->delete();
            \VanguardLTE\Statistic::where('shop_id', $shop)->delete();
            \VanguardLTE\StatisticAdd::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopCountry::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopOS::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopDevice::where('shop_id', $shop)->delete();
            \VanguardLTE\Task::create([
                'category' => 'shop',
                'action' => 'delete',
                'item_id' => $shop,
                'shop_id' => auth()->user()->shop_id
            ]);
            $usersToDelete = \VanguardLTE\User::whereIn('role_id', [
                1,
                2,
                3
            ])->where('shop_id', $shop)->get();
            if ($usersToDelete) {
                foreach ($usersToDelete as $userDelete) {
                    $userDelete->delete();
                }
            }
            \VanguardLTE\User::doesntHave('rel_shops')->where('shop_id', '!=', 0)->whereIn('role_id', [
                4,
                5
            ])->update(['shop_id' => 0]);
            $admin = \VanguardLTE\User::where('role_id', 6)->first();
            if ($admin->shop_id == $shop) {
                $admin->update(['shop_id' => 0]);
            }
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_deleted'));
        }
        public function hard_delete($shop)
        {
            $item = \VanguardLTE\Shop::find($shop);
            $item->delete();
            \VanguardLTE\Shop::where('id', $shop)->delete();
            \VanguardLTE\ShopUser::where('shop_id', $shop)->delete();
            \VanguardLTE\Statistic::where('shop_id', $shop)->delete();
            \VanguardLTE\StatisticAdd::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopCountry::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopOS::where('shop_id', $shop)->delete();
            \VanguardLTE\ShopDevice::where('shop_id', $shop)->delete();
            \VanguardLTE\Task::create([
                'category' => 'shop',
                'action' => 'delete',
                'item_id' => $shop,
                'shop_id' => auth()->user()->shop_id
            ]);
            $usersToDelete = \VanguardLTE\User::whereIn('role_id', [
                1,
                2,
                3
            ])->where('shop_id', $shop)->get();
            if ($usersToDelete) {
                foreach ($usersToDelete as $userDelete) {
                    $userDelete->delete();
                }
            }
            \VanguardLTE\User::doesntHave('rel_shops')->where('shop_id', '!=', 0)->whereIn('role_id', [
                4,
                5
            ])->update(['shop_id' => 0]);
            $admin = \VanguardLTE\User::where('role_id', 6)->first();
            if ($admin->shop_id == $shop) {
                $admin->update(['shop_id' => 0]);
            }
            return redirect()->route('backend.shop.list')->withSuccess(trans('app.shop_deleted'));
        }
        public function balance(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if (!array_get($data, 'type')) {
                $data['type'] = 'add';
            }
            $shop = \VanguardLTE\Shop::find($request->shop_id);
            $user = \VanguardLTE\User::find(auth()->user()->id);
            if ($request->all && $request->all == '1') {
                $request->summ = $shop->balance;
            }
            $summ = floatval($request->summ);
            if (!$shop) {
                abort(404);
            }
            if (!$user) {
                return redirect()->back()->withErrors([trans('app.wrong_user')]);
            }
            if (!auth()->user()->hasRole([
                'distributor',
                'manager'
            ])) {
                return redirect()->back()->withErrors([trans('app.only_for_distributors')]);
            }
            if (!$summ || $summ == 0 || $summ < 0) {
                return redirect()->back()->withErrors([trans('app.wrong_sum')]);
            }
            if ($data['type'] == 'add' && $user->balance < $summ) {
                return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_user_balance', [
                    'name' => $user->username,
                    'balance' => $user->balance
                ])]);
            }
            if ($data['type'] == 'out' && $shop->balance < $summ) {
                return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                    'name' => $shop->name,
                    'balance' => $shop->balance
                ])]);
            }
            $sum = ($request->type == 'out' ? -1 * $request->summ : $request->summ);
            \VanguardLTE\Statistic::create([
                'user_id' => auth()->user()->id,
                'shop_id' => $shop->id,
                'type' => $request->type,
                'sum' => abs($sum),
                'system' => 'shop'
            ]);
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => $shop->id,
                'end_date' => null
            ])->first();
            if ($open_shift) {
                if ($request->type == 'out') {
                    $open_shift->increment('balance_out', abs($sum));
                } else {
                    $open_shift->increment('balance_in', abs($sum));
                }
            } else if ($request->type == 'out') {
                \VanguardLTE\OpenShiftTemp::create([
                    'field' => 'balance_out',
                    'value' => abs($sum),
                    'shop_id' => $shop->id
                ]);
            } else {
                \VanguardLTE\OpenShiftTemp::create([
                    'field' => 'balance_in',
                    'value' => abs($sum),
                    'shop_id' => $shop->id
                ]);
            }
            $user->update([
                'balance' => $user->balance - $sum,
                'count_balance' => $user->count_balance - $sum
            ]);
            $shop->update(['balance' => $shop->balance + $sum]);
            return redirect()->back()->withSuccess(trans('app.balance_updated'));
        }
        public function action(\VanguardLTE\Shop $shop, $action)
        {
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => $shop->id,
                'end_date' => null
            ])->first();
            if ($action && in_array($action, [
                'jpg_out',
                'games_out',
                'return_out'
            ])) {
                switch ($action) {
                    case 'jpg_out':
                        $jackpots = \VanguardLTE\JPG::where('shop_id', $shop->id)->get();
                        foreach ($jackpots as $jackpot) {
                            $sum = $jackpot->balance;
                            if ($sum <= 0) {
                                continue;
                            }
                            $jackpot->decrement('balance', abs($sum));
                            $shop->increment('balance', abs($sum));
                            if ($open_shift) {
                                $open_shift->increment('balance_in', abs($sum));
                            } else {
                                \VanguardLTE\OpenShiftTemp::create([
                                    'field' => 'balance_in',
                                    'value' => abs($sum),
                                    'shop_id' => $shop->id
                                ]);
                            }
                            if ($shop->id > 0) {
                                \VanguardLTE\Statistic::create([
                                    'title' => $jackpot->name,
                                    'user_id' => auth()->user()->id,
                                    'system' => 'jpg',
                                    'type' => 'out',
                                    'sum' => abs($sum),
                                    'old' => $sum,
                                    'shop_id' => $shop->id
                                ]);
                            }
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                    case 'games_out':
                        $arr = ['gamebank'];
                        if ($action == 'jpg_out') {
                            $arr = [
                                'jp_1',
                                'jp_2',
                                'jp_3',
                                'jp_4',
                                'jp_5',
                                'jp_6',
                                'jp_7',
                                'jp_8',
                                'jp_9',
                                'jp_10'
                            ];
                        }
                        $games = \VanguardLTE\Game::where('shop_id', $shop->id)->get();
                        foreach ($games as $game) {
                            foreach ($arr as $element) {
                                $sum = $game->$element;
                                if ($sum <= 0) {
                                    continue;
                                }
                                $name = $game->name;
                                if ($element != 'gamebank') {
                                    $name .= (' JP ' . str_replace('jp_', '', $element));
                                }
                                $shop->increment('balance', $sum);
                                if ($open_shift) {
                                    $open_shift->increment('balance_in', abs($sum));
                                } else {
                                    \VanguardLTE\OpenShiftTemp::create([
                                        'field' => 'balance_in',
                                        'value' => abs($sum),
                                        'shop_id' => $shop->id
                                    ]);
                                }
                                if ($action == 'jpg_out') {
                                    $game->update([$element => 0]);
                                } else {
                                    $game->update([$element => 0]);
                                }
                                if ($shop->id > 0) {
                                    \VanguardLTE\Statistic::create([
                                        'title' => $name,
                                        'user_id' => auth()->user()->id,
                                        'system' => 'jpg',
                                        'type' => 'out',
                                        'sum' => $sum,
                                        'old' => $sum,
                                        'shop_id' => $shop->id
                                    ]);
                                }
                            }
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                    case 'return_out':
                        \VanguardLTE\User::where('shop_id', $shop->id)->update(['refunds' => 0]);
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                }
            }
        }
    }
}
