<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Friendship;
use App\PostComment;
use App\PostLike;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Response;
use Session;
use View;
use File;

class FriendshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        //
        $user = Auth::user();
        $user2 = User::where('id',$request->id)->first();
        $response = array();
        $response['code'] = 400;
        
        $friend = Friendship::where('first_user', $user->id)->where('second_user', $request->id)->get()->first();
        if ($friend) { // UnLike
            if ($friend->delete()) {
                $response['code'] = "delete";
            }
        }else{
            $friend = new Friendship();
            $friend->first_user = Auth::user()->id;
            $friend->second_user = $request->id;
            $friend->acted_user = $request->id;
            $friend->status = 'pending';

            if ($friend->save()) {
                $response['code'] = 200;
            }

            DB::table('notification')->insert(
                ['user_id' => $user->id, 'sender_id' => $user2->id, 'name' => $user->name." sent you friend Request."]
            );
        }

        return Response::json($response);
    }
    public function accept(Request $request)
    {
        //
        $user = Auth::user();
        $user2 = User::where('id',$request->id)->first();

        $response = array();
        $response['code'] = 400;
        
        $friend = Friendship::where('first_user', $request->id)->where('second_user', $user->id)->get()->first();
        if($friend){
            $friend->status = 'confirmed';

            if ($friend->save()) {
                $response['code'] = 200;
            }

            DB::table('notification')->insert(
                    ['user_id' => $user->id, 'sender_id' => $user2->id, 'name' => $user2->name." Request Accepted."]
                );
        }

        return Response::json($response);
    }

}

