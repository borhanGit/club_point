<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Product;
use App\Models\ClubPointSystem;
use App\Models\ConvertWallet;
use App\Models\Order;
use App\Models\OrderDetail;
use Auth;
use Illuminate\Support\Facades\DB;
use Session;

class WalletController extends Controller
{
    public function index()
    {
        // $wallets = ConvertWallet::where('user_id', Auth::user()->id)->sum('money');
        $wallets = ConvertWallet::where('user_id', Auth::user()->id)->where('status',0)->orWhere('status',2)->orWhere('product_id',null)->sum('money');
        return view('frontend.user.wallet.index', compact('wallets'));
    }

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
        if (class_exists($decorator)) {
            return (new $decorator)->pay($request);
        }
    }

    public function wallet_payment_done($payment_data, $payment_details)
    {
        $user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(translate('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge(Request $request)
    {
        $wallet = new Wallet;
        $wallet->user_id = Auth::user()->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = $request->payment_option;
        $wallet->payment_details = $request->trx_id;
        $wallet->approval = 0;
        $wallet->offline_payment = 1;
        $wallet->reciept = $request->photo;
        $wallet->save();
        flash(translate('Offline Recharge has been done. Please wait for response.'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge_request()
    {
        $wallets = Wallet::where('offline_payment', 1)->paginate(10);
        return view('manual_payment_methods.wallet_request', compact('wallets'));
    }

    public function updateApproved(Request $request)
    {
        $wallet = Wallet::findOrFail($request->id);
        $wallet->approval = $request->status;
        if ($request->status == 1) {
            $user = $wallet->user;
            $user->balance = $user->balance + $wallet->amount;
            $user->save();
        } else {
            $user = $wallet->user;
            $user->balance = $user->balance - $wallet->amount;
            $user->save();
        }
        if ($wallet->save()) {
            return 1;
        }
        return 0;
    }
    // ADMIN WALLET SYSTEM
     public function wallet_dashboard()
    {
        return view('backend.wallet.index');
    }
    public function wallet_activation()
    {
        Session::put('wallet','on');
        return view('backend.wallet.index');
    }
    public function wallet_dactivation()
    {

        Session::forget('wallet');
        return view('backend.wallet.index');
    }
    // ADMIN CULB POINT
    public function club_system()
    {
        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);
        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.club.index',compact('products'));
    }
    public function point_store(Request $request)
    {

        $products = Product::all();
        try{
            foreach($products as $product)
            {
                    Product::where('id',$product->id)->update([
                        'club_point' => $request->points
                    ]);
            }

            flash('Club point store has been inserted successfully')->success();
            return back();
        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }
    public function point_convert_wallet(Request $request)
    {

            ClubPointSystem::create([
                'points'=> $request->points
            ]);
            flash('Club point convert wallet successfully')->success();
            return back();
    }
    // USER EARNING POINTS
     public function userpoint_index()
     {
            $user = Auth::user();
            $getData =OrderDetail::with(['product','order'])->get();

            $points = ClubPointSystem::latest()->first();
        // dd($getData->order());

            return view('frontend.user.earning_point',compact('getData','points'));
     }
     public function convert_point_into_wallet($point,$productId,$orderDId)
     {
            $club_point_system = ClubPointSystem::latest()->first();
            $calPoint= $point/$club_point_system->points;
            $calMoney= $club_point_system->amount*$calPoint;
            $userId = Auth::user()->id;
            $convertWallet = new ConvertWallet();
            $convertWallet->user_id = $userId;
            $convertWallet->product_id = $productId;
            $convertWallet->money = $calMoney;
            $convertWallet->save();
            DB::table('order_details')
                ->where('id', $orderDId)
                ->update([
                    'convert'     => 1
                ]);
            flash('Points convert wallet successfully')->success();
            return back();

     }
}
