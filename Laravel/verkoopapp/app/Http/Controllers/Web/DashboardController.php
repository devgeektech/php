<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Items;
use App\User;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $data = array();
        $data['items'] = array();
        $oneMonth = date('Y-m-d', strtotime('-1 months'));
        $data['total_items'] = Items::count();
        $data['sold_items'] = Items::where('is_sold', 1)->count();
        $data['total_users'] = User::where('login_type', 'normal')->orWhere('login_type', '')->count();
        $items = Items::getDashboardItems($oneMonth);
        foreach ($items as $item) {
            $itemData['id'] = $item['id'];
            $itemData['name'] = $item['name'];
            $itemData['owner'] = $item->user->first_name;
            $itemData['price'] = $item['price'];
            $itemData['description'] = $item['description'];
            $itemData['category'] = $item->category->name;
            $itemData['image'] = isset($item->items_image[0]) ? url('/').'/'.$item->items_image[0]->url : asset('public/images/image-placeholder.png');
            $itemData['type'] = $item['type'] != 1 ? ($item['type'] == 1 ? 'Car' : 'Property') : 'Common';
            $itemData['created'] = date('m-d-Y', strtotime($item['created_at']));
            array_push($data['items'], $itemData);
        }
        $data['items_count'] = count($data['items']);
        $data['status'] = 200;

        return response()->json($data);
    }
}
