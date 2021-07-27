<?php

namespace App\Http\Controllers;

//use App\rating;
use Illuminate\Http\Request;
use App\Http\Requests\rating as RatingsRequests;
use App\Rating;
use App\NotificationActivity;
use Exception;
use Illuminate\Http\Response;
use App\Items;
use App\User;
use DB;

class RatingController extends Controller
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
    public function store(RatingsRequests $request)
    {
        try {
            $ratings = new Rating($request->all());
            $save = $ratings->save();
            if ($save) {
                $user_id = $request->user_id;
                $item_id = $request->item_id;
                $rated_user_id = $request->rated_user_id;
                $userData = User::searchUserName($user_id);
                $itemData = Items::find($item_id);
                $message = 'Rated your product '.ucfirst($itemData->name);
                $message_type = 4;
                $data['item_id'] = $item_id;
                $data['title'] = ucfirst($userData->username);
                $noti = $this->notification->getDeviceInfo($item_id, $message, $message_type, $rated_user_id, $data);

                if ($noti) {
                    $notiActivity = new NotificationActivity();
                    $notiActivity->title = ucfirst($userData->username);
                    $notiActivity->message = $message;
                    $notiActivity->type = $message_type;
                    $notiActivity->from = $user_id;
                    $notiActivity->to = $itemData->user_id;
                    if ($notiActivity->save()) {
                        $ratings->notification_id = $notiActivity->id;
                        $ratings->save();
                    }
                }

                return response()->json(['message' => 'Rating successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Rating failed.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\rating $rating
     *
     * @return \Illuminate\Http\Response
     */
    public function show(rating $rating)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\rating $rating
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(rating $rating)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\rating              $rating
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rating $rating)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\rating $rating
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(rating $rating)
    {
    }

    /**
     * Rating user list good.
     */
    public function listRatedUserGood($id)
    {
        $data = Rating::select('userName', 'profile_pic', 'rating', 'name', 'items_images.url', 'ratings.user_id', 'ratings.item_id', 'ratings.created_at')->join('items', 'items.id', '=', 'ratings.item_id')->leftJoin('items_images', 'items.id', '=', 'items_images.item_id')->join('users', 'users.id', '=', 'ratings.user_id')->where('rated_user_id', $id)->where('rating', '>=', 4)->get();

        return Response()->json(['data' => $data], Response::HTTP_OK);
    }

    /**
     * Rating user list bad.
     */
    public function listRatedUserAverage($id)
    {
        DB::enableQueryLog();
        $data = Rating::select('userName', 'profile_pic', 'rating', 'name', 'items_images.url', 'ratings.user_id', 'ratings.item_id', 'ratings.created_at')->leftJoin('items', 'items.id', '=', 'ratings.item_id')->leftJoin('items_images', 'items.id', '=', 'items_images.item_id')->leftJoin('users', 'users.id', '=', 'ratings.user_id')->where('rated_user_id', $id)->where('rating', '>', 2)->where('rating', '<', 4)->get();

        return Response()->json(['data' => $data], Response::HTTP_OK);
    }

    /**
     * Rating user list bad.
     */
    public function listRatedUserBad($id)
    {
        $data = Rating::select('userName', 'profile_pic', 'rating', 'name', 'items_images.url', 'ratings.user_id', 'ratings.item_id', 'ratings.created_at')->join('items', 'items.id', '=', 'ratings.item_id')->join('items_images', 'items.id', '=', 'items_images.item_id')->join('users', 'users.id', '=', 'ratings.user_id')->where('rated_user_id', $id)->where('rating', '<=', 2)->get();

        return Response()->json(['data' => $data], Response::HTTP_OK);
    }
}
