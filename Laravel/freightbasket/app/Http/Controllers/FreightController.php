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
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;
use App\role;
use App\Freight;
use App\Timeline;
use App\User;
use App\VesselSchedule;
use App\Notification;

class FreightController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // view blade ofmanage freight
    public function index(){
	    $freights = Freight::where(['status'=>'1'])->orderBy('id', 'desc')->get();
	    return view('user/freights', compact('freights'));
    }
    // end of view blade freight
    
    // View blade of open freight
    public function freight(){
        $countrys = DB::table('seaports')->select('Countries')->orderBy('Countries','asc')->distinct()->get(); /*seaorts*/
	    $airports = DB::table('airports')->select('countryName')->orderBy('countryName','asc')->distinct()->get();
	    $compantdetails = DB::table('companydetails')->where(['user_id'=>Auth::User()->id,'status'=>'1','officetype'=>'main'])->first();
	    $land = DB::table('countries')->get();
	    return view('user.addfreight')->with(['compantdetails'=>$compantdetails,'countrys'=>$countrys,'airports'=>$airports,'land'=>$land]);
    }
    // end of view blade of open freight
    
    // View blade of open freight
    public function allfreights(){
        $freights = DB::table('freights')->orderBy('id', 'desc')->paginate(10);
        return view('user.allfreights')->with(['freights'=>$freights]);
    }
    // end of view blade of open freight
    
    // Add new freight
    public function addfreight(Request $request){
        $data =  array();
        
        $data['service_category']= $request->service_category;
        $data['service_type']= $request->service_type;
        
        if(request()->client_type){
            $data['client_type'] = implode(',',request()->client_type);    
        }
        
        if(request()->location_type){
            $data['location_type'] = implode(',',request()->location_type);    
        }
        
        $data['freightvalidity']= $request->freightvalidity;
        $data['comment']= $request->comment;
        $data['user_id'] = Auth::User()->id;

        if($request->service_category=="land"){
              if($request->departure_country3 != ""){
             $data['departure_country'] = $request->departure_country3;
        }
        		
		if($request->departure_port3 != ""){
             $data['departure_port'] = $request->departure_port3;
        }

 		if($request->departure_city3 != ""){
             $data['departure_city'] = $request->departure_city3;
        }

        if($request->estimate_time3 != ""){
             $data['estimate_time'] = $request->estimate_time3;
        }


		if($request->transhipment_country3 != ""){
             $data['transhipment_country'] = $request->transhipment_country3;
        }

       	if($request->transhipment_port3 != ""){
             $data['transhipment_port'] = $request->transhipment_port3;
        }


		if($request->arriaval_country3 != ""){
             $data['arriaval_country'] = $request->arriaval_country3;
        }
 
        if($request->arriaval_city3 != ""){
             $data['arriaval_city'] = $request->arriaval_city3;
        }
       
        if($request->arriaval_port3 != ""){
             $data['arriaval_port'] = $request->arriaval_port3;
        }
        
         $data2 =  array();
              
                
               
                $ar2 = $request->currency_type_for_land;
                 $count = count($ar2);
                $ar3 = $request->price_for_land;
                $ar4 = $request->cost_type_for_land;
                $ar5 = $request->calculaion_for_land;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
        
        
        }
        
        
        if($request->service_category=="air"){
                
                if($request->departure_country1 != ""){
                     $data['departure_country'] = $request->departure_country1;
                 }
                
                if($request->departure_port1 != ""){
                     $data['departure_port'] = $request->departure_port1;
                }
                
                 if($request->departure_city1 != ""){
                     $data['departure_city'] = $request->departure_city1;
                 }
                 
                  if($request->transhipment_country1 != ""){
                     $data['transhipment_country'] = $request->transhipment_country1;
                  }
                  
                   if($request->transhipment_port1 != ""){
                        $data['transhipment_port'] = $request->transhipment_port1;
                    }
                    
                     if($request->arriaval_country1 != ""){
                            $data['arriaval_country'] = $request->arriaval_country1;
                      }
                if($request->arriaval_city1 != ""){
                         $data['arriaval_city'] = $request->arriaval_city1;
                 }
                 
                     
                    if($request->arriaval_port1 != ""){
                         $data['arriaval_port'] = $request->arriaval_port1;
                      }
                      
              if($request->estimate_time1 != ""){
                    $data['estimate_time'] = $request->estimate_time1;
                 }
                 
                 
                 
                $data2 =  array();
              
                $ar = $request->airquantity;
                $count = count($ar);
                $ar2 = $request->aircurrency_type;
                $ar3 = $request->airprice;
                $ar4 = $request->air_cost_type;
                $ar5 = $request->air_calculaion;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'quantity'=>$ar[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
                
        }
        
           if($request->service_category=="sea"){
               if($request->departure_country2 != ""){
             $data['departure_country'] = $request->departure_country2;
        }
        
		 if($request->departure_port2 != ""){
             $data['departure_port'] = $request->departure_port2;
        }
        

       
        if($request->departure_city2 != ""){
             $data['departure_city'] = $request->departure_city2;
        }
        

        
        if($request->estimate_time2 != ""){
             $data['estimate_time'] = $request->estimate_time2;
        }
        

        
        if($request->transhipment_country2 != ""){
             $data['transhipment_country'] = $request->transhipment_country2;
        }
        

       
        if($request->transhipment_port2 != ""){
             $data['transhipment_port'] = $request->transhipment_port2;
        }
        
		if($request->arriaval_country2 != ""){
             $data['arriaval_country'] = $request->arriaval_country2;
        }
        
		if($request->arriaval_city2 != ""){
             $data['arriaval_city'] = $request->arriaval_city2;
        }
        
       
        
        if($request->arriaval_port2 != ""){
             $data['arriaval_port'] = $request->arriaval_port2;
        } 
        if($request->service_type == "FCL"){
             $data2 =  array();
               
                $ar2 = $request->currency_type_for_sea_fcl;
                 $count = count($ar2);
                $ar3 = $request->price_for_sea_fcl;
                $ar4 = $request->cost_type_for_sea_fcl;
                $ar5 = $request->calculaion_for_sea_fcl;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
        }
        if($request->service_type == "LCL"){
            $data2 =  array();
               
                $ar2 = $request->currency_type_for_sea_lcl;
                 $count = count($ar2);
                $ar3 = $request->price_for_sea_lcl;
                $ar4 = $request->cost_type_for_sea_lcl;
                $ar5 = $request->calculaion_for_sea_lcl;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                  $price = serialize($data2);
                $data['airport_price']= $price;
                 
        }
        
           }
       
        $q = DB::table('freights')->insert($data);
        if($q){
               return redirect('freights')->with('success','Updated Successfully');
           }
           else{
               return redirect('freights')->with('error','Could Not Update, Please Try Again');
           }
    }
    
    
    // end of add freight
    
    
    
    // deletion of freights
    
    public function deletefreight(Request $request){
       $q = DB::table('freights')->where(['id'=>$request->id,'user_id'=>Auth::User()->id])->update(['status'=>'0']);
       if($q){
           return back()->with('success','Successfully Deleted');
       }
       else{
           return back()->with('error','Could Not Deleted Please Try Again');
       }
    }
    
    // end fof delete freight
    
    
    
    
    // View of single freight
    
    public function singlefreight(Request $request){
       $freight = DB::table('freights')->where(['id'=>$request->id])->first();
       return view('user.singlefreight')->with(['freight'=>$freight]);
    }
    
    // end of view single freight
    
    
    
    // For duplicate the freight
    
    
    public function clonefreight(Request $request)
    {
       
                $task = Freight::find($request->id);
                $newTask = $task->replicate(); // the new project_id
                $q = $newTask->save();
                
             if($q){
                 return back()->with('success','Duplicated Successfully');
                 }
            else{
                 return back()->with('Error','Could Not Be Duplicated, Please Try Again');
             }
    }
    
    // end of duplicate freight
    
    // for edit single freight
    
    
    public function editfreight(Request $request)
    {
            $freight = DB::table('freights')->where(['id'=>$request->id,'status'=>'1','user_id'=>Auth::User()->id])->first();
            $countrys = DB::table('seaports')->select('Countries')->orderBy('Countries','asc')->distinct()->get(); /*seaorts*/
    	    $airports = DB::table('airports')->select('countryName')->orderBy('countryName','asc')->distinct()->get();
    	    $compantdetails = DB::table('companydetails')->where(['user_id'=>Auth::User()->id,'status'=>'1','officetype'=>'main'])->first();
    	    $land = DB::table('countries')->get();
    	    return view('user.editfreight')->with(['compantdetails'=>$compantdetails,'countrys'=>$countrys,'airports'=>$airports,'land'=>$land,'freight'=>$freight]);
    }
        
    public function updatefeight(Request $request){
            
        if($request->service_category == "land"){
        $data = array();
        $data['service_type']= $request->service_type;

        
        if($request->departure_country3 != ""){
             $data['departure_country'] = $request->departure_country3;
        }
        if($request->departure_city3 != ""){
             $data['departure_city'] = $request->departure_city3;
        }
        if($request->departure_port3 != ""){
             $data['departure_city'] = $request->departure_port3;
        }
        if($request->estimate_time3 != ""){
             $data['estimate_time'] = $request->estimate_time3;
        }

        
        if($request->transhipment_country3 != ""){
             $data['transhipment_country'] = $request->transhipment_country3;
        }

        
        if($request->transhipment_port3 != ""){
             $data['transhipment_port'] = $request->transhipment_port3;
        }

        if($request->arriaval_country3 != ""){
             $data['arriaval_country'] = $request->arriaval_country3;
        }


    
        if($request->arriaval_city3 != ""){
             $data['arriaval_city'] = $request->arriaval_city3;
        }
       
        
        
        if($request->arriaval_port3 != ""){
             $data['arriaval_port'] = $request->arriaval_port3;
        }
        
         if(request()->client_type){
            $data['client_type'] = implode(',',request()->client_type);    
        }
         if(request()->location_type){
            $data['location_type'] = implode(',',request()->location_type);    
        }
        
         $data['freightvalidity']= $request->freightvalidity;
        
        
         $data2 =  array();
              
                
               
                $ar2 = $request->currency_type_for_land;
                 $count = count($ar2);
                $ar3 = $request->price_for_land;
                $ar4 = $request->cost_type_for_land;
                $ar5 = $request->calculaion_for_land;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
    }
           if($request->service_category == "sea"){
            //   dd($request->all());
              $data = array();
            //   $data['service_type']= $request->service_type;

        
        if($request->departure_country2 != ""){
             $data['departure_country'] = $request->departure_country2;
        }



        
        if($request->departure_city2 != ""){
             $data['departure_city'] = $request->departure_city2;
        }
        if($request->departure_port2 != ""){
             $data['departure_city'] = $request->departure_port2;
        }

        
        if($request->estimate_time2 != ""){
             $data['estimate_time'] = $request->estimate_time2;
        }

        
        if($request->transhipment_country2 != ""){
             $data['transhipment_country'] = $request->transhipment_country2;
        }

        
        if($request->transhipment_port2 != ""){
             $data['transhipment_port'] = $request->transhipment_port2;
        }

        if($request->arriaval_country2 != ""){
             $data['arriaval_country'] = $request->arriaval_country2;
        }


    
        if($request->arriaval_city2 != ""){
             $data['arriaval_city'] = $request->arriaval_city2;
        }
       
        
        
        if($request->arriaval_port2 != ""){
             $data['arriaval_port'] = $request->arriaval_port2;
        }
        
         if(request()->client_type){
            $data['client_type'] = implode(',',request()->client_type);    
        }
         if(request()->location_type){
            $data['location_type'] = implode(',',request()->location_type);    
        }
        
         $data['freightvalidity']= $request->freightvalidity;
         $data['service_type'] = $request->service_type;
         if($request->service_type == "FCL"){
             $data2 =  array();
               
                $ar2 = $request->currency_type_for_sea_fcl;
                 $count = count($ar2);
                $ar3 = $request->price_for_sea_fcl;
                $ar4 = $request->cost_type_for_sea_fcl;
                $ar5 = $request->calculaion_for_sea_fcl;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
        }
        if($request->service_type == "LCL"){
            $data2 =  array();
               
                $ar2 = $request->currency_type_for_sea_lcl;
                 $count = count($ar2);
                $ar3 = $request->price_for_sea_lcl;
                $ar4 = $request->cost_type_for_sea_lcl;
                $ar5 = $request->calculaion_for_sea_lcl;
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                  $price = serialize($data2);
                $data['airport_price']= $price;
                 
        }
        
           }
           if($request->service_category == "air"){
               
               $data = array();  
        if($request->departure_country1 != ""){
             $data['departure_country'] = $request->departure_country1;
        }



        
        if($request->departure_city1 != ""){
             $data['departure_city'] = $request->departure_city1;
        }
        if($request->departure_port1 != ""){
             $data['departure_city'] = $request->departure_port1;
        }

        
        if($request->estimate_time1 != ""){
             $data['estimate_time'] = $request->estimate_time1;
        }

        
        if($request->transhipment_country1 != ""){
             $data['transhipment_country'] = $request->transhipment_country1;
        }

        
        if($request->transhipment_port1 != ""){
             $data['transhipment_port'] = $request->transhipment_port1;
        }

        if($request->arriaval_country1 != ""){
             $data['arriaval_country'] = $request->arriaval_country1;
        }


    
        if($request->arriaval_city1 != ""){
             $data['arriaval_city'] = $request->arriaval_city1;
        }
       
        
        
        if($request->arriaval_port1 != ""){
             $data['arriaval_port'] = $request->arriaval_port1;
        }
        
         if(request()->client_type){
            $data['client_type'] = implode(',',request()->client_type);    
        }
         if(request()->location_type){
            $data['location_type'] = implode(',',request()->location_type);    
        }
        
        
            
        
        
         $data['freightvalidity']= $request->freightvalidity;
        
       
              
                $data2 =  array();
                $ar = $request->airquantity;
                $count = count($ar);
                $ar2 = $request->aircurrency_type;
                $ar3 = $request->airprice;
                $ar4 = $request->air_cost_type;
                $ar5 = $request->air_calculaion;
                
                for($i=0; $i<$count; $i++){
                    $data2[] = array(
                    'cost_type'=>$ar4[$i],
                    'calculation'=>$ar5[$i],
                    'quantity'=>$ar[$i],
                    'currency_type'=>$ar2[$i],
                    'price'=>$ar3[$i]); 
                 }
                 
               
                
                // $price = base64_encode(serialize($data2));
                $price = serialize($data2);
                $data['airport_price']= $price;
                
               
           }
           
           $q = DB::table('freights')->where(['id'=>$request->id,'user_id'=>$request->user_id])->update($data);
           if($q){
               return redirect('freights')->with('success','Updated Successfully');
           }
           else{
               return redirect('freights')->with('error','Could Not Update, Please Try Again');
           }
        }
    
    
    // end of edit single freight
    
    
    
    /*Ajax route for seaports*/
    
    
    public function getport(Request $request){
        $ports = DB::table('seaports')->where(['Countries'=>$request->data])->get();
        $data = "";
        foreach($ports as $port){
            $data.="<option value='".$port->ports."'>".$port->ports."</option>";    
        }
        return $data;
    }
    
    public function getcity(Request $request){
        
        $countries = $request->data;
        $ports = DB::table('countries')->where(['name'=>$request->data])->first();
       
        if( $ports){
            $country_id = $ports->id;
            $cities = DB::table('cities')->where(['country_id'=>$country_id])->get();
            $data = "";
            foreach($cities as $city){
                $data.="<option value='".$city->name."'>".$city->name."</option>";
            }
            return $data;
        }
        
    }
    
    /*End of seaports*/
    
    // Ajax Route for airports
    
    public function getcityforairport(Request $request){
         $cities = DB::table('airports')->select('cityName')->orderBy('cityName','asc')->where(['countryName'=>$request->data])->distinct()->get();
        $data = "";
        foreach($cities as $city){
            $data.="<option value='".$city->cityName."'>".$city->cityName."</option>";    
        }
        return $data; 
    }
    
    public function getportforairport(Request $request){
        $ports = DB::table('airports')->where(['countryName'=>$request->data])->get();
        $data = "";
        foreach($ports as $port){
            $data.="<option value='".$port->name."'>".$port->name."</option>";    
        }
        return $data;   
    }
    
    /* Get Country By words start here*/
    public function getcountry(Request $request){
        if($request->input("type") == "air"){
            $data = DB::table('airports')
                    ->where('countryName', "LIKE", "%".$request->input("query")."%")
                    ->select("countryName")
                    ->distinct()
                    ->take(10)
                    ->get();
        }else if($request->input("type") == "sea" || $request->input("type") == "sea-lcl" || $request->input("type") == "sea-fcl"){
            $data = DB::table('seaports')
                    ->where('Countries', "LIKE", "%".$request->input("query")."%")
                    ->select("Countries as countryName")
                    ->distinct()
                    ->take(10)
                    ->get();
        }else{
            $data = DB::table('countries')
                    ->where('name', "LIKE", "%".$request->input("query")."%")
                    ->select("name as countryName")
                    ->distinct()
                    ->take(10)
                    ->get();
        }
        
        return json_encode($data);
    }
    /* Get Country By words end here*/
    
    /* Get ports By words start here*/
    public function getportsbyword(Request $request){
        if($request->input("freight_type_name") == "air"){
            $data = DB::table('airports')
                    ->where('name', "LIKE", "%".$request->input("arv_ports_name")."%")
                    ->where(function($query) use ($request)
                    {
                        if (!empty($request->arv_country_name)) {
                            $query->where("countryName", "=", $request->arv_country_name);
                        }
                    })
                    ->select("name as ports")
                    ->distinct()
                    ->take(10)
                    ->get();
        }else if($request->input("freight_type_name") == "sea" || $request->input("freight_type_name") == "sea-lcl" || $request->input("freight_type_name") == "sea-fcl"){
            $data = DB::table('seaports')
                    ->where('ports', "LIKE", "%".$request->input("arv_ports_name")."%")
                    ->where(function($query) use ($request)
                    {
                        if (!empty($request->arv_country_name)) {
                            $query->where("Countries", "=", $request->arv_country_name);
                        }
                    })
                    ->select("ports")
                    ->distinct()
                    ->take(10)
                    ->get();
        }else{
            $data = DB::table('countries')
                    ->where('name', "LIKE", "%".$request->input("arv_ports_name")."%")
                    ->select("name as ports")
                    ->distinct()
                    ->take(10)
                    ->get();
        }
        
        return json_encode($data);
    }
    /* Get Country By words End here*/
    
    /* Get get_freights_by start here*/
    public function get_freights_by(Request $request){
        
        $data = Freight::where("service_category", "=", $request->freight_type_name)
                    ->where(function($query) use ($request)
                    {
                        if (!empty($request->dep_country_name)) {
                            $query->where("departure_country", "=", $request->dep_country_name);
                        }
                        if (!empty($request->arv_country_name)) {
                            $query->where("arriaval_country", "=", $request->arv_country_name);
                        }
                        if (!empty($request->arv_ports_name)) {
                            $query->where("arriaval_port", "=", $request->arv_ports_name);
                        }
                    })
                    ->where("user_id", "=", Auth::User()->id)
                    ->get();
        return json_encode($data);
    }
    /* Get get_freights_by End here*/



    /*Search Members Start*/
    public function getmemebers(Request $request){
        $data = DB::table('companydetails')
                    ->where('companyservice', "LIKE", "%".$request->service."%")
                    ->distinct()
                    ->get();
         $i = 0;

        foreach($data as $result){
            $companyDetails = array(
             'companyname' => $result->companyname,
             'companycity' => $result->companycity,
             'companycountry' => $result->countryname,
             'companyemail' => $result->companyemail,
             'companyphone' => $result->companyphone
            );

            $user_id = $result->user_id;
            $user_data[$i] = User::where('id',$user_id)->first();
            $user_data[$i]['company_details'] = $companyDetails;
            $i++;
        }
        
        return view('user/member_search', ['result' => !empty($user_data) ? $user_data : '']);
    }
    /*Search Members End*/

    /*Member profile start*/

    public function member_profile(Request $request){
        if(isset($request->notification)){
            $notify = Notification::where('id',$request->notification)->first();
            if($notify){
                $notify->status = 0;
                $notify->save();
            }
        }

        $limit = 20;
        $simpleuser = User::where('id',$request->user_id)->first();
        $user = Auth::user();
        $timeline = Timeline::where('user_id',$request->user_id)->get();
        $comment_count = 2;
        return view('user/member_profile', compact('simpleuser','user','timeline','limit','comment_count'));
     }

     /* Member Profile End*/

     /* Member Send Mail start*/

     public function member_send_email(Request $request){
        $email = $request->input('email');
        $vessel_schedules = VesselSchedule::join('vessels', 'vessels.id', '=', 'vessel_schedules.vessel_id')
               ->select('vessel_schedules.id as vsID','vessel_schedules.*','vessels.*')
               ->where('vessel_schedules.id', '=', '707')
               ->orderBy('vessel_schedules.id', 'desc')->first();
         
        
        $user = Auth::user();
        
        $companydetails = DB::table('companydetails')->where('user_id', '=', auth()->user()->id)->first();
        
        Mail::send('emails.test', ['vessel_schedules' => $vessel_schedules, 'user' => $user, 'companydetails' => $companydetails], function ($message)use($email) {
            $message->from('admin@freightbasket.us', 'Freight Basket');
            $message->subject('Member Test Email - Freight Basket');
            $message->to($email);
        });
        
        // otherwise everything is okay ...
        return redirect()->back()->with('success', 'Email sent successfully.');  
        
    }
    /* Member send mail end*/
}
