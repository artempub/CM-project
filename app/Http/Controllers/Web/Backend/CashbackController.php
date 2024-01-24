<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
		use \VanguardLTE\Cashback;
    class CashbackController extends \VanguardLTE\Http\Controllers\Controller
    {
		
        public function __construct()
        {
            $this->middleware([
                'auth', 
                '2fa'
            ]);
            $this->middleware('permission:access.admin.panel');
            // $this->middleware('permission:welcome_bonuses.manage');
            $this->middleware('shopzero');
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            $cashbacks = \VanguardLTE\Cashback::where('shop_id', \Auth::user()->shop_id)->get();
            return view('backend.cashback.list', compact('cashbacks', 'shop'));
        }
        public function edit(\VanguardLTE\Cashback $cashback)
        {
            if( !in_array($cashback->shop_id, auth()->user()->availableShops()) ) 
            {
                abort(404);
            }
            return view('backend.cashback.edit', compact('cashback'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\Cashback $cashback)
        {
            if( !in_array($cashback->shop_id, auth()->user()->availableShops()) ) 
            {
                abort(404);
            }
            $data = $request->only([
                'pay', 
                'sum', 
                'bonus', 
                'status'
            ]);
            $cashback->update($data);
            return redirect()->route('backend.cashback.list')->withSuccess('Successfully updated');
        }
        public function status($status)
        {
            $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
            // if( $shop && auth()->user()->hasPermission('cashback.edit') ) 
            // {
                if( $status == 'disable' )
                {
                    $shop->update(['cashback_active' => 0]);
                }
                else
                {
                    $shop->update(['cashback_active' => 1]);
                }
            // }
            return redirect()->route('backend.cashback.list')->withSuccess('Successfully updated');
        }
        public function security()
        {
        }
    }

}
