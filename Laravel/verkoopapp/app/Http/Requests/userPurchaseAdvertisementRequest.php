<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Advertisement_plans;
use App\User_accounts;

class userPurchaseAdvertisementRequest extends FormRequest
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
            'advertisement_plan_id' => 'required',
            'category_id' => 'required',
            'banner' => 'required'
        ];
    }
    
    
    
      public function withValidator($validator)
    {
        // checks create user only update item
        // before making changes
        $validator->after(function ($validator) {            
            $findData = Advertisement_plans::where(['id'=> $this->advertisement_plan_id])->first();
            $userCoin = User_accounts::where('user_id', $this->user_id)->first();
            $totalCoin = isset($userCoin)?$userCoin->coin:0;
            if (!$findData) {
              $validator->errors()->add('invalid_plan', 'Please choose vailed plan.');
            }else if($totalCoin < $findData->coin){
              $validator->errors()->add('less_coin', 'Please parchase coins first then add advertisement.');
            }else{
                $this->coin = $findData->coin;
            }
        });
        return;
    }
}
