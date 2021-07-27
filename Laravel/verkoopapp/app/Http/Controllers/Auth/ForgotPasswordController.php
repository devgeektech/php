<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Category;
use App\Image;
use DB;
use Mail;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Crypt;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    public function forget($id)
    {
       $idd = Crypt::decrypt($id); 
       $data['id'] = $idd; 
       return view('newpass',compact('data'));
    }

   
    public function updatepass(Request $request)
    {
        $password = bcrypt($request->password);
        $update = DB::update("UPDATE users SET password = '$password' WHERE id =$request->userid");
        if($update)
        {
         return  redirect('/success');
        }
        else
        {
          return  redirect('/error');
        }
    }
    

       
}
