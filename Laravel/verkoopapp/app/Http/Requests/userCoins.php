<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Coin_plans;
use App\User_accounts;
use App\User;
use App\Currencies;

class userCoins extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'coin_plan_id' => 'required'
        ];
    }
    
    
    public function withValidator($validator)
    {
        // before making changes
        $validator->after(function ($validator) {
            
            $findData = Coin_plans::where(['id'=> $this->coin_plan_id])->first();
            $userAmount = User_accounts::where('user_id', $this->user_id)->first();
            $totalAmount = isset($userAmount)?$userAmount->amount:0;

            $user = User::Where('id', $this->user_id)->first();
            if($user && $user->country_code){
                $currency = isset($user->currency) && isset($user->currency->code) ? $user->currency->code : "ZAR";
            }else{
                $currency = "ZAR";
            }

            /*$classCtrl = app()->make(\App\Helpers\CurrenciesHelper::class);
            $totalAmount2 = $classCtrl->Convertcurrency($currency,"ZAR",$totalAmount);*/

            $from = $currency;
            $to = "ZAR";
            $input = $totalAmount;
            if($from == '' || $to == '' || $from == $to){
                $totalAmount =  $input;
            }else{
               $fromCurrency = Currencies::where('code', $from)->first();
               $toCurrency = Currencies::where('code', $to)->first();
               $fromRate = max([$fromCurrency->rate1,$fromCurrency->rate2]);
               $toRate = max([$toCurrency->rate1,$toCurrency->rate2]);
               $convert_rate = $toRate / $fromRate;
               $output = $convert_rate * $input;
               $totalAmount =  number_format($output,2);  
            }
              
            if (!$findData) {
                $validator->errors()->add('invalid_plan', 'Please choose vailed plan.');
            }else if($totalAmount < $findData->amount){
                $validator->errors()->add('less_amount', 'Please add amount first then purchase plan.');
            }else{
                $this->coin = $findData->coin;
                $this->amount = $findData->amount;
            }
        });
        return;
    }
}
