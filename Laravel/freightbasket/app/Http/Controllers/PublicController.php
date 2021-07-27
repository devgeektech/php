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
use App\Freight;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function offerconfirm(Request $request){
        $offer = DB::table('offer')
                        ->where('id', "=",  $request->id)
                        ->where('remember_token', "=",  $request->token)
                        ->get(); 
                        
        if($offer->count()){
            $offer_sttus = json_decode($offer[0]->status);
            $update = false;
            foreach($offer_sttus as $offerstatus){
                if($offerstatus->email == $request->email){
                   if($offerstatus->status == 0){
                       $offerstatus->status = 1;
                       $update = true;
                   } 
                }
            }
            
            if($update == true){
                DB::table('offer')
                    ->where('id', "=",  $request->id)
                    ->where('remember_token', "=",  $request->token)
                    ->update(['status' => json_encode($offer_sttus)]);    
            
                $offer_emals = DB::table('offer')
                    ->join('freights', 'freights.id', '=', 'offer.freight_id')
                    ->join('users', 'users.id', '=', 'offer.user_id')
                    ->join('companydetails', 'users.id', '=', 'offer.user_id')
                    ->where('offer.id', '=', $request->id)
                    ->select('offer.id as offer_id','offer.remember_token as offer_remember_token','offer.created_at as offer_created_at','offer.updated_at as offer_updated_at','offer.*','freights.*','users.*','companydetails.*')
                    ->get();
                
                /*For customer start here*/
                $request_email = array("toemail" => $request->email, "fromemail" => $offer_emals[0]->email);
                Mail::send('emails.offerConfirm', ["offer" => $offer_emals], function ($message)use($request_email) {
                    $message->from($request_email['fromemail'], 'Freight Basket');
                    $message->subject('Offer Confirmed By Fre Fwrs');
                    $message->to($request_email['toemail']);
                });
                /*For customer end here*/
                
                /*For Fre fwrs start here*/
                $request_email_fre = array("fromemail" => $request->email, "toemail" => $offer_emals[0]->email);
                Mail::send('emails.offerConfirm', ["offer" => $offer_emals], function ($message)use($request_email_fre) {
                    $message->from($request_email_fre['fromemail'], 'Freight Basket Fre Fwrs');
                    $message->subject('Offer Confirmed By Customer');
                    $message->to($request_email_fre['toemail']);
                });
                /*For Fre fwrs end here*/
                
                return view('offerconfirm', ['success' => "Offer Activated Successfully"]);
            }else{
                return view('offerconfirm', ['success' => "Offer already activated"]);
            }
        }else{
            return view('offerconfirm', ['success' => "Offer not found"]);
        }
        
    }
}
