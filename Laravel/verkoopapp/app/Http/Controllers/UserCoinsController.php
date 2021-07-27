<?php

namespace App\Http\Controllers;

use App\User_coins;
use App\User_accounts;
use App\Payments;
use App\User;
use App\Currencies;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use App\Http\Requests\userCoins;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\FriendSendCoinRequest;
use DB;

class UserCoinsController extends Controller
{
    private $notification;

    public function __construct(NotificationController $notification)
    {
        DB::enableQueryLog();
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user_id = Input::get('user_id');
            $data = DB::select("SELECT (CASE WHEN friend_id != 0 THEN (SELECT username FROM users WHERE users.id = user_coins.friend_id) ELSE 'Verkoop' END) as userName, (CASE WHEN friend_id != 0 THEN (SELECT profile_pic FROM users WHERE users.id = user_coins.friend_id) ELSE '' END) as profilePic, user_coins.id, user_coins.type, user_coins.coin,user_coins.created_at FROM `user_coins` WHERE user_id = '$user_id' ORDER BY user_coins.created_at DESC");
            if ($data) {
                $userCoinCheck = $this->userCoinCheck($user_id);
                $coin = isset($userCoinCheck) ? $userCoinCheck->coin : 0;

                return response()->json(['data' => $data, 'coins' => $coin, 'message' => 'Data get successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'coins' => 0, 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(userCoins $request)
    {
        try {
            $user_id = $request->user_id;
            $coin_plan_id = $request->coin_plan_id;
            $coin = $request->coin;
            $res = $this->coinAdd($user_id, $coin_plan_id, 0, $coin, 0);
            if ($res) {
                $userCoinCheck = $this->userCoinCheck($user_id);
                if ($userCoinCheck) {
                    $this->userCoinUpdate($request, $user_id);
                } else {
                    $this->userCoinInsert($request);
                }
                $this->userCoinAmount($request);

                return response()->json(['message' => 'Coin purchase successfully.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User_coins $user_coins
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User_coins $user_coins)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User_coins $user_coins
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User_coins $user_coins)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User_coins          $user_coins
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User_coins $user_coins)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User_coins $user_coins
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User_coins $user_coins)
    {
    }

    public function sendFriendCoins(FriendSendCoinRequest $request)
    {
        try {
            $type = 2;
            $types = 3;
            $coin = $request->coin;
            $coin_plan_id = 0;
            $user_id = $request->qrCodetoUserId;
            $this->userCoinUpdate($request, $user_id);
            $this->userCoinDecrease($request, $request->user_id);
            $this->coinAdd($request->user_id, $coin_plan_id, $type, $coin, $user_id);
            $this->coinAdd($user_id, $coin_plan_id, $types, $coin, $request->user_id);
            $userDeviceInfo = User::getDeviceInfo($user_id);
            $userData = User::searchUserName($user_id);
            $message = "Sent you $coin coins";
            $data['title'] = ucfirst($userData->username);
            $message_type = 5;
            $this->notification->notificationSend($userDeviceInfo->device_type, $userDeviceInfo->device_id, $message, $message_type, $data);

            return response()->json(['message' => 'Coin sent successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function userCoinCheck($user_id)
    {
        return User_accounts::where('user_id', $user_id)->first();
    }

    public static function userCoinUpdate($req, $user_id)
    {
        return User_accounts::where('user_id', $user_id)->increment('coin', $req->coin);
    }

    public static function userCoinInsert($req)
    {
        $User_accounts = new User_accounts();
        $User_accounts->user_id = $req->user_id;
        $User_accounts->coin = $req->coin;

        return $User_accounts->save();
    }

    public static function userCoinDecrease($req, $user_id)
    {
        return User_accounts::where('user_id', $user_id)->decrement('coin', $req->coin);
    }

    public function userCoinAmount($req)
    {
        $user_id = $req->user_id;
        $amount = $req->amount;

        $user = User::Where('id', $req->user_id)->first();
        if($user && $user->country_code){
            $currency = isset($user->currency) && isset($user->currency->code) ? $user->currency->code : "ZAR";
        }else{
            $currency = "ZAR";
        }

        $from = "ZAR";
        $to = $currency;
        $input = $req->amount;
        if($from == '' || $to == '' || $from == $to){
            $totalAmount =  $input;
        }else{
           $fromCurrency = Currencies::where('code', $from)->first();
           $toCurrency = Currencies::where('code', $to)->first();
           $fromRate = max([$fromCurrency->rate1,$fromCurrency->rate2]);
           $toRate = max([$toCurrency->rate1,$toCurrency->rate2]);
           $convert_rate = $toRate / $fromRate;
           $output = $convert_rate * $input;
           $totalAmount =  number_format($output,2);  
        }
        User_accounts::where('user_id', $req->user_id)->decrement('amount', $totalAmount);

        return $this->detectAmount($user_id, $totalAmount, $currency);
    }

    public static function detectAmount($user_id, $amount, $currency)
    {
        $payment = new Payments();
        $payment->user_id = $user_id;
        $payment->amount = $amount;
        $payment->currency = $currency;
        $payment->status = 1;
        $payment->type = 1;

        return $payment->save();
    }

    public static function coinAdd($user_id, $coin_plan_id = 0, $type = 0, $coin, $friendId = 0)
    {
        $user_coins = new User_coins();
        $user_coins->user_id = $user_id;
        $user_coins->coin_plan_id = $coin_plan_id;
        $user_coins->coin = $coin;
        $user_coins->type = $type;
        $user_coins->friend_id = $friendId;

        return $res = $user_coins->save();
    }
}
