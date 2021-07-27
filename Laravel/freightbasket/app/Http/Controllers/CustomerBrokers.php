<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Session;
use File;
use App\role;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;

class CustomerBrokers extends Controller
{
    
    // user dashboard views listing
    public function index(){
    	if (Auth::guest()){
    		return redirect('/home');
    	}
    	else{
            $results = DB::select('select * from companydetails where user_id = ?', [Auth::user()->id]);
    		return view('user.customerbrokers')->with('results_companydetails',$results); 
    	}
    }
}
