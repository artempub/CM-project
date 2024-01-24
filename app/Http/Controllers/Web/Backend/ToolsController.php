<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use Session;
use DataTables; {
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
    class CashController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        private $child_shop_arrays = array();

        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware([
                'auth',
                '2fa'
            ]);
            $this->middleware('permission:access.admin.panel');
            $this->users = $users;
        }
        public function index(\Illuminate\Http\Request $request)
        {

            $operator_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
            $shop_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'shop')->first()->id;

            $operators = \VanguardLTE\User::where(['role_id' => $operator_role_id, 'parent_id' => auth()->user()->id])->get();
            // $currencies = \VanguardLTE\Currency::all();
            // $currencies = array_merge(\VanguardLTE\Shop::$values['currency'], ['ALL']);
            $currencies = \VanguardLTE\Shop::$values['currency'];

            // $query_shops = "Select temp.*, u1.username From (SELECT wt.*, wu.parent_id, wu.username as child_name FROM `w_transaction` as wt LEFT JOIN w_users as wu on wt.from_userId = wu.id GROUP BY wt.from_userId) as temp LEFT JOIN w_users as u1 on temp.parent_id = u1.id";
            //set the default options
            $_start_date = date('Y-m-d H:i:s', strtotime(now()));
            $_end_date = date('Y-m-d H:i:s', strtotime(now()));
            Session::put('startdate', $_start_date);
            Session::put('enddate', $_end_date);
            Session::put('operator_id', auth()->user()->id);
            Session::put('currency', 'ALL');

            if (isset($request->startdate)) {
                //change date range
                $start_date = date('Y-m-d H:i:s', strtotime($request->startdate));
                $end_date = date('Y-m-d H:i:s', strtotime($request->enddate));
                Session::put('startdate', $start_date);
                Session::put('enddate', $end_date);
            }
            if (isset($request->operator_id)) {
                Session::put('operator_id', $request->operator_id);
            }
            if (isset($request->currency)) {
                Session::put('currency', $request->currency);
            }

            //get all possible Shoops ids to show corresponding Shops per Operator network
            $child_users = \VanguardLTE\User::find(Session::get('operator_id'))->childs;
            $this->get_hierarchy_parents_ids($child_users, $shop_role_id);

            if (Session::get('startdate') == Session::get('enddate')) { //now()
                # get today's trans only
                if (Session::get('currency') == 'ALL') {
                    # get all currency trans
                    $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->whereYear('created_at', '>=', now()->year)
                        ->whereMonth('created_at', '>=', now()->month)
                        ->whereDay('created_at', '>=', now()->day)
                        ->groupBy('currency')
                        ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                        ->get();

                    $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->groupBy('currency')
                        ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                        ->get();
                } else {
                    # get specific currency only
                    $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->whereYear('created_at', '>=', now()->year)
                        ->whereMonth('created_at', '>=', now()->month)
                        ->whereDay('created_at', '>=', now()->day)
                        ->where('currency', Session::get('currency'))
                        ->groupBy('from_userId')
                        ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                        ->get();

                    $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->where('currency', Session::get('currency'))
                        ->groupBy('from_userId')
                        ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                        ->get();
                }
            } else {
                # get trans for the date range
                // var_dump(Session::get('startdate'),Session::get('enddate'), Session::get('currency'), $this->child_shop_arrays);
                if (Session::get('currency') == 'ALL') {
                    $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->whereBetween('created_at', [Session::get('startdate'), Session::get('enddate')])
                        ->groupBy('currency')
                        ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                        ->get();

                    $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->groupBy('currency')
                        ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                        ->get();
                } else {
                    $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->whereBetween('created_at', [Session::get('startdate'), Session::get('enddate')])
                        ->where('currency', Session::get('currency'))
                        ->groupBy('from_userId')
                        ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                        ->get();

                    $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                        ->where('currency', Session::get('currency'))
                        ->groupBy('from_userId')
                        ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                        ->get();
                }
            }
            $recent_operator_id = Session::get('operator_id');
            $recent_currency = Session::get('currency');

            return view('backend.cash.index', compact('operators', 'currencies', 'shops_all', 'shops_last_reset', 'recent_operator_id', 'recent_currency'));
        }

        public function get_hierarchy_parents_ids($child_users, $role_id)
        {
            foreach ($child_users as $key => $child_user) {
                if ($child_user->role_id == $role_id) {
                    # add user ID
                    array_push($this->child_shop_arrays, $child_user->id);
                }
                if (count($child_user->childs)) {
                    $this->get_hierarchy_parents_ids($child_user->childs, $role_id);
                }
            }
        }
    }
}
