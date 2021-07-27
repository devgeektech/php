<?php

namespace App\Http\Controllers;

use App\like;
use Illuminate\Http\Request;
use App\Http\Requests\ItemLikesRequest;
use Exception;
use Illuminate\Http\Response;
use App\Items;
use App\NotificationActivity;
use App\User;
use DB;

class LikeController extends Controller
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
    public function store(ItemLikesRequest $request)
    {
        try {
            $check = like::where($request->all())->first();
            if (empty($check)) {
                $like = new like($request->all());
                $save = $like->save();
                if ($save) {
                    $user_id = $request->user_id;
                    $item_id = $request->item_id;
                    $totalLike = like::join('items', 'items.id', '=', 'items_like.item_id')->where('items.id', '=', $item_id)->count();
                    $userData = User::searchUserName($user_id);
                    $itemData = Items::find($item_id);
                    $message = 'Liked your product '.ucfirst($itemData->name);
                    $type = 3;
                    $data['item_id'] = $item_id;
                    $data['title'] = ucfirst($userData->username);
                    $noti = $this->notification->getDeviceInfo($item_id, $message, $type, '', $data);

                    if ($noti) {
                        $notiActivity = new NotificationActivity();
                        $notiActivity->title = ucfirst($userData->username);
                        $notiActivity->message = $message;
                        $notiActivity->type = $type;
                        $notiActivity->from = $user_id;
                        $notiActivity->to = $itemData->user_id;
                        if ($notiActivity->save()) {
                            $like->notification_id = $notiActivity->id;
                            $like->save();
                        }
                    }

                    return response()->json(['message' => 'Like add successfully.', 'like_id' => (int) $like->id, 'totalCount' => (int) $totalLike], Response::HTTP_CREATED);
                } else {
                    return response()->json(['message' => 'Like added failed.'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json(['message' => 'Already Liked.'], Response::HTTP_ALREADY_REPORTED);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\like $like
     *
     * @return \Illuminate\Http\Response
     */
    public function show(like $like)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\like $like
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(like $like)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\like                $like
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, like $like)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\like $like
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(like $like)
    {
        try {
            $likes = like::find($like->id);
            $delete = $likes->delete();
            if ($delete) {
                $totalLike = like::join('items', 'items.id', '=', 'items_like.item_id')->where('items.id', '=', $likes->item_id)->count();

                return response()->json(['message' => 'Like delete successfully.', 'totalCount' => $totalLike], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Like deleted failed.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDeviceInfo(Int $user_id, Int $item_id, $userData)
    {
        $data = Items::select(['device_id', 'device_type', 'items.name'])
                ->where('items.id', $item_id)
                ->join('users', 'users.id', 'items.user_id')->first();
        $message = 'Liked your product '.ucfirst($data->name);
        $notificationController = new NotificationController();
        $notification_type = 3;
        $data['item_id'] = $item_id;
        $data['title'] = ucfirst($userData->username);
        $noti = $notificationController->notificationSend($data->device_type, $data->device_id, $message, $notification_type, $data);

        return response()->json(['message' => 'Get data successfully.', 'data' => $data], Response::HTTP_OK);
    }

    public static function getUserInfo(Int $id)
    {
        return User::searchUserName($id)->first();
    }
}
