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

class OfficeController extends Controller
{
   public function addoffice(Request $request){
       $data = $request->except('_token');
       $data['user_id'] = Auth::User()->id;
       $data['officetype']="sub";
        $q = DB::table('companydetails')->insert($data);
        if($q){
            return back()->with('success','Office Added Successfully');
        }
        else{
            return back()->with('error','Office Could Not Be Added, Please Try Again');
        }
       
   }
    public function addaboutcompany(Request $request){
        $data = $request->except('_token');
        $q = DB::table('companydetails')->where(['user_id'=>Auth::User()->id,'officetype'=>'main'])->update($data);
        if($q){
            return back()->with('success','Updated Successfully');
        }
        else{
            return back()->with('error','Could Not Be Update, Please Try Again');;
        }
    }
}
