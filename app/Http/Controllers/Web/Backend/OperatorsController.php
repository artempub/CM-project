<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use DataTables;
use Session;
use \Illuminate\Http\Request;

// use App\Http\Traits\RecursiveTrait;

// use VanguardLTE\Lobby;
{
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
    class OperatorsController extends \VanguardLTE\Http\Controllers\Controller
    {
        // use RecursiveTrait;

        private $users = null;
        private $max_operators = 100;
        private $child_users_array = array();
        private $parent_users_array = array();

        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware([
                'auth',
                '2fa'
            ]);
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:users.manage');
        }

        public function get_hierarchy_parents_ids($child_users, $role_id)
        {
            foreach ($child_users as $key => $child_user) {
                if ($child_user->role_id == $role_id) {
                    # add user ID
                    // $this->child_users_array = array_add($this->child_users_array, $key, $child_user->id);
                    array_push($this->child_users_array, $child_user->id);
                }
                if (count($child_user->childs)) {
                    $this->get_hierarchy_parents_ids($child_user->childs, $role_id);
                }
            }
        }
        public function get_upper_parents_ids($parent_user)
        {
            array_push($this->parent_users_array, $parent_user->username);
            if ($parent_user->parent) {
                $this->get_upper_parents_ids($parent_user->parent);
            }
            return $this->parent_users_array;
        }
        //use for new hierarchy
        public function index(\Illuminate\Http\Request $request)
        {

            $user_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
            $operator_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
            $child_users = \VanguardLTE\User::find(isset($request->id)? $request->id : auth()->user()->id)->childs;
            //get all operator username and id
            $all_operator_info = \VanguardLTE\User::where('role_id', $operator_role_id)->get();
            if ($request->ajax()) {

                $this->get_hierarchy_parents_ids($child_users, $user_role_id);
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

                    if ($action_type == 'parent_id' && isset($request->sel_new_parent_operator)) { //15/02/22 for 'Change Family by Admin' logic
                        # change the parent_ids over all selected Operator
                        foreach ($request->sel_rows as  $sel_row_id) {
                            //check if there is the same operator as the selected Operator
                            $sel_operatorId = \VanguardLTE\User::where('id', $sel_row_id)->first()->id;
                            $sel_operator_parentId = \VanguardLTE\User::where('id', $sel_row_id)->first()->parent_id;
                            $sel_new_parent_operator_parentId = \VanguardLTE\User::where('id', $request->sel_new_parent_operator)->first()->parent_id;
                            if ($sel_operatorId != $request->sel_new_parent_operator) {
                                \VanguardLTE\User::where('id', $sel_row_id)->update([$action_type => $request->sel_new_parent_operator]);
                            }
                            if ($sel_operatorId == $sel_new_parent_operator_parentId) {
                                # replace the new parent operator's parentId with sel_operator'parentId
                                \VanguardLTE\User::where('id', $request->sel_new_parent_operator)->update([$action_type => $sel_operator_parentId]);
                            }
                        }
                    } else {
                        foreach ($request->sel_rows as  $sel_row_id) {
                            \VanguardLTE\User::where('id', $sel_row_id)->update([$action_type => $sel_action]);
                        }
                    }
                }

                $users = \VanguardLTE\User::select('id', 'username', 'email', 'balance', 'last_online',  'ip_address', 'enabled', 'panic')->whereIn('id', $this->child_users_array)->get();
                return Datatables::of($users)
                    ->make(true);
            }
            return view('backend.operators.list', compact('all_operator_info'));
        }

        public function show_permissions(\Illuminate\Http\Request $request)
        {
            Session::put('sel_operator_id', $request->user_id);

            $operator_info = \VanguardLTE\Operator::where('user_id', $request->user_id)->first();

            $user_info = \VanguardLTE\User::find($request->user_id);
            $operator_name = $user_info->username;
            $operator_balance = $user_info->balance;
            $operator_lastLogin = $user_info->last_logiin;

            // $currencies = \VanguardLTE\Currency::all();
            // $currencies = array_merge(\VanguardLTE\Shop::$values['currency'], ['ALL']);
            $currencies = \VanguardLTE\Shop::$values['currency'];
            $timezones = \VanguardLTE\Timezones::all();
            #get all parents IDs
            $parent_user = $user_info->parent;
            $parent_username_array = array_reverse($this->get_upper_parents_ids($parent_user));
            array_shift($parent_username_array);
            #check if its parent is Admin
            $flag_1operator = false;
            $categories = [];
            // $providers_deluxecasino_names = [];
            // $providers_vipcasino_names = [];
            // $providers_deluxelivecasino_names = [];
            // $providers_viplivecasino_names = [];
            // $providers_virtual_names = [];
            // $providers_lotto_names = [];
            // $providers_fiable_names = [];
            // $providers_disabled = [];

            #final Category/Provider allocation flow changes on 13/06/22
            $parent_info = \VanguardLTE\User::find($request->user_id)->parent;
            $parent_role = $parent_info->role_id;
            Session::put('sel_operator_parent_id', $parent_info->id);
            if ($parent_role != 0) {
                $flag_1operator = true;

                $categories = \VanguardLTE\Category::where('user_id', $parent_info->id)->get();

                // 04/06/22, Provider allocation logic
                // $providers_deluxecasino_names = [];
                // $providers_vipcasino_names = [];
                // $providers_deluxelivecasino_names = [];
                // $providers_viplivecasino_names = [];
                // $providers_virtual_names = [];
                // $providers_lotto_names = [];
                // $providers_fiable_names = [];

                // $arr_providers_banned = [];
                // $providers_banned_list = \VanguardLTE\Gameproviders_shop::where('user_id', $parent_info->id)->get();
                // if (count($providers_banned_list)) {
                //     foreach ($providers_banned_list as $key => $value) {
                //         array_push($arr_providers_banned, $value->provider_disabled);
                //     }
                // }

                // $providers_deluxecasino = \VanguardLTE\Gameproviders::where('category_id', 1)->groupBy('name')->get();
                // $providers_vipcasino = \VanguardLTE\Gameproviders::where('category_id', 2)->groupBy('name')->get();
                // $providers_deluxelivecasino = \VanguardLTE\Gameproviders::where('category_id', 3)->groupBy('name')->get();
                // $providers_viplivecasino = \VanguardLTE\Gameproviders::where('category_id', 7)->groupBy('name')->get();
                // $providers_virtual = \VanguardLTE\Gameproviders::where('category_id', 4)->groupBy('name')->get();
                // $providers_lotto = \VanguardLTE\Gameproviders::where('category_id', 5)->groupBy('name')->get();
                // $providers_fiable = \VanguardLTE\Gameproviders::where('category_id', 8)->groupBy('name')->get();
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

                // $providers_disabled_list = \VanguardLTE\Gameproviders_shop::where('user_id', $request->user_id)->get();
                // if (count($providers_disabled_list)) {
                //     foreach ($providers_disabled_list as $key => $value) {
                //         array_push($providers_disabled, $value->provider_disabled);
                //     }
                // }
            }

            if ($request->ajax()) {
                if (isset($request->sel_permission)) {
                    //add/remove the permission row to permissions_operator table
                    switch ($request->sel_status) {
                        case 0:
                            # remove the recent permission row
                            \VanguardLTE\Permissions_operator::where('permission_id', $request->sel_permission)
                                ->where('user_id', $request->user_id)
                                ->delete();

                            break;
                        case 1:
                            # enable the permission selected
                            $permission_check = \VanguardLTE\Permissions_operator::where('permission_id', $request->sel_permission)
                                ->where('user_id', $request->user_id);

                            if (!($permission_check->first())) {
                                \VanguardLTE\Permissions_operator::create([
                                    'permission_id' => $request->sel_permission,
                                    'user_id' => $request->user_id
                                ]);
                            }

                            break;
                        default:
                            break;
                    }
                    $array_permissions = \VanguardLTE\Operator_permissions::withCount('per_op')->get();
                    return Datatables::of($array_permissions)
                        ->make(true);
                }
                if ($request->sel_gamecategories) { //game categories allowcation request by Operator
                    //edit the permission row to permissions_operator table
                    // switch ($request->sel_status) {
                    //     case 0:
                    //         # remove the recent category
                    //         \VanguardLTE\Gamecategories_shop::where('gamecategory_id', $request->sel_category)
                    //             ->where('user_id', $request->user_id)
                    //             ->update(['enabled', 0]);

                    //         break;
                    //     case 1:
                    //         # enable the permission selected
                    //         $category_check = \VanguardLTE\Gamecategories_shop::where('gamecategory_id', $request->sel_category)
                    //             ->where('user_id', $request->user_id);

                    //         if (!($category_check->first())) {
                    //             \VanguardLTE\Gamecategories_shop::create([
                    //                 'permission_id' => $request->sel_category,
                    //                 'user_id' => $request->user_id
                    //             ]);
                    //         } else {
                    //             \VanguardLTE\Gamecategories_shop::where('gamecategory_id', $request->sel_category)
                    //                 ->where('user_id', $request->user_id)
                    //                 ->update(['enabled', 1]);
                    //         }
                    //         break;
                    //     default:
                    //         break;
                    // }
                    // $arr_parent_categories = [];
                    // $parent_categories_info = \VanguardLTE\Gamecategories_shop::where(['user_id'=>$parent_info->id, 'enabled'=>1])->get();
                    // if (count($parent_categories_info)) {
                    //     # get the categories IDs allowed from parent Operator
                    //     foreach ($parent_categories_info as $key => $category_info) {
                    //         array_push($arr_parent_categories, $category_info->gamecategory_id);
                    //     }
                    // }
                    // $array_categories = \VanguardLTE\Category::withCount('per_operator')->get();
                    // $array_categories = \VanguardLTE\Shop_gamecategories::whereIn('id', $arr_parent_categories)->withCount('per_operator')->get();
                    $array_categories = [];
                    return Datatables::of($array_categories)
                        ->make(true);
                } else {
                    $array_permissions = \VanguardLTE\Operator_permissions::withCount('per_op')->get();
                    return Datatables::of($array_permissions)
                        ->make(true);
                }
            }

            $users=[];
            if($user_info->role_id == 2){
                $users=\VanguardLTE\User::where('parent_id', $user_info->id)->pluck('id');
            }elseif($user_info->role_id == 10){
                $users=\VanguardLTE\User::where('parent_id', $user_info->parent_id)->pluck('id');
            }else{
                $agents = \VanguardLTE\User::where('role_id', 2)->where('parent_id', $user_info->id)->pluck('id');
                $agents_array = [];
                foreach ($agents as $key => $agent) {
                    array_push($agents_array, $agent);
                }

                $all_agents_id = $this->sub_childs($agents_array);
                $users = \VanguardLTE\User::whereIn('parent_id', $all_agents_id)->pluck('id');
            }
            $users[] = $user_info->id;

            $start_point=date('Y-m-d H:i:s', strtotime('-1 month'));
            $end_point=date('Y-m-d H:i:s');

            $gameLogs = \VanguardLTE\StatGame::orderBy('user_id')
                    ->whereIn('user_id', $users)
                    ->where('date_time', '>', $start_point)
                    ->where('date_time', '<', $end_point)
                    ->get();

            $cashLogs = \VanguardLTE\Transaction::orderBy('from_userId')
                    ->whereIn('from_userId', $users)
                    ->where('created_at', '>', $start_point)
                    ->where('created_at', '<', $end_point)
                    ->get();

            return view('backend.operators.permissions', compact('categories', 'flag_1operator', 'operator_info', 'operator_name', 'operator_balance', 'operator_lastLogin', 'currencies', 'timezones', 'parent_username_array', 'gameLogs', 'cashLogs'));
        }

        function sub_childs($parents_id)
        {
            $childs = \VanguardLTE\User::where('role_id', 2)->whereIn('parent_id', $parents_id)->pluck('id');
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

        public function edit_profile(Request $request)
        {

            $operator_info = \VanguardLTE\Operator::where('user_id', $request->user_id)->first();
            $user_info = \VanguardLTE\User::find($request->user_id);
            $parent_operator_info = $user_info->parent;
            $parent_operator_balance = 0;
            if ($parent_operator_info) {
                $parent_operator_balance = $parent_operator_info->balance;
            }

            if (isset($request->edit_operator_profile)) {
                $data = $request->only([
                    'timezone',
                    'currency',
                    'percentage',
                ]);

                \VanguardLTE\Operator::where('user_id', $request->user_id)->update($data);
                return response()->json([
                    'timezone' => $data['timezone'],
                    'currency' => $data['currency'],
                    'bonus' => $data['percentage'],
                ]);
            }

            if ($request->reset_operator_credits) {
                $user_balance = $user_info->balance;
                $parent_operator_balance += $user_balance;
                $parent_operator_info->balance = $parent_operator_balance;
                $parent_operator_info->save();
                $user_info->update(['balance' => 0]);
                $operator_credits_reset = true;

                $operator_startCredits_reset = false;
                if ($request->reset_operator_startCredits) {
                    $operator_startCredits = $operator_info->start_credit;
                    $parent_operator_balance += $operator_startCredits;
                    $parent_operator_info->balance = $parent_operator_balance;
                    $parent_operator_info->save();
                    \VanguardLTE\Operator::where('user_id', $request->user_id)->update(['start_credit' => 0]);
                    $operator_startCredits_reset = true;
                }
                return response()->json([
                    'operator_credits_reset' => $operator_credits_reset,
                    'operator_startCredits_reset' => $operator_startCredits_reset,
                ]);
            }

            if ($request->operator_credits_in) {

                $parent_operator_balance = 0;
                if ($parent_operator_info) {
                    $parent_operator_balance = $parent_operator_info->balance;
                }
                if ($request->operator_credits_in > $parent_operator_balance && $parent_operator_info->role_id != 6) {

                    return response()->json([
                        'status' => 'lack_operator_credits',
                    ]);
                } else {
                    $parent_operator_balance -= $request->operator_credits_in;
                    \VanguardLTE\User::whereId($parent_operator_info->id)->update(['balance' => $parent_operator_balance]);
                    $operator_balance = $user_info->balance;
                    $operator_balance += $request->operator_credits_in;
                    \VanguardLTE\User::whereId($request->user_id)->update(['balance' => $operator_balance]);

                    $this->create_transaction($parent_operator_info->id, $request->user_id, $request->operator_credits_in, 0);

                    return response()->json([
                        'operator_credits' => $operator_balance,
                    ]);
                }
            } elseif ($request->operator_credits_out) {
                $operator_balance = 0;
                if ($user_info) {
                    $operator_balance = $user_info->balance;
                }
                if ($request->operator_credits_out > $operator_balance) {
                    return response()->json([
                        'status' =>  'lack_user_credits',
                    ]);
                } else {
                    $operator_balance -= $request->operator_credits_out;
                    \VanguardLTE\User::whereId($request->user_id)->update(['balance' => $operator_balance]);
                    $parent_operator_balance = $parent_operator_info->balance;
                    $parent_operator_balance += $request->operator_credits_out;
                    \VanguardLTE\User::whereId($parent_operator_info->id)->update(['balance' => $parent_operator_balance]);

                    $this->create_transaction($request->user_id, $parent_operator_info->id, 0, $request->operator_credits_out);

                    return response()->json([
                        'operator_credits' => $operator_balance,
                    ]);
                }
            } elseif ($request->operator_account_in) {
                $operator_account_limit = $operator_info->account_limit;
                $operator_account_limit += $request->operator_account_in;
                \VanguardLTE\Operator::where('user_id', $request->user_id)->update(['account_limit' => $operator_account_limit]);
                return response()->json([
                    'operator_account_limits' => $operator_account_limit,
                ]);
            } elseif ($request->operator_account_out) {
                $operator_account_limit = $operator_info->account_limit;
                $operator_account_limit -= $request->operator_account_out;
                \VanguardLTE\Operator::where('user_id', $request->user_id)->update(['account_limit' => $operator_account_limit]);
                return response()->json([
                    'operator_account_limits' => $operator_account_limit,
                ]);
            } else {
            }
        }

        public function logged_operator_profile(Request $request)
        {

            $timezones = \VanguardLTE\Timezones::all();
            $user_info = \VanguardLTE\User::find(auth()->user()->id);
            $operator_info = \VanguardLTE\Operator::where('user_id', auth()->user()->id)->first();
            $operator_apikey = $operator_info->api_hash;
            $operator_timezone = $operator_info->timezone;

            return view(
                'backend.operators.profile',
                compact('user_info', 'operator_apikey', 'operator_timezone', 'timezones')
            );
        }

        public function edit_operator_profile(Request $request)
        {
            if ($request->timezone) {
                $operator_info = \VanguardLTE\Operator::where('user_id', auth()->user()->id)->first();
                $operator_info->timezone = $request->timezone;
                $operator_info->save();
                return response()->json([
                    'timezone' => $request->timezone
                ]);
            }
            if ($request->new_password) {
                $user_info = \VanguardLTE\User::find(auth()->user()->id);
                $user_info->password = bcrypt($request->new_password);
                $user_info->save();
                return response()->json([
                    'status' => 'password reset'
                ]);
            }
        }

        public function logged_operator_faq(Request $request)
        {
            return view('backend.operators.faq');
        }

        public function create_transaction($from_userId, $to_userId, $in_amount, $out_amount)
        {

            $trans_info['from_userId'] = $from_userId;
            $trans_info['to_userId'] = $to_userId;
            $trans_info['in_amount'] = $in_amount;
            $trans_info['out_amount'] = $out_amount;
            \VanguardLTE\Transaction::create($trans_info);
        }
    }
}
