<?php

namespace App\Http\Controllers;

use App\specialraterequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use URL;
use Response;
use Session;
use View;
use File;

class SpecialraterequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $specialraterequest = specialraterequest::where("user_id",Auth::id())->get();
        if($specialraterequest || !empty($specialraterequest)){
            return view("special-rate.index", compact('specialraterequest'));
        }else{
            $data = "No Special Rate Request Found";
            return view("user.404", compact("data"));
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
        $action = URL::route('user.specialrate.store');

        return view('special-rate.create')->with(compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'commodity_name' => 'required|unique:specialraterequests,commodity_name',
            ]
        );
        $request->merge([
            'user_id' => Auth::id()
        ]);
        // dd($request);
        specialraterequest::create($request->all());
        return redirect()->route('user.specialrate')
                        ->with('success','Special Rate created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\specialraterequest  $specialraterequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view("special-rate.show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\specialraterequest  $specialraterequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = URL::route('user.specialrate.update', ['id' => $id]);
        $data = specialraterequest::where('user_id', Auth::id())->find($id);        

        if($data || !empty($data)){
            return view('special-rate.create')->with(compact('data', 'action'));
        }else{
            $data = "No Special Rate Request Found";
            return view("user.404", compact("data"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\specialraterequest  $specialraterequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rate = specialraterequest::where('user_id', Auth::id())->find($id);        
        $rate->update($request->all());
        
        return redirect()->route('user.specialrate')
                        ->with('success','Special Rate updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\specialraterequest  $specialraterequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $rate = specialraterequest::find($request->id);
        if ($rate){
            if ($rate->user_id == Auth::id()) {
                if ($rate->delete()) {
                    return redirect()->route('user.specialrate')
                        ->with('success','Special Rate deleted successfully');
                }
            }
        }

        return redirect()->route('user.specialrate')
                        ->with('error','Error delete');
    }



    /*All Listing of special rates start */
    public function listing()
    {
        $rates = specialraterequest::get();
        
        return view("special-rate.listing", compact("rates"));
    }
    /*All Listing of special rates End*/
}
