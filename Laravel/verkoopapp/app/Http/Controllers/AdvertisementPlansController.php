<?php

namespace App\Http\Controllers;

use App\Advertisement_plans;
use App\User_accounts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Exception;

class AdvertisementPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $user_id = Input::get('user_id');
            $data = Advertisement_plans::where('is_active',1)->get();
            if($data){
                $userCoinData = $this->getUserCoin($user_id);
                $coins = isset($userCoinData)?$userCoinData->coin:0;
                return response()->json(['data' => $data, 'coins'=> $coins, 'message'=>'Data Get Successfully.'], Response::HTTP_OK);        
            }else{
                return response()->json(['data' => [],'coins'=> 0, 'message'=>'Data Not Found.'], Response::HTTP_OK);    
            }
            
        }catch(Exception $e){
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advertisment_plans  $advertisment_plans
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement_plans $advertisment_plans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Advertisment_plans  $advertisment_plans
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertisement_plans $advertisment_plans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advertisment_plans  $advertisment_plans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement_plans $advertisment_plans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advertisment_plans  $advertisment_plans
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement_plans $advertisment_plans)
    {
        //
    }

     public static function getUserCoin($user_id){
        return User_accounts::where('user_id',$user_id)->first();   
    }
}
