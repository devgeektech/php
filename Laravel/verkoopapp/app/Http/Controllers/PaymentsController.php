<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendSendCoinRequest;
use App\Payments;
use App\User_accounts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\paymentRequest;
use App\NotificationActivity;
use App\User;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use DB;

class PaymentsController extends Controller
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
            $payments = Payments::where(['user_id' => $user_id])->where('status', 1)->orderBy('created_at', 'DESC')->get();
            if (count($payments) > 0) {
                $userCoinCheck = $this->userCoinCheck($user_id);
                $amount = isset($userCoinCheck) ? $userCoinCheck->amount : 0;

                return response()->json(['data' => $payments, 'amount' => sprintf('%0.2f', $amount), 'message' => 'Data get successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'amount' => 0, 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function store(paymentRequest $request)
    {
        try {
            $user_id = $request->user_id;
            $amount = $request->amount;
            $currency = $request->currency;
            $token = $request->token;
            $transaction = $this->chargeUserCc($token, $currency, $amount);
            $payment = new Payments();
            $payment->user_id = $user_id;
            $payment->amount = $amount;
            $payment->currency = $currency;
            if ($transaction['status'] == 200) {
                $payment->charge_id = $transaction['charge_id'];
                $payment->status = 1;
                $payment->save();
                $userCoinCheck = $this->userCoinCheck($user_id);
                if ($userCoinCheck) {
                    $this->userCoinUpdate($request, $user_id);
                } else {
                    $this->userCoinInsert($request);
                }

                return response()->json(['message' => 'Payment success.'], Response::HTTP_OK);
            } else {
                $payment->status = 0;
                $payment->save();

                return response()->json(['message' => $transaction['api_msg']], $transaction['status']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Payments $payments
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Payments $payments)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Payments $payments
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Payments $payments)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Payments            $payments
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payments $payments)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Payments $payments
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payments $payments)
    {
    }

    public static function userCoinCheck($user_id)
    {
        return User_accounts::where('user_id', $user_id)->first();
    }

    public static function userCoinUpdate($req, $user_id)
    {
        return User_accounts::where('user_id', $user_id)->increment('amount', $req->amount);
    }

    public static function userCoinInsert($req)
    {
        $User_accounts = new User_accounts();
        $User_accounts->user_id = $req->user_id;
        $User_accounts->amount = $req->amount;
        $User_accounts->coin = 0;

        return $User_accounts->save();
    }

    public function sendMoney(FriendSendCoinRequest $request)
    {
        try {
            $userAmount = User_accounts::where('user_id', $request->user_id)->first();
            $totalAmount = isset($userAmount) ? $userAmount->amount : 0;
            if ($totalAmount < $request->amount) {
                return response()->json(['message' => 'Insufficient funds in wallet'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $amount = $request->amount;
            $user_id = $request->qrCodetoUserId;
            $this->userAmountUpdate($request, $user_id);
            $this->userAmountDecrease($request, $request->user_id);
            $userDeviceInfo = User::getDeviceInfo($user_id);
            $userData = User::searchUserName($request->user_id);
            $message = "Sent you ".$currency." ".$amount;
            $message_type = 5;
            $data['title'] = ucfirst($userData->username);
            $send = $this->notification->notificationSend($userDeviceInfo->device_type, $userDeviceInfo->device_id, $message, $message_type, $data);
            if ($send) {
                $notiActivity = new NotificationActivity();
                $notiActivity->title = ucfirst($userData->username);
                $notiActivity->message = $message;
                $notiActivity->type = $message_type;
                $notiActivity->from = $request->user_id;
                $notiActivity->to = $user_id;
                $notiActivity->save();
            }

            return response()->json(['message' => 'Amount sent successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function userAmountUpdate($req, $user_id)
    {
        $amount = User_accounts::firstOrNew(['user_id' => $user_id])->increment('amount', $req->amount);
        if ($amount) {
            $pay = new Payments();
            $pay->user_id = $user_id;
            $pay->amount = $req->amount;
            $pay->status = 1;
            $pay->type = 0;
            if ($pay->save()) {
                return true;
            }
        }

        return false;
    }

    public static function userAmountDecrease($req, $user_id)
    {
        $amount = User_accounts::firstOrNew(['user_id' => $user_id])->decrement('amount', $req->amount);
        if ($amount) {
            $pay = new Payments();
            $pay->user_id = $user_id;
            $pay->amount = $req->amount;
            $pay->status = 1;
            $pay->type = 1;
            if ($pay->save()) {
                return true;
            }
        }

        return false;
    }

    /* Function to charge user's credit card*/
    public static function chargeUserCc($source_token, $currency, $amount, $stripe_id = null)
    {
        $error = trans('messages.error.general_error');
        $success = false;
        try {
            /* Check if credit card is linked with stripe account */
            if ($source_token != null) {
                $charge = Stripe::charges()->create([
                  'source' => $source_token,
                  'currency' => $currency,
                  'amount' => $amount,
              ]);
            } else {
                $charge = Stripe::charges()->create([
                  'customer' => $stripe_id,
                  'currency' => $currency,
                  'amount' => $amount,
              ]);
            }
            $status = 200;
            $success = true;
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (\Cartalyst\Stripe\Exception\BadRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
            // Authentication with Stripe's API failed
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            // Network communication with Stripe failed
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            //  Missing source parameter
            $error = $e->getMessage();
            $status = $e->getCode();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error = $e->getMessage();
            $status = $e->getCode();
        }

        if ($success) {
            $charge_id = $charge['id'];
            $last4 = $charge['source']['last4'];
            $brand = $charge['source']['brand'];
            $data['last4'] = $last4;
            $data['brand'] = $brand;
            $data['status'] = $status;
            $data['charge_id'] = $charge_id;
        } else {
            $data['status'] = $status;
            $data['msg'] = $data['api_msg'] = $error;
        }

        return $data;
    }
}
