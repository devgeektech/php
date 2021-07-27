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
use App\Offer;
use App\Freight;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    
    public function index(){
        return view('user.shipment'); 
    }
   
    public function add_shipmentpage(){
       return view('user.addshipment'); 
    }
    
    public function addshipmentpart2(){
	    $goods_description = DB::table('goods_descriptions')->where(['user_id'=>Auth::User()->id])->get();
	    $vessels = DB::table('vessels')->get();
	    $seaports = DB::table('shipment_ports')->get();
	    $customer_lists = DB::table('customer_lists')->where(['user_id'=>Auth::User()->id])->get();
		return view('user.addshipmentpart2')->with(['goods_description'=>$goods_description,'vessels'=>$vessels,'seaports'=>$seaports,'customer_lists'=>$customer_lists]); 
    }
    
    public function getvoyage(Request $request){
       $vessels = DB::table('vessel_schedules')->where(['vessel_id'=>$request->data])->get();
       $data = "";
       foreach($vessels as $row){
           $data.='<option value="'.$row->voyage_no.'">'.$row->voyage_no.'</option>';
       }
       return $data;
    }
   
    public function add_good_description(Request $request){
       $data =array();
       $data['description_name'] = $request->goods_name_title;
       $data['user_id'] = Auth::User()->id;
       $data['commercial_invoice_no'] = $request->commercial_invoice_no;
       $data['commercial_invoice_date'] = $request->commercial_invoice_date;
        $data2 =  array();
        $ar1 = $request->goods_name;
        $count = count($ar1);
        $ar2 = $request->packing_types;
        $ar3 = $request->packege_quantity;
        $ar4 = $request->gross_weight;
        $ar5 = $request->width;
        $ar6 = $request->length;
        $ar7 = $request->height;
        $ar8 = $request->hs_code;
        $ar9 = $request->container_number;
        $ar10 = $request->type_opf_container;
        $ar11 = $request->seal_no;        
        for($i=0; $i<$count; $i++){
            $data2[] = array(
            'goods_name'=>$ar1[$i],
            'packing_types'=>$ar2[$i],
            'packege_quantity'=>$ar3[$i],
            'gross_weight'=>$ar4[$i],
            'width'=>$ar5[$i],
            'length'=>$ar6[$i],
            'height'=>$ar7[$i],
            'hs_code'=>$ar8[$i], 
            'container_number'=>$ar9[$i],
            'seal_no'=>$ar11[$i],
            'type_opf_container'=>$ar10[$i]);
                     
        }
                 
        $price = serialize($data2);
        $data['goods_description']= $price;
         $q = DB::table('goods_descriptions')
            ->insert($data);
            if($q){
                return redirect()->route('addshipmentpart2');
            }
            else{
                return back()->with(['error'=>'Could Not Be Added Please Try Again']);
            }
                
   }
    
    public function add_billing_form(Request $request){
       $data = $request->except('_token');
        $data['user_id'] = Auth::User()->id;
        if($request->delivery_zip_code !=""){
            
            $data2 =  array();
            $ar1 = $request->delivery_zip_code;
            $ar2 = $request->delivery_area_name;
            $ar3 = $request->delivery_city_name;
            
            $data2[] = array(
            'zipconde'=>$ar1,
            'area_name'=>$ar2,
            'city_name'=>$ar3);
                     
        
         $delivery = serialize($data2);
        $data['place_of_delivery']= $delivery;
        }
        
        $q = DB::table('shipments')
            ->insert($data);
        if($q){
            return redirect()->route('manageshipments')->with('success','Added Successfuly');
        }
        else{
            return redirect()->route('manageshipments')->with('error','Could Not be Added Please Try Again');
        }
    }
    
    
    /* Offer Data Start*/
    public function offer(){
        $offers = Offer::where('user_id', auth()->user()->id)->get();
        if(!empty($offers)){
            return view('user.offer', compact('offers'));
        }else{
            $data = "Offer Not Found";
            return view('user.404', compact($data));
        }
    }

    public function offerview($id){
       
        $offer = Offer::find($id);
        if(!empty($offer)){
            return view('user.offerview', compact('offer'));
        }else{
            $data = "Offer Not Found";
            return view('user.404', compact($data));
        }
    }

    public function offeradd(Request $request){
        if($request->has('freight_id')){
          
            $offer_price = array(
                "cost_type" => $request->input('cost_type'), 
                "calculaion" => array_values(array_filter($request->input('calculaion'))), 
                "quantity" => array_filter($request->input('quantity')), 
                "currency" => $request->input('currency'), 
                "price" => $request->input('price'));
            
            $token = Str::random(40);
            $i = 0;
            foreach($request->input('client_email') as $client_email_status){
                $status_data[$i]["email"] = $client_email_status;
                $status_data[$i]["status"] = 0;
                $i++;
            }
            $data=array(
                "user_id" => auth()->user()->id,
                "freight_id"=>$request->input('freight_id'),
                "customer"=>json_encode($request->input('client_email')),
                "offer_price"=>json_encode($offer_price),
                "remember_token"=>$token,
                "status"=>json_encode($status_data),
                "custom_note"=>$request->input('custom_note'),
            );
            
            $id = DB::table('offer')->insertGetId($data);
            
            $offer = DB::table('offer')
                    ->join('freights', 'freights.id', '=', 'offer.freight_id')
                    ->join('users', 'users.id', '=', 'offer.user_id')
                    ->join('companydetails', 'companydetails.user_id', '=', 'offer.user_id')
                    ->where('offer.id', '=', $id)
                    ->select('offer.id as offer_id','offer.remember_token as offer_remember_token','offer.created_at as offer_created_at','offer.updated_at as offer_updated_at','offer.*','freights.*','users.*','companydetails.*')
                    ->get();
            
            foreach($request->input('client_email') as $email){
                
                $offer->customer_lists = DB::table('customer_lists')
                        ->where("user_id", "=", Auth::user()->id)
                        ->where("multi_user", "LIKE", "%".$email."%")
                        ->first();
                
                $data_email = array("email" => $email, "user_email" => Auth::user()->email);
                Mail::send('emails.offer', ["offer" => $offer, "customer_email" => $email], function ($message)use($data_email) {
                    $message->from($data_email['user_email'], 'Freight Basket Fre Fwrs');
                    $message->subject('New Freight Price Offer only For You');
                    $message->to($data_email['email']);
                });
                unset($data_email);
            }
            //dd($request->input('client_email'));
            
            return redirect('offer')->with('success','offer saved Successfully.');
        }else{
            $freights = Freight::where('user_id', '=', Auth::user()->id)->get();
            $cusomerslist = DB::table('customer_lists')->where(['user_id'=>Auth::User()->id])->get(); 
            return view('user.offeraddedit', ['freights' => $freights, 'cusomerslist' => $cusomerslist]);
        }
    }
    
    public function get_cost_type(Request $request){
        $cost_type = DB::table('service_cost_type')->where('services', "LIKE",  "%".$request['data']."%")->get(); 
        return $cost_type;
    }
    
    public function offerreceived(){
        $offer = Offer::where('offer.customer', 'LIKE', '%' . auth()->user()->email . '%')->get();
        return view('user.offerrecevied', compact('offer'));
    }
    
    public function offerinvite(Request $request){
        $offer = Offer::where('customer', 'LIKE', '%' . auth()->user()->email . '%')->find($request->offer_id);
        if($offer){
            if($request->status == "accept"){
                $offer_status = json_decode($offer->status);
                foreach($offer_status as $key => $status1){
                    if($status1->email == Auth::User()->email){
                        if($status1->email == 0){
                            $offer_status[$key]->status = 1;
                        }
                    }
                }

                $offer->status = json_encode($offer_status);
                $offer->save();
            }else{
                $offer_status = json_decode($offer->status);
                foreach($offer_status as $key => $status1){
                    if($status1->email == Auth::User()->email){
                        if($status1->email == 0){
                            $offer_status[$key]->status = 3;
                            $offer_status[$key]->message = $request->message;
                        }
                    }
                }

                $offer->status = json_encode($offer_status);
                $offer->save();
            }
            return redirect('offer/received')->with('success', 'Offer Updated');
        }else{
            return redirect('offer/received')->with('error', 'Offer not found');
        }
    }
    
    /* Offer Data End*/
}
