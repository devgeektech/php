<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Item_images;
use App\Items;
use App\items_details;
use App\like;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    public function getItems(Request $request)
    {
        $offset = $request->input('offset');
        $data = array();
        $data['items'] = array();
        $items = Items::getUnpurchasedItems($offset);
        foreach ($items as $item) {
            $itemData['id'] = $item['id'];
            $itemData['name'] = $item['name'];
            $itemData['owner'] = $item->user ? $item->user->first_name : '';
            $itemData['price'] = $item['price'];
            $itemData['description'] = $item['description'];
            $itemData['category'] = $item->category->name;
            $itemData['image'] = isset($item->items_image[0]) ? url('/').'/'.$item->items_image[0]->url : asset('public/images/image-placeholder.png');
            $itemData['type'] = $item['type'] != 1 ? ($item['type'] == 1 ? 'Car' : 'Property') : 'Common';
            $itemData['created'] = date('m-d-Y', strtotime($item['created_at']));
            array_push($data['items'], $itemData);
        }
        $data['total_items'] = Items::where('is_sold', 0)->count();
        $data['status'] = 200;

        return response()->json($data);
    }

    public function deleteItem(Request $request)
    {
        $rules = array(
        'item_id' => 'required',
      );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $msgs = $validator[$i];
            }
            $data = [
            'message' => $msgs,
            'data' => (object) array(),
          ];

            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        $item_id = $request->input('item_id');
        $deleted = Items::where('id', $item_id)->delete();
        if ($deleted) {
            Item_images::where('item_id', $item_id)->delete();
            items_details::where('item_id', $item_id)->delete();
            like::where('item_id', $item_id)->delete();
            DB::table('chat_users')->where('item_id', $item_id)->delete();

            return response()->json(['status' => 200, 'message' => 'Deleted successfully']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Something went wrong'], 400);
        }
    }
}
