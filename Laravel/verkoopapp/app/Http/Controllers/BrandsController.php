<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\brands;
use App\cars_type;
use Illuminate\Http\Response;
use Exception;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
        $data = brands::All();
        // $cars_type = cars_type::All();
         if($data){
                return response()->json(['data' => $data, 'message'=>'Data Get Successfully.'], Response::HTTP_OK);        
            }else{
                return response()->json(['data' => [], 'message'=>'Data Not Found.'], Response::HTTP_OK);    
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getBrandWithModels(){
        $brands = brands::with(['car_models'])->get();
        return response()->json(['data' => $brands, 'message'=>'Data Get Successfully.'], Response::HTTP_OK);        

    }
}
