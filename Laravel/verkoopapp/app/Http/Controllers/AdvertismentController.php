<?php

namespace App\Http\Controllers;

use App\Advertisement_plans;
use App\User;
use App\User_accounts;
use App\User_coins;
use App\User_purchase_advertisement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdvertismentController extends Controller
{
    public function getAllAdBanners()
    {
        $banners = User_purchase_advertisement::getAllBanners();
        $data = array();
        foreach ($banners as $b) {
            $ban = array();
            $ban['b_id'] = $b['id'];
            $ban['image'] = $b['image'] ? asset('/').$b['image'] : asset('public/images/image-placeholder.png');
            $ban['name'] = $b['name'];
            $ban['plan'] = $b['plan'];
            $ban['status'] = $b['status'];
            $ban['validity'] = $b['valid_upto'] ? $b['valid_upto'] : 'NA';
            $ban['renewed'] = $b['renewed_at'] ? $b['renewed_at'] : 'NA';
            $ban['created'] = $b['created_at'];
            array_push($data, $ban);
        }

        return view('admin/banners', ['banners' => $data]);
    }

    public function updateBannerStatus(Request $request)
    {
        $b_id = $request->b_id;
        $status = $request->status;
        $banner = User_purchase_advertisement::find($b_id);
        $plan = Advertisement_plans::find($banner->advertisement_plan_id);
        if ($status == 1) {
            $banner->status = 1;
            $banner->approved_at = date('Y-m-d H:i:s');
            $banner->valid_upto = date('Y-m-d H:i:s', strtotime('+'.$plan->day.' days', time()));
        } else {
            $banner->status = 3;
            $banner->approved_at = null;
        }
        if ($banner->save()) {
            $msg = 'Advertisement denied';
            if ($banner->status == 1) {
                $msg = 'Advertisement approved';
            } else {
                $this->userCoinRefund($banner->user_id, $plan->coin);
                $subject = 'Banner Advertisement Rejected';
                $template = 'banner-rejected.txt';
                $user = User::find($banner->user_id);
                $email = $user->email;
                $name = $user->username;
                $content = $request->content;
                $hooks = array(
                          'searchStrs' => array('#NAME#', '#CONTENT#'),
                          'subjectStrs' => array($name, $content),
                          );
                $classCtrl = app()->make(\App\Helpers\EmailHelper::class);
                if ($classCtrl->newTemplateMsg($template, $hooks)) {
                    if ($classCtrl->sendMail($email, $subject)) {
                        $msg .= ' and email sent successfully';
                    } else {
                        $msg .= ' but email failed';
                    }
                }
            }

            return response()->json(['message' => $msg, 'validity' => $banner->valid_upto], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Something went wrong, please try again later'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function userCoinRefund($user_id, $coin)
    {
        User_accounts::where('user_id', $user_id)->increment('coin', $coin);

        return $this->coinAdd($user_id, 0, 0, $coin, 0);
    }

    public static function coinAdd($user_id, $coin_plan_id = 0, $type = 1, $coin, $friendId = 0)
    {
        $user_coins = new User_coins();
        $user_coins->user_id = $user_id;
        $user_coins->coin_plan_id = $coin_plan_id;
        $user_coins->coin = $coin;
        $user_coins->type = $type;
        $user_coins->friend_id = $friendId;

        return $user_coins->save();
    }
}
