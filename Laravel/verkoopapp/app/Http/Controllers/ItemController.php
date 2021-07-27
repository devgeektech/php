<?php

namespace App\Http\Controllers;

use App\Advertisements;
use Illuminate\Http\Request;
use App\Items;
use App\Item_images;
use App\User_purchase_advertisement;
use App\comments;
use App\User;
use App\user_block;
use App\reports;
use App\user_category;
use App\Categories as Category;
use App\items_details;
use App\NotificationActivity;
use App\cars_type;
use App\brands;
use App\Rating;
use App\follow;
use Illuminate\Http\Response;
use App\Http\Requests\RequestsCreateItemsRequest;
use App\Http\Requests\RequestEditItemRequest;
use Exception;
use App\Services\DataService;
use DB;
use App\Http\Controllers\CategoriesController as Categories;
use App\like;
use File;

class ItemController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        //die('Rajinder');
        $this->dataService = $dataService;

        DB::enableQueryLog();
    }

    private $categories = array();

    /**
     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Categories::getCategories();
            $advertisements = $this->get_advertisments();
            $allData = array(
                        'advertisments' => $advertisements,
                        'categories' => $categories,
                    );

            return response()->json(['data' => $allData, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function testgetcate(Request $request)
    {
        $userIds = $this->getCategoryUserId($request->category_id, $request->user_id);
        $deviceData = $this->getUserDeviceInfo($userIds);

        return response()->json(['data' => $deviceData, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.

     *

     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RequestsCreateItemsRequest $request)
    {
        try {
            $user_id = $request->user_id;
            $category_id = $request->category_id;
            $user = User::Where('id', $user_id)->first();
            if($user && $user->country_code){
                $currency = isset($user->currency) && isset($user->currency->code) ? $user->currency->code : "ZAR";
            }else{
                $currency = "ZAR";
            }
            $item = new Items();
            $item->category_id = $category_id;
            $item->user_id = $user_id;
            $item->brand_id = isset($request->brand_id) ? $request->brand_id : 0;
            $item->car_type_id = isset($request->car_type_id) ? $request->car_type_id : 0;
            $item->model_id = isset($request->model_id) ? $request->model_id : 0;
            $item->zone_id = isset($request->zone_id) ? $request->zone_id : 0;
            $item->type = isset($request->type) ? $request->type : 0;
            $item->name = $request->name;
            $item->price = $request->price;
            $item->currency = $currency;
            $item->item_type = isset($request->item_type) ? $request->item_type : 0;
            $item->address = isset($request->address) ? $request->address : '0';
            $item->latitude = isset($request->latitude) ? $request->latitude : '0.000';
            $item->longitude = isset($request->longitude) ? $request->longitude : '0.000';
            $item->meet_up = isset($request->meet_up) ? $request->meet_up : 0;
            $item->description = $request->description;
            $item->save();
            if ($item) {
                $id = $item->id;
                $img = null;
                if ($request->hasfile('image')) {
                    $images = $request->file('image');
                    $labels = $request->label;
                    $imgs = $this->itemImage_save($images, $id, $labels);
                    $img = count($imgs) ? $imgs[0] : null;
                }
                if (isset($request->additional_info) && !empty($request->additional_info)) {
                    $details = json_decode($request->additional_info);
                    foreach ($details as $key => $value) {
                        $field_name = str_replace('"', '', trim($key));
                        $field_value = $value;
                        if ($field_value) {
                            $this->additionalInfo_save($field_value, $field_name, $id);
                        }
                    }
                }
                $userIds = $this->getCategoryUserId($category_id, $user_id);
                $allUserIds = $this->getfollowerId($user_id, $userIds);
//                $deviceData = $this->getUserDeviceInfo($allUserIds, $id, $user_id, $item);

                return response()->json(['image' => $img, 'message' => 'Item is added successfully.'], Response::HTTP_CREATED);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.

     *

     * @param \App\Model\Items $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $items = Items::withCount(['items_like'])->with(['items_image', 'user', 'category']);
            $allDatas = $items->where('id', $id)->first();
            if ($allDatas) {
                $reports = reports::where('type', 0)->get();
                $allDatas['username'] = $allDatas->user ? $allDatas->user->username : '';
                $allDatas['profile_pic'] = $allDatas->user ? $allDatas->user->profile_pic : '';
                $allDatas['additional_info'] = $this->additional_info($id);
                $catName = $allDatas->category ? $allDatas->category->name : '';
                $allDatas['category_name'] = $this->get_category_breadcrumb($allDatas->category->parent_id, $catName, $catName);
                $allDatas['comments'] = $this->get_allcomments($id);
                // $user_id = 1;
                // $offer_price = $this->getLastOffers($id, $user_id);
                // $allDatas['offer_price'] = $offer_price?$offer_price->price:0;

                $allDatas['reports'] = $reports;
                unset($allDatas->user);
                unset($allDatas->category);
                Items::where('id', $id)->increment('view_count');

                return response()->json(['message' => 'Get data successfully.', 'data' => $allDatas], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Data not found.', 'data' => []], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function item_details($id, $userId)
    {
        try {
            $items = Items::withCount(['items_like'])->with(['items_image', 'user', 'category']);
            $allDatas = $items->where('id', $id)->first();
            if ($allDatas) {
                $reports = reports::where('type', 0)->get();
                $allDatas['username'] = $allDatas->user ? $allDatas->user->username : '';
                $allDatas['profile_pic'] = $allDatas->user ? $allDatas->user->profile_pic : '';
                $allDatas['additional_info'] = $this->additional_info($id);
                $catName = $allDatas->category ? $allDatas->category->name : '';
                $allDatas['category_name'] = $this->get_category_breadcrumb($allDatas->category->parent_id, $catName, $catName);
                $allDatas['comments'] = $this->get_allcomments($id);
                $offer_price = $this->getLastOffers($id, $userId);
                $allDatas['offer_price'] = floatval($offer_price ? $offer_price->price : 0);
                $allDatas['chat_user_id'] = $offer_price ? $offer_price->id : 0;
                $allDatas['chat_count'] = $this->getItemChatCount($id, $userId);
                $allDatas['message_count'] = $this->getItemMessageCount($id, $userId);
                $allDatas['make_offer'] = $this->checkOffers($id, $userId) > 0 ? true : false;
                $allDatas['is_rate'] = $this->userRate($id, $userId) > 0 ? true : false;
                $allDatas['reports'] = $reports;
                $liked = $this->isItemLiked($id, $userId);
                $allDatas['is_like'] = $liked ? true : false;
                $allDatas['like_id'] = $liked ? $liked->id : 0;
                unset($allDatas->user);
                unset($allDatas->category);
                Items::where('id', $id)->increment('view_count');

                return response()->json(['message' => 'Get data successfully.', 'data' => $allDatas], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Data not found.', 'data' => $allDatas], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.

     *

     * @param \App\Model\Items $item
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Items $item)
    {
    }

    /**
     * Update the specified resource in storage.

     *

     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Items         $item
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Items $item)
    {
    }

    /**
     * Update item.
     */
    public function updateItem(RequestEditItemRequest $request)
    {
        try {
            $item_id = $request->item_id;
            $user_id = $request->user_id;
            $user = User::Where('id', $user_id)->first();
            if($user && $user->country_code){
                $currency = isset($user->currency) && isset($user->currency->code) ? $user->currency->code : "ZAR";
            }else{
                $currency = "ZAR";
            }
            $item['category_id'] = $request->category_id;
            $item['brand_id'] = isset($request->brand_id) ? $request->brand_id : 0;
            $item['car_type_id'] = isset($request->car_type_id) ? $request->car_type_id : 0;
            $item['model_id'] = isset($request->model_id) ? $request->model_id : 0;
            $item['type'] = isset($request->type) ? $request->type : 0;
            $item['user_id'] = $user_id;
            $item['name'] = $request->name;
            $item['price'] = $request->price;
            $item['country_code'] = $request->country_code;
            $item['currency'] = $currency;
            $item['item_type'] = isset($request->item_type) ? $request->item_type : 0;
            $item['address'] = isset($request->address) ? $request->address : '';
            $item['latitude'] = isset($request->latitude) ? $request->latitude : '0.000';
            $item['longitude'] = isset($request->longitude) ? $request->longitude : '0.000';
            $item['meet_up'] = isset($request->meet_up) ? $request->meet_up : 0;
            $item['zone_id'] = $request->zone_id;
            $item['description'] = $request->description;
            $update = Items::where('id', $item_id)->update($item);
            $img = null;
            if ($request->hasfile('image')) {
                $images = $request->file('image');
                $labels = $request->label;
                $imgs = $this->itemImage_save($images, $item_id, $labels);
                $img = count($imgs) ? $imgs[0] : null;
            }
            if (isset($request->additional_info) && !empty($request->additional_info)) {
                $details = json_decode($request->additional_info);
                foreach ($details as $key => $value) {
                    $field_name = str_replace('"', '', trim($key));
                    $field_value = trim($value);
                    if ($field_value && $field_value != '0') {
                        $itemDetails = items_details::where(['item_id' => $item_id, 'meta_key' => $field_name, 'meta_value' => $field_value])->first();
                        if (empty($itemDetails)) {
                            $itemDetailsCheck = items_details::where(['item_id' => $item_id, 'meta_key' => $field_name])->first();
                            if (!empty($itemDetailsCheck)) {
                                $this->additionalInfo_update($field_value, $field_name, $item_id);
                            } else {
                                $this->additionalInfo_save($field_value, $field_name, $item_id);
                            }
                        }
                    }
                }
            }
            if ($request->image_remove_id) {
                $image_remove_id = explode(',', $request->image_remove_id);
                $itemImages = Item_images::whereIn('id', $image_remove_id)->where('item_id', $item_id)->get();
                if (count($itemImages) > 0) {
                    $this->remove_imageItem($itemImages);
                    $this->remove_image($itemImages);
                }
            }
            if ($update) {
                return response()->json(['image' => $img, 'message' => 'Item is updated successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.

     *

     * @param \App\Model\Items $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $itemImages = Item_images::where('item_id', $id)->get();
            $items = Items::find($id);
            if ($items) {
                $delete = $items->delete();
                $this->remove_image($itemImages);

                return response()->json(['message' => 'Item is deleted successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get search by keyword data.
     */
    public function searchKeywordDataTest(Request $request)
    {
        try {
            $search = $request->name;
            $user_id = $request->user_id;
            $blockUserList = $this->userBlockList($user_id);
            array_push($blockUserList, (int) $user_id);
            $datas = Items::with(['category'])->select(['id', 'category_id', 'name'])->whereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhere('name', 'LIKE', '%'.$search.'%')
            ->where('user_id', '!=', $user_id)
            ->where('is_sold', '=', 0)
            ->whereNotIn('user_id', $blockUserList)
            ->orderBy('name', 'asc')->get();
            if (count($datas) > 0) {
                $datas->map(function ($item, $key) {
                    $catName = $item->category ? $item->category->name : '';
                    $item['category_name'] = $this->get_category_breadcrumb($item->category->parent_id, $catName, $catName);

                    return $item;
                });

                return response()->json(['message' => 'Get data successfully.', 'data' => $datas], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Data not found.', 'data' => []], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function imageTextSearch(string $text)
    {
        $data = Item_images::search($text)->get();
        $plucked = $data->pluck('item_id');

        return $plucked->all();
    }

    public function searchKeywordMultipleData(Request $data)
    {
        try {
            $request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }
            $page = 1;
            if (isset($request['page']) && !empty($request['page'])) {
                $page = $request['page'];
            }
            $search = $request['name'];
            $user_id = $request['user_id'];
            $item_ids = $this->imageTextSearch($search);
            $blockUserList = $this->userBlockList($user_id);
            array_push($blockUserList, (int) $user_id);
            $totalItemParPage = 20;
            $items = $this->item_data($user_id);
            $itemsAll = $this->filterData($request, $items);
            $itemsAll->whereNotIn('user_id', $blockUserList)->whereIn('id', $item_ids)->where('is_sold', '!=', 1);
            $itemscountData = $itemsAll->get();
            $itemsData = $itemsAll->paginate($totalItemParPage);
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $user_id);
            if (count($itemsDatas) > 0) {
                $allData['items'] = $itemsDatas;
                $allData['totalPage'] = $this->totalPage($itemscountData, $totalItemParPage);

                return response()->json(['message' => 'Get data successfully.', 'data' => $allData], Response::HTTP_OK);
            } else {
                $allData['items'] = [];
                $allData['totalPage'] = 1;

                return response()->json(['message' => 'Data not found.', 'data' => $allData], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchKeywordData(Request $request)
    {
        try {
            $search = $request->name;
            $user_id = $request->user_id;
            $blockUserList = $this->userBlockList($user_id);
            array_push($blockUserList, (int) $user_id);
            $strBlockUserList = implode(', ', $blockUserList);
            $data = DB::select(
              'call searchKeywordData("'.$strBlockUserList.'","'.$search.'")'
            );
            $allItems = [];
            foreach ($data as $key => $item) {
                $items['id'] = $item->id;
                $items['name'] = $item->name;
                $items['category_id'] = $item->category_id;
                $catName = $item->cat_name ? $item->cat_name : '';
                $items['category_name'] = $this->get_category_breadcrumb($item->parent_id, $catName, $catName);
                $items['category'] = array('id' => $item->cat_id, 'name' => $item->cat_name, 'parent_id' => $item->parent_id);
                $allItems[] = $items;
            }
            if (count($allItems) > 0) {
                return response()->json(['message' => 'Get data successfully.', 'data' => $allItems], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Data not found.', 'data' => []], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * mark as sold item.
     */
    public function markAsSold(Request $data, $id)
    {
        try {
            $req['is_sold'] = 1;
            $findData = Items::where(['id' => $id, 'is_sold' => 1])->first();
            if ($findData) {
                return response()->json(['message' => 'Already marked as sold.'], Response::HTTP_FOUND);
            }
            $update = Items::where(['id' => $id, 'user_id' => $data['user_id']])->update($req);
            if ($update) {
                return response()->json(['message' => 'Item is marked as sold.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_OK);
            }
        } catch (Exceptions $e) {
            return response()->json(['data' => [], 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all item according to type without login user data.
     */
    public function allData(Request $data, $id)
    {
        try {
            
            /*$request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }*/
            
            $request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }
            
            $page = 1;
            if (isset($request['page']) && !empty($request['page'])) {
                $page = $request['page'];
            }
            $type = 0;
            if (isset($request['type']) && !empty($request['type'])) {
                $type = $request['type'];
            }
            
            $country_code = $request['country_code'];

            $blockUserList = $this->userBlockList($id);
            array_push($blockUserList, (int) $id);
            $user_category = $this->selectedCategory($id);
            $unSelected_category = $this->unSelectedCategory($user_category);
            $allCategory = array_merge($user_category, $unSelected_category);
            $totalItemParPage = 20;
            $items = $this->item_data($id);
            $itemsAll = $this->filterData($request, $items);
            $itemsAll->whereNotIn('user_id', $blockUserList)->where('is_sold', '!=', 1)->where('type', '=', $type);
            //$sortData = $this->sortData($request, $itemsAll);
            $itemscountData = $itemsAll->get();
            if ($type == 1 || $type == 2) {
                $itemsData = $itemsAll->where('country_code', '=', $country_code)->orderBy('items.created_at', 'DESC')->paginate($totalItemParPage);
            } else {
                $itemsData = $itemsAll->where('country_code', '=', $country_code)->orderBy(DB::raw('FIELD(`category_id`, '.implode(',', $allCategory).')'))->paginate($totalItemParPage);
            }

            //dd(DB::getQueryLog());
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $id);
            $allData['items'] = $itemsDatas;
            $allData['totalPage'] = $this->totalPage($itemscountData, $totalItemParPage);
            $allData = $this->selectedHeader($allData, $type, $page, $id, $allCategory, $blockUserList);

            return response()->json(['data' => $allData, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get car and property filter data.
     */
    public function carAndPropertyFilterData(Request $request, $id)
    {
        try {
            $blockUserList = $this->userBlockList($id);
            array_push($blockUserList, (int) $id);
            
            // dd($blockUserList);
            
            $type = isset($request->type) ? $request->type : 0;
            $totalItemParPage = 20;
            $items = $this->item_data($id);
            $itemsFilterData = $this->filterData($request, $items);
            $itemsAllData = $itemsFilterData->whereNotIn('user_id', $blockUserList)->where('is_sold', '!=', 1)->where('type', '=', $type);
            $sortData = $itemsAllData->orderBy('id', 'desc');
            $itemsDatacount = $sortData->get();
            $itemsData = $sortData->paginate($totalItemParPage);
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $id);
            if (count($itemsDatas)) {
                $allData['items'] = $itemsDatas;
                $allData['totalPage'] = $this->totalPage($itemsDatacount, $totalItemParPage);

                return response()->json(['data' => $allData, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                $allData['items'] = [];
                $allData['totalPage'] = 1;

                return response()->json(['data' => $allData, 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all category api filter.
     */
    public function categoryFilterData(Request $data, $id)
    {
        
        try {
            $request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }
           
            
            $blockUserList = $this->userBlockList($id);
            array_push($blockUserList, (int) $id);
            $allData = [];
            $category_id = $request['category_id'];
            
            
            $country_code = $request['country_code'];
            //$country_id = $request['country_id'];
            $search = $request['search'];
            $allData['subCategoryList'] = [];
            if (isset($request['sort_no']) && $request['sort_no'] == 1 && $request['latitude'] == '' && $request['longitude'] == '') {
                return response()->json(['message' => 'Latitude and Longitude required'], Response::HTTP_BAD_REQUEST);
            }
            if (isset($request['type']) && $request['type'] == '0') {
                $allData['subCategoryList'] = $this->subCategories($category_id);
                $categoryId = $this->subCategoriesId($category_id);
                
            } else {
                $categoryId = explode(' ', $category_id);
            }
            //print_r($categoryId); die;
            if(isset($request['category_id']) && !empty($categoryId))
            {$this->userViewCategoryInsert($id, $categoryId);
            }
            //die('ddd');
            $totalItemParPage = 20;
            $items = $this->item_data($id);
            
            $itemsAllData = array();
           if(isset($request['category_id']) && !empty($categoryId))
            {
               $filterData = $this->filterCategoryData($categoryId, $items);
               $itemsAll = $this->filterData($request, $filterData);
              $itemsAllData = $itemsAll->whereNotIn('user_id', $blockUserList)->where('is_sold', '!=', 1)->where('country_code', '=', $country_code);
            }
            
            if(!empty($itemsAllData)){
            $itemsAllData = $itemsAllData->Where('name', 'LIKE', '%'.$search.'%');
            }else{//die('ddddddd');
                $itemsAllData = $items->Where('name', 'LIKE', '%'.$search.'%')->where('is_sold', '!=', 1)->where('country_code', '=', $country_code);
            }
            
            
            //if(count($categoryId) <= 0){
                //$itemsAllData = $itemsAllData->Where('category_id', '=', $cat_id);
            //}
            $sortSearchData = $itemsAllData;
            if (isset($request['item_id']) && !empty($request['item_id'])) {
                $item_id = $request['item_id'];
                $sortSearchData = $itemsAllData->orderBy(DB::raw('FIELD(`id`, '.$item_id.')'), 'desc');
            }
            $sortData = $this->sortData($request, $sortSearchData);
            $itemsDatacount = $sortData->get();
            $itemsData = $sortData->paginate($totalItemParPage);
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $id);
            if (count($itemsDatas) > 0) {
                $allData['items'] = $itemsDatas;
                $allData['totalPage'] = $this->totalPage($itemsDatacount, $totalItemParPage);

                return response()->json(['message' => 'Get data successfully.', 'data' => $allData], Response::HTTP_OK);
            } else {
                $allData['items'] = [];
                $allData['totalPage'] = 1;

                return response()->json(['message' => 'Data not found.', 'data' => $allData], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function categoryFilterData_android(Request $data, $id)
    {
       
        try 
        {
            $request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }
           
            
            $blockUserList = $this->userBlockList($id);
            array_push($blockUserList, (int) $id);
            $allData = [];
            
            
            $totalItemParPage = 20;
            $items = $this->item_data($id);
            
            $country_code = $request['country_code'];
        
            $search = $request['search'];
            $allData['subCategoryList'] = [];
            
            $itemsAllData = $items->Where('name', 'LIKE', '%'.$search.'%')->where('is_sold', '!=', 1)->where('country_code', '=', $country_code);
            
            
            $sortSearchData = $itemsAllData;
            if (isset($request['item_id']) && !empty($request['item_id'])) {
                $item_id = $request['item_id'];
                $sortSearchData = $itemsAllData->orderBy(DB::raw('FIELD(`id`, '.$item_id.')'), 'desc');
            }
            $sortData = $this->sortData($request, $sortSearchData);
            $itemsDatacount = $sortData->get();
            $itemsData = $sortData->paginate($totalItemParPage);
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $id);
            if (count($itemsDatas) > 0) {
                $allData['items'] = $itemsDatas;
                $allData['totalPage'] = $this->totalPage($itemsDatacount, $totalItemParPage);

                return response()->json(['message' => 'Get data successfully.', 'data' => $allData], Response::HTTP_OK);
            } else {
                $allData['items'] = [];
                $allData['totalPage'] = 1;

                return response()->json(['message' => 'Data not found.', 'data' => $allData], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get banner details.
     */
    public function bannerDetails($id, $category_id)
    {
        try {
            $allDatas = [];
            $banner = $this->bannerAccordingtoUser($id);
            $totalItemParPage = 20;
            $items = $this->item_data($id);
            $itemsAllData = $items->where('is_sold', '!=', 1)->where('category_id', '=', $category_id)->where('user_id', $id);
            $sortData = $itemsAllData->orderBy('id', 'desc');
            $itemsDatacount = $sortData->get();
            $itemsData = $sortData->paginate($totalItemParPage);
            $itemsDatas = $this->dataService->get_selectedField($itemsData, $id);
            $allDatas['banner'] = $banner;
            $allDatas['items'] = $itemsDatas;
            $allDatas['totalPage'] = $this->totalPage($itemsDatacount, $totalItemParPage);

            return response()->json(['data' => $allDatas, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * chat image uploaded.
     */
    public function chatImageUpload(Request $request)
    {
        try {
            if ($request->hasfile('chat_image')) {
                $images = $request->file('chat_image');
                $filename = trim(time().$images->getClientOriginalName());
                $images = $images->move(public_path().'/images/chats/', $filename);
                $fileUpload['image'] = 'public/images/chats/'.$filename;

                return response()->json(['data' => $fileUpload, 'message' => 'File uploaded successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please uploaded file.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get selected item.
     */
    public function getSelectedItemData($item_id, $user_id)
    {
        return Items::withCount(['items_like'])->with(['items_images', 'user', 'Likes' => function ($query) use ($user_id) {
            return $query->where('user_id', $user_id)->first();
        }])->where('id', '=', $item_id)->get();
    }

    /**
     * Get user favourite data.
     */
    public function getUserFavouriteData($id)
    {
        try {
            $items = $this->item_data($id);
            $itemData = $items->join('items_like', 'items.id', '=', 'items_like.item_id')->where('items_like.user_id', $id)
                        ->orderBy('items_like.created_at', 'DESC')
                        ->get();

            $itemsDatas = $this->dataService->get_favourite_selectedField($itemData, $id);
            if (count($itemsDatas) > 0) {
                $data['items'] = $itemsDatas;

                return response()->json(['data' => $data, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                $data['items'] = [];

                return response()->json(['message' => 'Data not found.', 'data' => $data], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * user view category insert.
     */
    public static function userViewCategoryInsert($user_id, $categoryIds)
    {
        foreach ($categoryIds as $key => $value) {
            $data['user_id'] = $user_id;
            $data['category_id'] = $value;
            $findData = user_category::where([['user_id', '=', $user_id], ['category_id', '=', $value]])->increment('count');
            if (!$findData) {
                user_category::insert($data);
            }
        }

        return true;
    }

    /**
     * total page in item.
     */
    public static function totalPage($data, $totalItemParPage)
    {
        $totalItems = $data->count();
        $totalpages = intval($totalItems / $totalItemParPage);
        $mod = ($totalItems % $totalItemParPage) > 0 ? 1 : 0;

        return $totalPage = $totalpages + $mod;
    }

    /**
     * get all comments particular item.
     */
    public static function get_allcomments($id)
    {
        return comments::select(['users.username', 'users.profile_pic', 'comments.id', 'comments.user_id', 'comments.comment', 'comments.created_at'])->rightjoin('users', 'users.id', '=', 'comments.user_id')->where('item_id', $id)->orderBy('comments.created_at')->get();
    }

    /**
     * get category breadcrumb.
     */
    public function get_category_breadcrumb($category_id, $categroy_name, $breadcrumb)
    {
        $categoryData = Category::where('id', $category_id)->first();
        if ($categoryData->parent_id != 0) {
            $this->get_category_breadcrumb($categoryData->id, $categoryData->name, $breadcrumb);
        }
        if ($breadcrumb !== $categoryData->name) {
            $breadcrumb = $categoryData->name.' > '.$breadcrumb;
        }

        return $breadcrumb;
    }

    /**
     * Remove item images into folder.
     */
    public static function remove_image($itemImages)
    {
        foreach ($itemImages as $key => $value) {
            $url = str_replace('public/', '', $value->url);
            if (File::exists(public_path($url))) {
                File::delete(public_path($url));
            }
        }
    }

    /**
     * Remove item images.
     */
    public static function remove_imageItem($itemImages)
    {
        foreach ($itemImages as $key => $value) {
            $Item_images = Item_images::find($value->id);
            $delete = $Item_images->delete();
        }
    }

    /**
     * Get all advertisment.
     */
    public static function get_advertisments()
    { 
        $datas = array();
        $uads = User_purchase_advertisement::select('id', 'user_id', 'category_id', 'image')->where('valid_upto','>',date('Y-m-d H:i:s'))->orderBy('updated_at', 'DESC')->where('status',1)->take(5)->get();
        
        foreach ($uads as $ad) {
            $adv = array();
            $adv['id'] = $ad['id'];
            $adv['user_id'] = $ad['user_id'];
            $adv['category_id'] = $ad['category_id'];
            $adv['image'] = $ad['image'];
            array_push($datas, $adv);
        }
        $ads = Advertisements::get();
        foreach ($ads as $ad) {
            $adv = array();
            $adv['id'] = $ad['id'];
            $adv['user_id'] = $ad['user_id'];
            $adv['category_id'] = $ad['category_id'];
            $adv['image'] = $ad['image'];
            array_push($datas, $adv);
        }
        return $datas;
    }

    /**
     * get banner according to user.
     */
    public static function bannerAccordingtoUser($id)
    {
        return User_purchase_advertisement::select('id', 'user_id', 'category_id', 'image')->where('status',1)->where('user_id', $id)->get();
    }

    /**
     * Get all items data.
     */
    public static function item_data($id)
    {
        return Items::withCount(['items_like'])->with(['items_images', 'user', 'Likes' => function ($query) use ($id) {
            return $query->where('user_id', $id)->first();
        }]);
    }

    /**
     * get user block list.
     */
    public static function userBlockList($id)
    {
        $blockList = user_block::select(DB::raw('(CASE WHEN user_id  = '.$id.' THEN user_block_id  ELSE user_id  END) AS user_id'))
            ->where(function ($query) use ($id) {
                $query->where('user_id', '=', $id)->orWhere('user_block_id', '=', $id);
            })->get('user_id');
        $plucked = $blockList->pluck('user_id');

        $deactivatedList = User::where('is_active', 0)->get(['id']);
        $pluckIds = $deactivatedList->pluck('id');

        return array_merge($pluckIds->all(), $plucked->all());
    }

    /**
     * Category filter data.
     */
    public static function filterCategoryData($categoryId, $items)
    {
        return $items->whereIn('category_id', $categoryId);
    }

    /**
     * get subCategories.
     */
    public static function subCategories(int $id)
    {
        return Category::select('image', 'name', 'id')->where('parent_id', $id)->get();
    }

    /**
     * get subcategries ids.
     */
    public static function subCategoriesId(int $id)
    {
        $categories = Category::select('id')->where('parent_id', $id)->get();
        $allData = $categories->map(function ($item, $key) {
            return $items[] = $item->id;
        });

        return $allData;
    }

    /**
     * get selected category id.
     */
    public static function selectedCategory(int $id)
    {
        $categories = user_category::select('categories.id')
                    ->rightjoin('categories', function ($join) {
                        $join->on('categories.id', '=', 'user_categories.category_id')
                      ->orOn('categories.parent_id', '=', 'user_categories.category_id');
                    })
                    ->where('user_categories.user_id', $id)->orderBy('user_categories.updated_at', 'desc')->get();
        $plucked = $categories->pluck('id');

        return $plucked->all();
    }

    /**
     * get unSelected category id.
     */
    public static function unSelectedCategory(array $ids)
    {
        $allCategory = Category::select('id')->whereNotIn('id', $ids)->get();
        $plucked = $allCategory->pluck('id');

        return $plucked->all();
    }

    /**
     * get daily pic.
     */
    public function get_dailyPic(array $allData, int $id, array $allcat, array $allUserBlock)
    {
        $data = $this->item_data($id);
        $data->whereNotIn('user_id', $allUserBlock)->where('is_sold', '!=', 1);
        $allDailyPic = $data->orderBy(DB::raw('FIELD(`category_id`, '.implode(',', $allcat).')'))->limit(10)->get();
        $daily_pic = $this->dataService->get_selectedField($allDailyPic, $id);
        $allData['daily_pic'] = $daily_pic;

        return $allData;
    }

    /**
     * Get header category and advertisments.
     */
    public function get_headersData(array $allData)
    {
        $categories = Categories::getCategories();
        $advertisements = $this->get_advertisments();
        $allData['advertisments'] = $advertisements;
        $allData['categories'] = $categories;

        return $allData;
    }

    /**
     * Get all filter data.
     */
    public static function filterData($request, $items)
    {
        if (isset($request['brand_id']) && !empty($request['brand_id'])) {
            $items->where('brand_id', $request['brand_id']);
        }
        if (isset($request['car_type_id']) && !empty($request['car_type_id'])) {
            $items->where('car_type_id', $request['car_type_id']);
        }
        if (isset($request['zone_id']) && !empty($request['zone_id'])) {
            $items->where('zone_id', $request['zone_id']);
        }
        if (isset($request['meet_up']) && !empty($request['meet_up'])) {
            $items->where('meet_up', 1);
        }
        if (isset($request['item_type']) && !empty($request['item_type'])) {
            $item_type = $request['item_type'];
            $items->where('item_type', $item_type);
        }
        if ((isset($request['min_price']) && !empty($request['min_price'])) && (isset($request['max_price']) && !empty($request['max_price']))) {
            $min_price = $request['min_price'];
            $max_price = $request['max_price'];
            $items->whereBetween('price', [$min_price, $max_price]);
        }
        if ((isset($request['latitude']) && !empty($request['latitude'])) && (isset($request['longitude']) && !empty($request['longitude']))) {
            $latitude = $request['latitude'];
            $longitude = $request['longitude'];
            $items->select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
              ->having('distance', '<', 25)
              ->where('meet_up', 1);
        }
        if ((isset($request['price_no']) && !empty($request['price_no']))) {
            $price_no = $request['price_no'];
            if ($price_no == 1) {
                $items->where('price', '<', '15000');
            } elseif ($price_no == 2) {
                $items->where('price', '>', '15000');
            } elseif ($price_no == 3) {
                $items->where('price', '<', '25000');
            } else {
                $items->where('price', '>', '25000');
            }
        }

        return $items;
    }

    /**
     * Get all filter data.
     */
    public static function sortData($request, $items)
    {
        if (isset($request['sort_no']) && !empty($request['sort_no'])) {
            if ($request['sort_no'] == '1') {
                $items->orderBy('distance', 'desc');
            } elseif ($request['sort_no'] == '2') {
                $items->orderBy('view_count', 'desc');
            } elseif ($request['sort_no'] == '3') {
                $items->orderBy('created_at', 'desc');
            } elseif ($request['sort_no'] == '4') {
                $items->orderBy('price', 'desc');
            } elseif ($request['sort_no'] == '5') {
                $items->orderBy('price', 'asc');
            } else {
                // $items->orderBy('id', 'desc');
            }
        }

        return $items;
    }

    /**
     * Item additional information.
     */
    public static function additional_info($id)
    {
        $additional_info = items_details::where('item_id', '=', $id)->get();
        $allData = [];
        if ($additional_info) {
            foreach ($additional_info as $key => $value) {
                $allData[$value->meta_key] = $value->meta_value;
            }
        }

        return  (object) $allData;
    }

    /**
     * update additional information.
     */
    public static function additionalInfo_update(String $field_value, String $field_name, int $item_id)
    {
        $reqData['meta_value'] = $field_value;

        return items_details::where('item_id', $item_id)->where('meta_key', $field_name)->update($reqData);
    }

    /**
     * save additional information.
     */
    public static function additionalInfo_save(String $field_value, String $field_name, int $id)
    {
        $additional_info = new items_details();
        $additional_info->item_id = $id;
        $additional_info->meta_key = $field_name;
        $additional_info->meta_value = $field_value;

        return $additional_info->save();
    }

    // public function multidata(Request $request){

    //   $dataList = json_decode($request->label,true);
    //   $data = json_decode(json_encode($dataList));
    //   foreach ($data as $key => $value) {
    //     echo json_encode($value->text);
    //   }

    // }

    /**
     * save item images.
     */
    public static function itemImage_save(array $images, int $id, string $labels = '')
    {
        $dataList = json_decode($labels, true);
        $dataLabel = json_decode(json_encode($dataList));
        $i = 0;
        $firstImage = array();
        foreach ($images as $file) {
            $filename = time().$file->getClientOriginalName();
            $images = $file->move(public_path().'/images/items/', $filename);
            $fileUpload = 'public/images/items/'.$filename;
            $item_images = new Item_images();
            $item_images->item_id = $id;
            $item_images->url = $fileUpload;
            $item_images->label = $dataLabel[$i]->text;
            $item_images->save();
            ++$i;
            array_push($firstImage,$fileUpload);
        }

        return $firstImage;
    }

    /**
     * Dashboard header.
     */
    public function dashboard_header(array $allData, int $page = 1, int $id, array $allCategory, array $blockUserList)
    {
        $allData['advertisments'] = [];
        $allData['categories'] = [];
        $allData['daily_pic'] = [];
        if ($page <= 1) {
            $allData = $this->get_headersData($allData);
            $allData = $this->get_dailyPic($allData, $id, $allCategory, $blockUserList);
        }

        return $allData;
    }

    /**
     * car listing header.
     */
    public function car_header(array $allData, int $page = 1)
    {
        $allData['brands'] = [];
        $allData['car_types'] = [];
        if ($page <= 1) {
            $allData['brands'] = brands::all();
            $allData['car_types'] = cars_type::all();
        }

        return $allData;
    }

    /**
     * property listing header.
     */
    public function property_header($allData, int $page = 1)
    {
        return $allData;
    }

    /**
     * Header selected according to type.
     */
    public function selectedHeader(array $allData, int $type = 0, int $page = 1, int $id, array $allCategory, array $blockUserList)
    {
        if ($type == 1) {
            return $this->car_header($allData, $page);
        } elseif ($type == 2) {
            return $this->property_header($allData, $page);
        } else {
            return $this->dashboard_header($allData, $page, $id, $allCategory, $blockUserList);
        }
    }

    /**
     * get last offers.
     */
    public static function getLastOffers($itemId, $userId)
    {
        return DB::table('user_requests')->where('item_id', $itemId)->where('sender_id', $userId)->orderBy('created_at', 'desc')->first();
        // dd(DB::getQueryLog());
    }

    /**
     * get offers check.
     */
    public static function checkOffers($itemId, $userId)
    {
        return DB::table('user_requests')->where('item_id', $itemId)->where('sender_id', $userId)->where('status', '!=', 5)->count();
        // dd(DB::getQueryLog());
    }

    /**
     * check user rated or not.
     */
    public static function userRate($itemId, $userId)
    {
        return Rating::where(['item_id' => $itemId, 'user_id' => $userId])->count();
    }

    /**
     * get item chat count.
     */
    public static function getItemChatCount($itemId, $user_id)
    {
        return DB::table('user_requests')->where('item_id', $itemId)->where(function ($query) use ($user_id) {
            $query->where('sender_id', '=', $user_id)->orWhere('receiver_id', '=', $user_id);
        })->count();
    }

    /**
     * get Item Message Count.
     */
    public static function getItemMessageCount($itemId, $user_id)
    {
        return DB::table('chat_users')->where('item_id', $itemId)->where('is_read', 0)->where(function ($query) use ($user_id) {
            $query->where('sender_id', '=', $user_id)->orWhere('receiver_id', '=', $user_id);
        })->count();
    }

    /**
     * get user device info.
     */
    public function getUserDeviceInfo(array $userIds, $item_id = null, $from_id = null, $itemObj = null)
    {
        $userData = User::select('id', 'device_id', 'device_type')->searchWhereIn($userIds)->get();
        foreach ($userData as $value) {
            $notificationController = new NotificationController();
            $message = 'New item is added by your friend.';
            $device_type = $value->device_type;
            $device_id = $value->device_id;
            if (!empty($device_type) && !empty($device_id)) {
                $from = User::find($from_id);
                $data = array();
                $data['title'] = ucfirst($from->username);
                if ($item_id) {
                    $data['item_id'] = $item_id;
                }
                $type = 1;
                $send = $notificationController->notificationSend($device_type, $device_id, $message, $type, $data);
                if ($send) {
                    $notiActivity = new NotificationActivity();
                    $notiActivity->title = ucfirst($from->username);
                    $notiActivity->message = $message;
                    $notiActivity->type = $type;
                    $notiActivity->from = $from_id;
                    $notiActivity->to = 0;
                    if ($notiActivity->save()) {
                        $itemObj->notification_id = $notiActivity->id;
                        $itemObj->save();
                    }
                }
            }
        }

        return true;
    }

    /**
     * get according to category user_id.
     */
    public static function getCategoryUserId(Int $category_id, Int $user_id)
    {
        $data = user_category::search($category_id)->NotUserId($user_id)->get();
        $userId = $data->pluck('user_id');

        return $userId->all();
    }

    public function unionData(Request $data, $id)
    {
        // try {

        $request = json_decode($data->getContent(), true);
        if ($request === null) {
            $request = $data->input();
        }
        //print_r($request['type']);
       // die;
        $blockUserList = $this->userBlockList($id);
        array_push($blockUserList, (int) $id);
        $user_category = $this->selectedCategory($id);
        //$unSelected_category = $this->unSelectedCategory($user_category);
        //$allCategory = array_merge($user_category, $unSelected_category);
        print_r($user_category);
        $totalItemParPage = 20;
        $itemsUnion = $this->item_data($id);
        $itemsAllUnion = $this->filterData($request, $itemsUnion);
        $itemsAllUnions = $itemsAllUnion->whereNotIn('category_id', $user_category);
        $itemsUniones = $itemsAllUnions->whereNotIn('user_id', $blockUserList)->where('is_sold', '!=', 1);
        $items = $this->item_data($id);
        $itemsAll = $this->filterData($request, $items);
        $itemsAll->whereNotIn('user_id', $blockUserList)->where('is_sold', '!=', 1);
        //$sortData = $this->sortData($request, $itemsAll);
        $itemsAll->union($itemsUniones);
        $itemscountData = $itemsAll->get();

        $itemsData = $itemsAll->orderBy(DB::raw('FIELD(`category_id`, '.implode(',', $user_category).')'))->paginate($totalItemParPage);
        $itemsDatas = $this->dataService->get_selectedField($itemscountData, $id);
        $allData['items'] = $itemsDatas;
        $allData['totalPage'] = $this->totalPage($itemscountData, $totalItemParPage);
        $allData['advertisments'] = [];
        $allData['categories'] = [];
        if (isset($request['page']) && $request['page'] <= 1) {
            $allData = $this->get_headersData($allData);
        }

        return response()->json(['data' => $allData, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
        // } catch (Exception $e) {
      //     return response()->json(['message'=> $e], Response::HTTP_INTERNAL_SERVER_ERROR);
      // }
    }

    public function testSearch(Request $request)
    {
        $data = User::search('upendra', ['email' => 10, 'userName' => 20, 'first_name' => 5])->take(10)->get();

        return response()->json(['data' => $data]);
    }

    public function getfollowerId(Int $id, array $userIds = [])
    {
        $data = follow::getFollowerId($id);
        $result = $data->pluck('user_id');
        $restultallId = $result->all();

        $likeData = $this->getLikeUserId($id);

        return array_unique(array_merge(array_merge($userIds, $restultallId), $likeData));
    }

    public function getLikeUserId($id)
    {
        $data = Items::select(['items_like.user_id'])
                    ->where('items.user_id', $id)
                    ->rightjoin('items_like', 'items_like.item_id', '=', 'items.id')
                    ->groupBy('items_like.user_id')->get();
        $result = $data->pluck('user_id');

        return $result->all();
    }

    public function isItemLiked($itemId, $userId){
      return like::where('user_id', $userId)->where('item_id',$itemId)->first();
    }

    public function testData(Request $request)
    {
        $user_id = 2;
        $name = 'test';
        $blockUserList = $this->userBlockList($user_id);
        array_push($blockUserList, (int) $user_id);
        print_r($blockUserList);
        die;
        //       $strBlockUserList = implode (", ", $blockUserList);
        //   	$data = DB::select(
        //     'call select_by_user_id("'.$strBlockUserList.'","'.$name.'")'
        // );
        // $allItems = [];
        // foreach ($data as $key => $item) {
        // 	$items['id'] = $item->id;
        //     $items['name'] = $item->name;
        //     $items['category_id'] = $item->category_id;
        //     $catName = $item->cat_name?$item->cat_name:"";
        //           $items['category_name'] = $this->get_category_breadcrumb($item->parent_id, $catName, $catName);
        //     $items['category'] =  array('id'=>$item->cat_id, 'name'=> $item->cat_name, 'parent_id'=> $item->parent_id);
        //     $allItems[] = $items;
        // }

        // //dd(DB::getQueryLog());
        // return response()->json(['data'=>$allItems], Response::HTTP_OK);
        //$data = DB::select('exec my_stored_procedure('.$user_id.')');
        //print_r((object)$request['additional_info']);die;

        if (isset($request->additional_info) && !empty($request->additional_info)) {
            $details = (object) $request->additional_info;
            foreach ($details as $key => $value) {
                echo $key;
                echo $value;
                // echo $field_name = str_replace("'",'',trim(key($value)));
                // echo $field_value = trim($value["'".$field_name."'"]);
                // $item_id = 14;
                // $additional_info = new items_details();
                // $additional_info->item_id = 15;
                // $additional_info->meta_key = $field_name;
                // $additional_info->meta_value = $field_value;
                // return $additional_info->save();
            }
        }
        die;
    }
}
