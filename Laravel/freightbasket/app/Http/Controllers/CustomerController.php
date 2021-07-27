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

class CustomerController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    
    public function index(){
        $cusomerslist = DB::table('customer_lists')->where(['user_id'=>Auth::User()->id])->paginate(20); 
		return view('user.customerdata', ['cusomerslist' => $cusomerslist]);
    }
   
    public function  newcustomer(){
        $countryname = DB::table('countries')->get();
        return view('user.addcustomer', ['countryname' => $countryname]); 
    }
   
   public function addcustomer(Request $request){
       
        $data = $request->except('_token','name','email','occuption');
 
        $data2 = array();
        $ar1 = $request->name;
        $ar2 = $request->email;
        $ar3 = $request->occuption;
        $count = count($ar1);
            for($i=0 ;$i<$count;$i++){
                 $data2[] = array(
                    'name'=>$ar1[$i],
                    'email'=>$ar2[$i],
                    'occupation'=>$ar3[$i]
        );
            }
       
            
        $email = serialize($data2);
        $data['multi_user']= $email;
        $data['user_id']= Auth::User()->id;
        
        $q = DB::table('customer_lists')->insert($data);
        if($q){
            return back()->with('success','Added Successfully');
        }
        else{
            return back()->with(['error'=>'Could Not Be Added Please Try Again']);
        }
   }
   public function viewcustomer(Request $request){
        $customer = DB::table('customer_lists')->where(['id'=>$request->id])->first();
        return view('user.viewcustomer',['customer'=>$customer]); 
   }
   public function delete(Request $request){
        $q = DB::table('customer_lists')->where(['id'=>$request->id])->delete();
        if($q){
            return back()->with('success','Customer Data Deleted Successfully');
        }
        else{
             return back()->with('error','Colud Not Be Deleted Please Try Again');
        }
   }
   
   public function edit(Request $request){
        $customer = DB::table('customer_lists')->where(['id'=>$request->id])->first();
        $countryname = DB::table('countries')->get();
        return view('user.editcustomerdata',['customer'=>$customer, 'countryname' => $countryname]); 
   }
   
   public function update(Request $request){
       $data = $request->except('_token','name','email','occuption','id');
 
       $data2 = array();
        $ar1 = $request->name;
        $ar2 = $request->email;
        $ar3 = $request->occuption;
        $count = count($ar1);
            for($i=0 ;$i<$count;$i++){
                 $data2[] = array(
                    'name'=>$ar1[$i],
                    'email'=>$ar2[$i],
                    'occupation'=>$ar3[$i]
        );
            }
       
            
        $email = serialize($data2);
        $data['multi_user']= $email;
       
        $q = DB::table('customer_lists')->where(['id'=>$request->id])->update($data);
        if($q){
            return redirect('customer')->with('success','Updated Successfully');
        }
        else{
             return redirect('customer')->with(['error'=>'Could Not Be Updated Please Try Again']);
        }
   }
   
}
