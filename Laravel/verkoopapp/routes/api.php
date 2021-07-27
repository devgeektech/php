<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'V1'], function () {
    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::post('/getfollowerId', 'ItemController@getfollowerId');

        Route::apiResource('/categories', 'CategoriesController');

        Route::apiResource('/items', 'ItemController');

        Route::get('/item_details/{id}/{userId}', 'ItemController@item_details');

        Route::get('/bannerDetails/{id}/{category_id}', 'ItemController@bannerDetails');

        Route::apiResource('/ratings', 'RatingController');

        Route::apiResource('/likes', 'LikeController');

        Route::post('/updateItem', 'ItemController@updateItem');

        Route::post('/dashboard/{id}', 'ItemController@allData');

        Route::put('/unionData/{id}', 'ItemController@unionData');

        Route::put('/markAsSold/{id}', 'ItemController@markAsSold');

        Route::post('/searchKeywordData', 'ItemController@searchKeywordData');

        Route::post('/searchKeywordMultipleData', 'ItemController@searchKeywordMultipleData');

        Route::post('/categoryFilterData/{id}', 'ItemController@categoryFilterData');
        
         Route::post('/categoryFilterData_android/{id}', 'ItemController@categoryFilterData_android');
        

        Route::put('/carAndPropertyFilterData/{id}', 'ItemController@carAndPropertyFilterData');

        Route::get('/getUserFavouriteData/{id}', 'ItemController@getUserFavouriteData');

        Route::post('/chatImageUpload', 'ItemController@chatImageUpload');

        Route::apiResource('/comments', 'CommentsController');

        Route::apiResource('/reports', 'ReportsController');

        Route::get('/getReportList/{type}', 'ReportsController@getReportList');

        Route::apiResource('follows', 'FollowController');

        Route::apiResource('block_users', 'UserBlocksController');

        Route::apiResource('brands', 'BrandsController');

        Route::apiResource('advertisment_plans', 'AdvertisementPlansController');

        Route::apiResource('coin_plans', 'CoinPlansController');

        Route::apiResource('payments', 'PaymentsController');

        Route::post('send_money', 'PaymentsController@sendMoney');

        Route::apiResource('user_coin', 'UserCoinsController');

        Route::post('send_friendCoins', 'UserCoinsController@sendFriendCoins');

        Route::apiResource('userPurchaseAdvertisement', 'UserPurchaseAdvertisementController');

        Route::post('renew_advertisement', 'UserPurchaseAdvertisementController@renewAd');

        Route::get('getBrandWithModels', 'BrandsController@getBrandWithModels');

        Route::apiResource('carsType', 'CarsTypeController');

        Route::post('sendmail', 'Api\UserController@sendmail');

        Route::put('getUserListFollow/{id}', 'FollowController@get_followData');

        Route::get('qr-code', 'FollowController@qrcode');

        Route::post('/searchText', 'Api\UserController@testSearch');

        Route::get('listRatedUserGood/{id}', 'RatingController@listRatedUserGood');

        Route::get('listRatedUserAverage/{id}', 'RatingController@listRatedUserAverage');

        Route::get('listRatedUserBad/{id}', 'RatingController@listRatedUserBad');

        Route::get('get_activity_list/{id}', 'NotificationController@getNotificationActivityList');
        Route::get('currencies', 'CurrencyController@get_currencies');

       Route::get('/state_list/{id}', 'CurrencyController@get_states');
       Route::get('/city_list/{id}', 'CurrencyController@get_cities');
       
        
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('register', 'Api\UserController@register');

        Route::post('login', 'Api\UserController@authenticate');

        Route::post('forgot_password', 'Api\UserController@sendmail');

        Route::group(['middleware' => ['jwt.verify']], function () {
            Route::post('logout', 'Auth\LoginController@logout')->name('mobile_logout');

            Route::post('itemCreateProfileData', 'Api\UserController@itemCreateProfileData');

            Route::get('profileData/{id}', 'Api\UserController@getUserProfileData');

            Route::get('friendInfo/{userId}', 'Api\UserController@getNameProfile');

            Route::post('searchByUserName/{id}', 'Api\UserController@searchByUserName');

            Route::get('profile/{id}', 'Api\UserController@profileData');

            Route::post('selectedUserCategroy', 'Api\UserController@selectedUserCategroy');

            Route::post('profileUpdate', 'Api\UserController@profileUpdate');
            
            Route::post('stripepost', 'Api\UserController@stripePost');
            
            Route::post('authpay', 'Api\UserController@authPay');

            Route::post('changePassword', 'Api\UserController@changePassword');

            Route::put('changePhoneNo/{id}', 'Api\UserController@updatePhoneNo');

            Route::post('mobileVerified', 'Api\UserController@mobileVerified');

            Route::post('updateDeviceInfo', 'Api\UserController@updateDeviceInfo');

            Route::put('deactivate_account', 'Api\UserController@deactivateAccount');
        });

        Route::post('updateCountry', 'Api\UserController@updateCountry');
        
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
