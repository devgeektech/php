<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Items;

class RequestEditItemRequest extends FormRequest
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
            'item_id' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
            'price'=>'required',
            'item_type' => 'required'
        ];
    }
    
    
    public function withValidator($validator)
    {
        // checks create user only update item
        // before making changes
        $validator->after(function ($validator) {
            $findData = Items::where(['user_id'=> $this->user_id, 'id' => $this->item_id])->first();
            if (!$findData) {
                $validator->errors()->add('unatharized_user', 'Unautharized User.');
            }
            if(isset($findData->is_sold) && $findData->is_sold === 1){
                $validator->errors()->add('sold_item', 'Item is not update becouse it`s already sold.');
            }
            
        });
        return;
    }
}
