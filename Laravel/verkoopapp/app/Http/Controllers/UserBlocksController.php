<?php

namespace App\Http\Controllers;

use App\user_block;
use Illuminate\Http\Request;
use App\Http\Requests\createUserBlock;
use Exception;
use Illuminate\Http\Response;

class UserBlocksController extends Controller
{
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
    public function store(createUserBlock $request)
    {
        try {
            $user_id = (int) $request['user_id'];
            $user_block_id = (int) $request['user_block_id'];
            $findData = user_block::where(['user_id' => $user_id, 'user_block_id' => $user_block_id])->first();
            if ($findData) {
                return response()->json(['message' => 'Already Blocked.'], Response::HTTP_FOUND);
            }
            $req = new user_block();
            $req['user_block_id'] = $user_block_id;
            $req['user_id'] = $user_id;
            $req->save();
            if ($req) {
                return response()->json(['message' => 'User is blocked successfully.', 'data' => $req], Response::HTTP_CREATED);
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
        try {
            $list = user_block::where('user_id', $id)->get();
            if (count($list) > 0) {
                return response()->json(['message' => 'Get User block list successfully.', 'data' => $list], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Data not found.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            $user_block = user_block::find($id);
            $delete = $user_block->delete();
            if ($delete) {
                return response()->json(['message' => 'User is unblocked successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
