<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\comments;
use App\User;
use App\Items;
use App\Http\Requests\CommentsStoreRequest;
use App\NotificationActivity;
use Illuminate\Http\Response;
use Exception;
use DB;

class CommentsController extends Controller
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
    public function store(CommentsStoreRequest $request)
    {
        try {
            $item_id = $request->item_id;
            $user_id = $request->user_id;
            $comments = new comments();
            $comments->item_id = $item_id;
            $comments->user_id = $user_id;
            $comments->comment = $request->comment;
            $comments->save();
            if ($comments) {
                $userData = User::where('id', $comments['user_id'])->first();
                $data['id'] = $comments['id'];
                $data['comment'] = $comments['comment'];
                $data['created_at'] = date('Y-m-d H:i:s', strtotime($comments['created_at']));
                $data['username'] = $userData->username;
                $data['profile_pic'] = $userData->profile_pic;
                $userData = User::searchUserName($user_id);
                $itemData = Items::find($item_id);
                $message = 'Commented on your product '.ucfirst($itemData->name);
                $message_type = 6;
                $notiData['comment_id'] = $comments['id'];
                $notiData['item_id'] = $item_id;
                $notiData['title'] = ucfirst($userData->username);
                $noti = $this->notification->getDeviceInfo($item_id, $message, $message_type, '', $notiData);

                if ($noti) {
                    $notiActivity = new NotificationActivity();
                    $notiActivity->title = ucfirst($userData->username);
                    $notiActivity->message = $message;
                    $notiActivity->type = $message_type;
                    $notiActivity->from = $user_id;
                    $notiActivity->to = $itemData->user_id;
                    if ($notiActivity->save()) {
                        $comments->notification_id = $notiActivity->id;
                        $comments->save();
                    }
                }

                return response()->json(['message' => 'Comment is added successfully.', 'data' => $data], Response::HTTP_CREATED);
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Comments = comments::find($id);
            $delete = $Comments->delete();
            if ($delete) {
                return response()->json(['message' => 'Comment is deleted successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
