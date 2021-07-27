<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Items;
use App\Payments;
use Illuminate\Support\Facades\Request;

class TransactionController extends Controller
{
    public function getAppTransactions()
    {
        $tnxs = Payments::getAllPaymentTransactions();

        return view('admin/transactions', ['tnxs' => $tnxs]);
    }

    public function getSalesReport(Request $request)
    {
        $data['items'] = array();
        $soldItems = Items::getSoldItemsDetails();
        foreach ($soldItems as $item) {
            $itemData['id'] = $item['id'];
            $itemData['name'] = $item['name'];
            $itemData['owner'] = $item->user->first_name;
            $itemData['price'] = $item['price'];
            $itemData['description'] = $item['description'];
            $itemData['category'] = $item->category->name;
            $itemData['image'] = $item->items_image[0]->url;
            $itemData['type'] = $item['type'] != 1 ? ($item['type'] == 1 ? 'Car' : 'Property') : 'Common';
            $itemData['created'] = date('d-m-Y', strtotime($item['created_at']));
            array_push($data['items'], $itemData);
        }

        return response()->json($data);
    }
}
