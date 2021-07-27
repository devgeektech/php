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



class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id){
        $userdetail = DB::table('users')->where(['id'=>$id,'refrence_id'=>Auth::User()->id ])->first();
       return view('user/singlestaff')->with(['singleuser'=>$userdetail]);
    }
    public function deletestaff($id){
       $q =  DB::table('users')->where(['id'=>$id,'refrence_id'=>Auth::User()->id ])->update(['refrence_id'=>'']);
       if($q){
           return back()->with(['success'=>'Deleted Successfully']);
       }
       else{
           return back()->with(['error'=>'Could Not Be updated, Please Try Again']);
       }
    }
}
