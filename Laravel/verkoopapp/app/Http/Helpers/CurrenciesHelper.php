<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\User;
use App\Currencies;

class CurrenciesHelper
{
    //Function used for replacing hooks in our templates
    public function Convertcurrency($from='', $to='',$input)
    {
      if($from == '' || $to == '' || $from == $to){
        return $input;
      }

      $fromCurrency = Currencies::where('code', $from)->first();
      $toCurrency = Currencies::where('code', $to)->first();

      $fromRate = max([$fromCurrency->rate1,$fromCurrency->rate2]);
      $toRate = max([$toCurrency->rate1,$toCurrency->rate2]);
      $convert_rate = $toRate / $fromRate;
      
      $output = $convert_rate * $input;
      
      return number_format($output,2);  
    }

    public function getUserCurrency($user_id){
      
      /*$user = User::Where('id', $user_id)->first();
      echo '<pre>';
      print_r($user);
      exit;
      if($user && $user->country_code){
        return $user->currency;
      }
      return;*/
    }
}
