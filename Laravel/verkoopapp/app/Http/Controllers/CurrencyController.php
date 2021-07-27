<?php

namespace App\Http\Controllers;
use App\User;
use App\Currencies;
use App\Countries;
use App\States;
use App\Cities;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
class CurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "welcome";
        exit;
        $user = User::Where('id', 9)->first();
        if($user && $user->country_code){
          echo $currency = $user->currency->code;
          exit;
        }else{
          $currency = "ZAR";
        }
    }

    public function fixer_rate_update(){
        /* FIXER API RATE */
        $endpoint = "http://data.fixer.io/api/latest";
        $client = new \GuzzleHttp\Client();
        $access_key="81a9cb0d31b8a78fe1043a231dd52cd1";
        $base = 'EUR';

        $response = $client->request('GET', $endpoint, ['query' => [
            'access_key'=>$access_key,
            'base' => $base, 
        ]]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        $content = json_decode($response->getBody(), true);
        
        if($content && $content['success']){
            $date = $content['date'];
            $timestamp = $content['timestamp'];
            $rates = $content['rates'];

            foreach ($rates as $code => $rate) {
                $rate =  $rate / $rates['USD'];
                $cur = array();
                $cur['rate1'] = $rate;
                $cur['updated_at'] =$date;
                $cur['timestamp'] = $timestamp;
                $update = Currencies::where('code', $code)->update($cur);
            }
        }
    }

    public function apilayer_rate_update(){
        $endpoint = "http://www.apilayer.net/api/live";
        $client = new \GuzzleHttp\Client();
        $access_key="933d49c85a3eaf68832c2d75c443bd3e";
        $format = 1;

        $response = $client->request('GET', $endpoint, ['query' => [
            'access_key'=>$access_key,
            'format' => $format, 
        ]]);

        $statusCode = $response->getStatusCode();
        $content1 = $response->getBody();

        $content1 = json_decode($response->getBody(), true);
        
        if($content1 && $content1['success']){
            $timestamp = $content1['timestamp'];
            $rates1 = $content1['quotes'];

            foreach ($rates1 as $code => $rate) {
                $code = substr($code,3);
                $cur = array();
                $cur['rate2'] = $rate ;
                $cur['updated_at'] =date('Y-m-d');
                $cur['timestamp'] = $timestamp;
                $update = Currencies::where('code', $code)->update($cur);
            }
        }
    }

    public function get_currencies(){
       $currencies = Currencies::all(); 
       $output = array();
       foreach ($currencies as $key => $currency) {
           $output['currency'][] = array(
                'code'=>$currency['code'],
                'rate'=>max([$currency->rate1,$currency->rate2])
            );
       }

       $message = 'No Currencies Found';
        if (count($output)) {
            $message = 'Data Get Successfully.';
        }
       return Response()->json(['data' => $output, 'message' => $message], Response::HTTP_OK);
    }
    
    public function get_states($id){
      
       $country = Countries::select('id', 'name')->where('sortname', $id)->get();
      
       $country_id = $country[0]['id'];
       $states = States::select('id', 'name')->where('country_id', $country_id)->get();
       
       $output = array();
       foreach ($states as $key => $region) {
           $output['state'][] = array(
                'id'=>$region['id'],
                'name'=>$region['name'],
            );
       }

       $message = 'No states Found';
        if (count($output)) {
            $message = 'Data Get Successfully.';
        }
       return Response()->json(['data' => $output, 'message' => $message], Response::HTTP_OK);
    }
    
    public function get_cities($id){
      
       $cities = Cities::select('id', 'name')->where('state_id', $id)->get();
       
       $output = array();
       if(!empty($cities) && count($cities) > 0){
           foreach ($cities as $key => $city) {
               $output['city'][] = array(
                    'id'=>$city['id'],
                    'name'=>$city['name'],
                );
           }
            $message = 'Data Get Successfully.';
       }
       else{
           $message = 'No cities Found';
           $output['city'] = array();
       }

       //$message = 'No cities Found';
        if (count($output)) {
           //$message = 'Data Get Successfully.';
        }
       return Response()->json(['data' => $output, 'message' => $message], Response::HTTP_OK);
    }
}
