<?php

namespace App\Http\Controllers;

use App\User_purchase_advertisement;
use App\Advertisement_plans;
use App\User_accounts;
use App\User_coins;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\userPurchaseAdvertisementRequest;
use Illuminate\Support\Facades\Input;
use Exception;

class UserPurchaseAdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user_id = Input::get('user_id');
            $data = User_purchase_advertisement::select(['user_purchase_advertisement.id', 'image', 'user_purchase_advertisement.updated_at', 'day', 'advertisement_plan_id', 'category_id', \DB::raw('(CASE 
            WHEN user_purchase_advertisement.approved_at < now() THEN 2 
            ELSE user_purchase_advertisement.status
            END) AS status')])->join('advertisement_plans', 'advertisement_plans.id', '=', 'user_purchase_advertisement.advertisement_plan_id')->where('user_id', $user_id)
                            ->orderBy('user_purchase_advertisement.updated_at', 'DESC')
                            ->get();
            if ($data) {
                return response()->json(['data' => $data, 'message' => 'Data get successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_OK);
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
    public function store(userPurchaseAdvertisementRequest $request)
    {
        try {
            $fileUpload = '';
            if ($request->hasfile('banner')) {
                $images = $request->file('banner');
                $filename = trim(time().$images->getClientOriginalName());
                $images = $images->move(public_path().'/images/advertisments/', $filename);
                $fileUpload = 'public/images/advertisments/'.$filename;
            }
            $plan = Advertisement_plans::find($request->advertisement_plan_id);
            $user_purchase_advertisement = new User_purchase_advertisement();
            $user_purchase_advertisement->user_id = $request->user_id;
            $user_purchase_advertisement->advertisement_plan_id = $request->advertisement_plan_id;
            $user_purchase_advertisement->category_id = $request->category_id;
            $user_purchase_advertisement->valid_upto = date('Y-m-d H:i:s', strtotime('+'.$plan->day.' days', time()));
            $user_purchase_advertisement->image = $fileUpload;
            $res = $user_purchase_advertisement->save();
            if ($res) {
                $this->userCoinDetection($request->user_id, $request->coin);

                return response()->json(['message' => 'Banner added successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Banner added failed.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User_purchase_advertisement $user_purchase_advertisement
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User_purchase_advertisement $user_purchase_advertisement)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User_purchase_advertisement $user_purchase_advertisement
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User_purchase_advertisement $user_purchase_advertisement)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request         $request
     * @param \App\User_purchase_advertisement $user_purchase_advertisement
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User_purchase_advertisement $user_purchase_advertisement)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User_purchase_advertisement $user_purchase_advertisement
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = User_purchase_advertisement::find($id);
        if ($ad) {
            if ($ad->delete()) {
                return response()->json(['message' => 'Advertisement deleted successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Something went wrong, please try again later'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(['message' => 'Advertisement not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function userCoinDetection($user_id, $coin)
    {
        User_accounts::where('user_id', $user_id)->decrement('coin', $coin);

        return $this->coinAdd($user_id, 0, 1, $coin, 0);
    }

    public static function coinAdd($user_id, $coin_plan_id = 0, $type = 1, $coin, $friendId = 0)
    {
        $user_coins = new User_coins();
        $user_coins->user_id = $user_id;
        $user_coins->coin_plan_id = $coin_plan_id;
        $user_coins->coin = $coin;
        $user_coins->type = $type;
        $user_coins->friend_id = $friendId;

        return $res = $user_coins->save();
    }

    public function renewAd(Request $request)
    {
        try {
            $ad_id = $request->banner_id;
            $plan_id = $request->advertisement_plan_id;
            $user_purchase_advertisement = User_purchase_advertisement::find($ad_id);
            if (!$user_purchase_advertisement) {
                return response()->json(['message' => 'Advertisement not found'], Response::HTTP_NOT_FOUND);
            }
            $plan = Advertisement_plans::find($plan_id);
            $userCoin = User_accounts::where('user_id', $user_purchase_advertisement->user_id)->first();
            if ($plan->coin > $userCoin->coin) {
                return response()->json(['message' => 'Insufficient coins'], Response::HTTP_BAD_REQUEST);
            }
            $user_purchase_advertisement->renewed_at = date('Y-m-d H:i:s');
            $user_purchase_advertisement->valid_upto = date('Y-m-d H:i:s', strtotime('+'.$plan->day.' days', time()));
            $user_purchase_advertisement->advertisement_plan_id = $plan_id;
            $user_purchase_advertisement->status = 1;
            $res = $user_purchase_advertisement->save();
            if ($res) {
                $this->userCoinDetection($user_purchase_advertisement->user_id, $plan->coin);

                return response()->json(['message' => 'Banner renewed successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Banner renew failed.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
