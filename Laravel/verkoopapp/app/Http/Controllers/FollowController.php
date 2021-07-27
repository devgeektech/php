<?php

namespace App\Http\Controllers;

use App\follow;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\createfollow;
use App\NotificationActivity;
use Exception;
use Illuminate\Http\Response;
use DB;

class FollowController extends Controller
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
    public function store(createfollow $request)
    {
        try {
            $user_id = $request['user_id'];
            $follower_id = $request['follower_id'];
            $findData = follow::where(['user_id' => $user_id, 'follower_id' => $follower_id])->first();
            if ($findData) {
                return response()->json(['message' => 'Already follow.'], Response::HTTP_FOUND);
            }
            $req = new follow();
            $req['follower_id'] = (int) $follower_id;
            $req['user_id'] = (int) $user_id;
            $req->save();
            if ($req) {
                $userDeviceInfo = User::getDeviceInfo($follower_id);
                $userData = User::searchUserName($user_id);
                $message = 'Starts following you';
                $message_type = 2;
                $data['user_id'] = $user_id;
                $data['title'] = ucfirst($userData->username);
                $noti = $this->notification->notificationSend($userDeviceInfo->device_type, $userDeviceInfo->device_id, $message, $message_type, $data);

                if ($noti) {
                    $notiActivity = new NotificationActivity();
                    $notiActivity->title = ucfirst($userData->username);
                    $notiActivity->message = $message;
                    $notiActivity->type = $message_type;
                    $notiActivity->from = $user_id;
                    $notiActivity->to = $follower_id;
                    if ($notiActivity->save()) {
                        $req->notification_id = $notiActivity->id;
                        $req->save();
                    }
                }

                return response()->json(['message' => 'User is followed successfully.', 'data' => $req], Response::HTTP_CREATED);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\follow $follow
     *
     * @return \Illuminate\Http\Response
     */
    public function show(follow $follow)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\follow $follow
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(follow $follow)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\follow              $follow
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, follow $follow)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\follow $follow
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $follow = follow::find($id);
            $delete = $follow->delete();
            if ($delete) {
                return response()->json(['message' => 'User is unfollowed successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_followData(int $id, Request $request)
    {
        try {
            $data = $request->type == 1 ? $this->get_follower_userData($id) : $this->get_follow_userData($id);

            return response()->json(['message' => 'Get user list successfully.', 'data' => $data], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function get_follower_userData(int $id)
    {
        return DB::select(
                'call get_follower_user("'.$id.'")'
            );
    }

    public static function get_follow_userData(int $id)
    {
        return DB::select(
                'call get_follow_user("'.$id.'")'
            );
    }
}
