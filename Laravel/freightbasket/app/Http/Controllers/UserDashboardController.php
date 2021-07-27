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
use App\User;
use App\role;
use App\Timeline;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    
    // user dashboard views listing
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        $limit = 20;
        $roles = DB::table('roles')->skip(2)->take(3)->get();
        $timeline = Timeline::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(5);
        $comment_count = 2;
        $user = Auth::user();
        return view('user.userdashboard', compact('roles', 'timeline', 'comment_count', 'user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function profile(){
	    $compantdetaisl = DB::table('companydetails')->where(['user_id'=>Auth::User()->id ])->first();
		return view('user.myprofile')->with('compantdetails',$compantdetaisl);	
    }

    
    public function managestaff(){
	    $staff = DB::table('users')->where(['refrence_id'=>Auth::User()->id ])->paginate(10);
	    $numberofemployee = DB::table('users')->where(['refrence_id'=>Auth::User()->id ])->count();
		return view('user.staff')->with(['staff'=>$staff,'numberofemployee'=>$numberofemployee]);	
     }
     
    public function companyprofile(){
        $user = User::find(Auth::user()->id);
	    $compantdetails = DB::table('companydetails')->where(['user_id'=>Auth::User()->id ,'status'=>'1'])->get();
		$staff = DB::table('users')->where(['refrence_id'=>Auth::User()->id ])->get();
	    $compnay_documents = DB::table('compnay_documents')->where(['user_id'=>Auth::User()->id,'status'=>'1'])->first();
	    return view('user.companyprofile', compact('user','compantdetails','staff','compnay_documents'));
    }

    public function coprofile($id){
        $user = User::find($id);
        $compantdetails = DB::table('companydetails')->where(['user_id'=>$id ,'status'=>'1'])->get();
        $staff = DB::table('users')->where(['refrence_id'=>$id ])->get();
        $compnay_documents = DB::table('compnay_documents')->where(['user_id'=>$id,'status'=>'1'])->first();
        return view('user.companyprofile', compact('user','compantdetails','staff','compnay_documents'));  
    }
    // end of userdashboard view listing
    
    // update profile picture
        
    public function profileupdate(Request $request){
        $request->validate([
            'avatar'     =>  'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = request()->except(['_token','oldavatar']);

        $image = $request->file('avatar');
        if($request->hasfile('avatar')){
            $imageName = time().'$'.Auth::user()->name.'$'.$image->getClientOriginalName();
            if($image->move(public_path('uploads/profiles/'),$imageName)){
                $data['avatar'] = $imageName;
                $oldavatar = 'public/uploads/profiles/'.request()->oldavatar;
                if(file_exists($oldavatar)){
                    unlink($oldavatar);
                }
            }
            else{
                $data['avatar'] = 'default-user-photo.jpg';
            }
        }
        

       
        $q = DB::table('users')
            ->where('id', $request->id)
            ->update($data);
            Session::flash('success', 'Successfully updated!');
            return redirect('myprofile');
          
     }
     
    //  end of update profile picture
    
    //  =====================================
    
    //  update password 
    
    public function updatepassword(Request $request){
            $password = $request->newpassword;
            $confirm_new_password = $request->confirm_new_password;
            if($password == $confirm_new_password){
                $newpassword = bcrypt($password);
                $q = DB::table('users')
            ->where('id', $request->id)
            ->update(['password'=>$newpassword]);
                if($q){
                    Session::flash('success', 'Successfully updated!');
                 return redirect('myprofile');
                }
                else{
                 Session::flash('error', 'Sorry Password could not be updated. Please Try Again!');
                 return redirect('myprofile');   
                }
            }
            else{
                Session::flash('error', 'Sorry! New Password And Confirm New Password Are Not Same');
            return redirect('myprofile');
            }
    }
    //  end of update password
    
    // =====================================================
     
    //  Add company details at the time of register
    
     public function addcompanydetails(Request $request){
             
        $path = time().'$'.Auth::User()->id.'$'.Auth::User()->name;
        File::makeDirectory('uploads/userdocuments/'.$path, $mode = 0777, true, true);
        
        $folderPath = 'uploads/userdocuments/'.$path;
        if($request->hasfile('companydocuments'))
        {

            foreach($request->file('companydocuments') as $file)
            {
                $name = time().'$'.$file->getClientOriginalName();

                $file->move(base_path($folderPath), $name);  
            }
        }
  
        $data2['companydocuments'] = $folderPath;
        $data2['user_id'] = Auth::User()->id;
        DB::table('compnay_documents')->insert($data2);
         
        $data = request()->except(['_token','id','companydocuments']);
        if(request()->companyservice){
            $data['companyservice'] = serialize(request()->companyservice);    
        }

        // if(request()->service){
        //     $data['service'] = implode(',',request()->service);    
        // }
        
        $data['user_id'] = $request->id; 
        $q = DB::table('companydetails')->insert($data);
        if($q){
            if(Auth::User()->role_id == "2"){
                 DB::table('users')
                ->where('id', $request->id)
                ->update(['role_id'=> request()->companytype]);
            }

        }
        return redirect('userdashboard');
     }
    //  end of Add company details at the time of register
    
    
    //  =========================================
    
    //  Update company profile 
    
    
    
     public function updatecompanyprofile(Request $request){
         
       $data = request()->except(['_token','id','user_id']);
       $data['companyservice'] = implode(',',request()->companyservice);
       DB::table('companydetails')
            ->where(['id'=>$request->id,'user_id'=>$request->user_id])
            ->update($data);
            Session::flash('success', 'Successfully updated!');
            return redirect('companyprofile');
     }
     
    //  end of company update 
    
    // =============================================
    
    // Add employee 
    
    
    
     public function addemployee(Request $request){
         
         $data2 = $request->except('_token');
         $password ='freight'.rand(4569,8957);
         $data2['password'] = bcrypt($password);
         $data['verified'] = "1";
          $companydetails = DB::table('companydetails')->where(['user_id'=>Auth::User()->id ])->first();
          
         if(DB::table('users')->insert($data2)){
             $data = array([
                    'username'=>$request->email,
                    'password' => $password,
                    'companyname' =>$companydetails->companyname
                 ]);
           Mail::to($request->email)->send(new SendMail($data));
           return back()->with('success','Employee Added Successfully');
        }
        
     }
     
    //  end of add employee
    
    //  =================================================
     
    //  checking mail at the time of adding employee
    
    
    
     public function checkemail(Request $request){
         $email = $request->data;
        $q = DB::table('users')->where(['email'=> $email])->count();
        if($q > 0){
           echo "ok";
        }
       
     }
     
    //  end of checking mail at the time of adding employee
    
    // ======================================================================
    
    
    // delete single image from user documents
    
    public function deleteimage(Request $request){
        if(unlink($request->data)){
            echo"ok";
        }
        else{
            echo"no";
        }
       
    }


    /*=============================Background image upload start=========================================*/
    public function backgroundupdate(Request $request){
        $request->validate([
            'background_image'     =>  'image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $user = Auth::user();
        $cover = $request->file('background_image');

        $avatarName = $user->id.'_bg_'.time().'.'.$cover->getClientOriginalExtension();
        $folderPath = 'uploads/background-image/';
        
        $image_path = public_path($folderPath.$user->background_image);  // Value is not URL but directory file path
        
        $cover->move(public_path($folderPath), $avatarName);              
        $user->background_image = $avatarName;
        $user->save();

        if(file_exists($image_path.'/'.$request->oldimage)) {
            unlink($image_path);
        }

        return back()
            ->with('success','You have successfully upload image.');
          
    }

    /*------------------------------------------------------------------------------------------------------*/
    public function profilepictureupdate(Request $request){
        $request->validate([
            'profilepic'     =>  'image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $user = Auth::user();
        $cover = $request->file('profilepic');
        $avatarName = time().'$'.Auth::user()->name.'$'.$cover->getClientOriginalName();
        $folderPath = 'uploads/profiles/';
        
        $image_path = 'public/'.$folderPath.$user->avatar;  // Value is not URL but directory file path
        $cover->move(public_path($folderPath), $avatarName);              
        $user->avatar = $avatarName;
        $user->save();

        if(file_exists($image_path)) {
            unlink($image_path);
        }

        return back()
            ->with('success','You have successfully upload image.');
          
    }
    /*=============================Background image upload end=========================================*/


    
}
