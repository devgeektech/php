<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Session;
use File;
use App\Vessel;
use App\VesselSchedule;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendMail;

class VesselController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    
    public function index()
    {
        $vessels = Vessel::orderBy('id', 'desc')->get();
        return view('user.vessel', ['vessels' => $vessels]);
    }
    
    public function get_vessel_by(Request $request)
    {
        $vessels = Vessel::find($request);
        return response()->json($vessels);
    }

    public function store(Request $request)
    {

    	if($request->has('vessel_name')){

    		$rules = array(
    			'vessel_name' => 'required|unique:vessels,vessel_name',
    			'imo_no' => 'required|min:7|max:7',
    			'built_date' => 'required|min:4|max:4',
    		);

		    $validator = Validator::make($request->all(), $rules);

		    if ($validator->fails())
		    {
		        return redirect('vessel/add')->withErrors($validator)->withInput();
		    }else{
		        $vessel = new Vessel;
		        $vessel->vessel_name = $request->input('vessel_name');
		        $vessel->nmsi = $request->input('nmsi');
		        $vessel->call_sign = $request->input('call_sign');
		        $vessel->built_date = $request->input('built_date');
		        $vessel->imo_no = $request->input('imo_no');
		        $vessel->flag = $request->input('flag');
		        $vessel->save();
		        return redirect('vessel')->with('success','Vessel saved Successfully..!!!');
		    }

		}else{
			$countryname = DB::table('countries')->get();
			return view('user.addvessel', ['countryname' => $countryname]);
		}
    }
    
    
    
    /*Global Vessel Start */
    public function v_view($id){
        $vessels = Vessel::find($id);
        return view('user.viewvessel', ['vessels' => $vessels]);
    }
    
    public function v_edit(Request $request, $id){

        if($request->has('vessel_name')){           
            
            $Vesse = Vessel::find($id);
            $Vesse_re = $request->all();
            $Vesse->update($Vesse_re);

            return redirect('vessel')->with('success','Vessel updated.');
        }else{
            $vessels = Vessel::find($id);
            $countryname = DB::table('countries')->get();
            return view('user.editvessel', ['vessels' => $vessels, 'countryname' => $countryname]); 
        }     
    }
    
    public function v_send_email(Request $request){
        $id = $request->input('vessel_id');
        $email = $request->input('email');
        $vessel = Vessel::find($id);
        
        $user = Auth::user();
        
        $companydetails = DB::table('companydetails')->where('user_id', '=', auth()->user()->id)->first();
             
        Mail::send('emails.globalvesselnotify', ['vessel' => $vessel, 'user' => $user, 'companydetails' => $companydetails], function ($message)use($email) {
            $message->from('admin@freightbasket.us', 'Freight Basket');
            $message->subject('Vessel Update - Freight Basket');
            $message->to($email);
        });
        
        // otherwise everything is okay ...
        return "success";
        
    }
    /*Global Vessel End */
    
    /*Vessel schedule listing*/
    public function vschedule()
    {        

        $results = DB::select('select * from companydetails where user_id = ?', [Auth::user()->id]);
        if(!empty($results)){
            $result_fnl = unserialize($results[0]->companyservice);
            if (array_key_exists("'fre-fwrs'",$result_fnl) || array_key_exists("fre-fwrs",$result_fnl)){
                $vessels = VesselSchedule::join('vessels', 'vessels.id', '=', 'vessel_schedules.vessel_id')
                           ->where('user_id', '=', auth()->user()->id)
                           ->select('vessel_schedules.id as vsID','vessel_schedules.*','vessels.*')
                           ->orderBy('vessel_schedules.id', 'desc')
                           ->paginate(15);
                return view('user.vesselschedule', ['vessels' => $vessels]);
            }else{
                return redirect('userdashboard');
            }
        }else{
            return redirect('userdashboard');
        }

    }
    
    /*Add Vessel schedule listing*/
    public function vs_add(Request $request)
    {   
        if($request->has('voyage_no')){

            
            $vessel = new VesselSchedule;
            $vessel->user_id = auth()->user()->id;
            $vessel->vessel_id = $request->input('vessel_name');
            $vessel->voyage_no = $request->input('voyage_no');
            $vessel->liner_agent = $request->input('liner_agent');
            $vessel->departure_country = $request->input('departure_country');
            $vessel->departure_port = $request->input('departure_port');
            $vessel->est_departure_date = $request->input('est_departure_date');
            $vessel->arrival_country = $request->input('arrival_country');
            $vessel->arrival_port = $request->input('arrival_port');
            $vessel->est_arrival_date = $request->input('est_arrival_date');
            $vessel->terminal = $request->input('terminal');
            $vessel->loading_date = $request->input('loading_date');
            $vessel->ship_role = $request->input('ship_role');
            $vessel->decl_surrender_office = $request->input('decl_surrender_office');
            $vessel->cut_off_date = $request->input('cut_off_date');
            $vessel->booking_ref_no = $request->input('booking_ref_no');
            $vessel->warehouse_stuffing_att = $request->input('warehouse_stuffing_att');
            $vessel->notes = $request->input('notes');
            $vessel->container_no = $request->input('container_no');
            $vessel->save();
            
            return redirect('vessel/schedule')->with('success','Vessel saved Successfully.');

        }else{
            
            $vessels = Vessel::all();
            $countryname = DB::table('countries')->get();
            $countryname_seaports = DB::table('seaports')->distinct()->select("Countries")->get();
            return view('user.addvesselschedule', ['countryname' => $countryname, 'countryname_seaports' => $countryname_seaports, 'vessels' => $vessels]);
        }     
    }

    public function vs_view($id){
        $vessel_schedules = VesselSchedule::join('vessels', 'vessels.id', '=', 'vessel_schedules.vessel_id')
               ->select('vessel_schedules.id as vsID','vessel_schedules.*','vessels.*')
               ->where('vessel_schedules.id', '=', $id)
               ->orderBy('vessel_schedules.id', 'desc')->first();
        $vessels = Vessel::all();
        $countryname = DB::table('countries')->get();
        $countryname_seaports = DB::table('seaports')->distinct()->select("Countries")->get();
        return view('user.viewvesselschedule', ['countryname' => $countryname, 'countryname_seaports' => $countryname_seaports, 'vessels' => $vessels, 'vessel_schedules' => $vessel_schedules]);
    }
    public function vs_edit(Request $request, $id){

        if($request->has('voyage_no')){           
            
            $VesselSchedule = VesselSchedule::find($id);
            $VesselSchedule_re = $request->all();
            $VesselSchedule->update($VesselSchedule_re);

            return redirect('vessel/schedule')->with('success','Vessel schedule updated.');
        }else{
            
            $vessel_schedules = VesselSchedule::join('vessels', 'vessels.id', '=', 'vessel_schedules.vessel_id')
                   ->select('vessel_schedules.id as vsID','vessel_schedules.*','vessels.*')
                   ->where('vessel_schedules.id', '=', $id)
                   ->orderBy('vessel_schedules.id', 'desc')->first();
            $vessels = Vessel::all();
            $countryname = DB::table('countries')->get();
            $countryname_seaports = DB::table('seaports')->distinct()->select("Countries")->get();
            return view('user.editvesselschedule', ['countryname' => $countryname, 'countryname_seaports' => $countryname_seaports, 'vessels' => $vessels, 'vessel_schedules' => $vessel_schedules]);
        }     
    }
    public function vs_delete($id){
        $vessel = VesselSchedule::find($id);
        $vessel->delete();
        return redirect('vessel/schedule')->with('success','Vessel schedule deleted.');
    }
    public function vs_clone($id){
        $vessel = VesselSchedule::find($id);
        $newVessel = $vessel->replicate();
        $newVessel->save();

        return redirect('vessel/schedule')->with('success','Vessel cloned Successfully.');
    }
    
    public function vs_send_email(Request $request){
        $id = $request->input('vessel_schedule_id');
        $email = $request->input('email');
        $vessel_schedules = VesselSchedule::join('vessels', 'vessels.id', '=', 'vessel_schedules.vessel_id')
               ->select('vessel_schedules.id as vsID','vessel_schedules.*','vessels.*')
               ->where('vessel_schedules.id', '=', $id)
               ->orderBy('vessel_schedules.id', 'desc')->first();
         
        
        $user = Auth::user();
        
        $companydetails = DB::table('companydetails')->where('user_id', '=', auth()->user()->id)->first();
        
        Mail::send('emails.vesselschedulenotify', ['vessel_schedules' => $vessel_schedules, 'user' => $user, 'companydetails' => $companydetails], function ($message)use($email) {
            $message->from('admin@freightbasket.us', 'Freight Basket');
            $message->subject('Vessel Schedule Update - Freight Basket');
            $message->to($email);
        });
        
        // otherwise everything is okay ...
        return "success";
        
    }
}