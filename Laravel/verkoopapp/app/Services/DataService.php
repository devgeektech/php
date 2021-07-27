<?php
/**
 * Created by PhpStorm.
 * User: upendra yadav
 * Date: 20/8/18
 * Time: 1:08 PM
 */
namespace App\Services;

use Illuminate\Http\Request;
use App\like;

class DataService
{
    public function getData(Request $data){
    	$request =  json_decode($data->getContent(), true);
    	if($request === null )
    	{
    		$request = $data->input();
    	}
    	return $request;
    }
    /**
     * get item selected field
     */
    public static function get_selectedField($itemsData,$id){
       
        return $itemsData->map(function ($item, $key) use ($id) {

            $item_id = $item['id'];

            $items['id'] = $item_id;

            $items['user_id'] = $item['user_id'];
            
             $items['country_code'] = $item['country_code'];

            $items['category_id'] = $item['category_id'];

            $items['name'] = $item['name'];

            $items['currency'] = (isset($item['currency']) && $item['currency'] !="") ? $item['currency'] : "ZAR";
            $items['price'] = $item['price'];

            $items['item_type'] = $item['item_type'];
            $items['type'] = $item['type'];

            $items['created_at'] = $item['created_at'];

            $userLike = like::where('user_id','=',$id)->where('item_id','=',$item_id)->first();

            $items['like_id'] = $userLike?$userLike->id:0;

            $items['is_like'] = $userLike?true:false;

            $items['items_like_count'] = $item->items_like_count?$item->items_like_count:$item->likes_count;

            $items['image_url'] = $item->items_images?$item->items_images->url:"";

            $items['username'] = $item->user->username?$item->user->username:"";

            $items['profile_pic'] = $item->user->profile_pic?$item->user->profile_pic:"";
            $items['is_sold'] = $item['is_sold']?$item['is_sold']:0;

            return $items;

        });

    }

     public static function get_favourite_selectedField($itemsData,$id){

        return $itemsData->map(function ($item, $key) use ($id) {
            $item_id = $item['id'];
            $userLike = like::where('user_id','=',$id)->where('item_id','=',$item_id)->first();
            if($userLike){
                $items['id'] = $item_id;

                $items['user_id'] = $item['user_id'];

                $items['category_id'] = $item['category_id'];

                $items['name'] = $item['name'];

                $items['currency'] = (isset($item['currency']) && $item['currency'] !="") ? $item['currency'] : "ZAR";
                $items['price'] = $item['price'];

                $items['item_type'] = $item['item_type'];
                $items['type'] = $item['type'];

                $items['created_at'] = $item['created_at'];

                $userLike = like::where('user_id','=',$id)->where('item_id','=',$item_id)->first();

                $items['like_id'] = $userLike?$userLike->id:0;

                $items['is_like'] = $userLike?true:false;

                $items['items_like_count'] = $item->items_like_count?$item->items_like_count:$item->likes_count;

                $items['image_url'] = $item->items_images?$item->items_images->url:"";

                $items['username'] = $item->user->username?$item->user->username:"";

                $items['profile_pic'] = $item->user->profile_pic?$item->user->profile_pic:"";
                $items['is_sold'] = $item['is_sold']?$item['is_sold']:0;

                return $items;
            }
        });
    }
} 