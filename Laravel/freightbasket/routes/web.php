<?php

use Illuminate\Support\Facades\Route;
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);

include('messenger.php');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


Route::get('logout', function ()
{
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');


Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cleared Cache";
});

Route::get('/', 'HomeController@index')->name('gotohome');

Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');

Route::get('/userdashboard','UserDashboardController@index')->name('Udashboard');

Route::get('/myprofile','UserDashboardController@profile')->name('myprofile');
Route::post('/profileupdate','UserDashboardController@profileupdate')->name('profileupdate');
Route::post('/profilepictureupdate','UserDashboardController@profilepictureupdate')->name('profilepictureupdate');
Route::post('/backgroundupdate','UserDashboardController@backgroundupdate')->name('backgroundupdate');
Route::post('/updatepassword','UserDashboardController@updatepassword')->name('updatepassword');
Route::post('/Addcompanyprofile','UserDashboardController@addcompanydetails')->name('addcompany');
Route::post('/updatecompanyprofile','UserDashboardController@updatecompanyprofile')->name('UpdateComPro');
Route::get('managestaff','UserDashboardController@managestaff')->name('managestaff');
Route::get('companyprofile','UserDashboardController@companyprofile')->name('companyprofile');
Route::post('addemployee','UserDashboardController@addemployee')->name('addemployee');
Route::get('checkemail','UserDashboardController@checkemail')->name('checkemail');
Route::post('addoffice','OfficeController@addoffice')->name('addoffice');
Route::post('addaboutcompany','OfficeController@addaboutcompany')->name('aboutcompany');
Route::get('/deleteimage','UserDashboardController@deleteimage');
Route::get('singlestaff/{id}','StaffController@index')->name('singlestaff');
Route::get('deletestaff/{id}','StaffController@deletestaff')->name('deletestaff');

// freights routes

Route::get('freights','FreightController@index')->name('managefreights');
Route::get('freight/add','FreightController@freight')->name('freight');
Route::post('freight/add','FreightController@addfreight')->name('addfreight');
Route::get('freight/delete/{id}','FreightController@deletefreight')->name('deletefreight');
Route::get('freight/view/{id}','FreightController@singlefreight')->name('singlefreight');
Route::get('freight/clone/{id}','FreightController@clonefreight')->name('clonefreight');
Route::get('freight/edit/{id}','FreightController@editfreight')->name('editfreight');
Route::post('feight/update','FreightController@updatefeight')->name('updatefeight');

/*Producer Role start*/
Route::get('user/freights','FreightController@allfreights')->name('allFreights');
/*Producer Role end*/

// Route for shipments
Route::get('shipments','ShipmentController@index')->name('manageshipments');
Route::get('shipments/add','ShipmentController@add_shipmentpage')->name('add_shipment_view');
Route::post('add_good_description','ShipmentController@add_good_description')->name('add_good_description');
Route::get('shipments/add/part/2','ShipmentController@addshipmentpart2')->name('addshipmentpart2');
Route::post('add_billing_form','ShipmentController@add_billing_form')->name('add_billing_form');

/*Routes for shipment ajax*/
Route::get('getvoyage','ShipmentController@getvoyage')->name('getvoyage');

// 404
Route::get('/nopermission', function(){ return back(); })->name('nopermission');

// ONLY ADMIN
Route::group(['prefix'=>'admin','as'=>'admin.','middleware' => ['auth','roles'], 'roles' => ['admin']], function(){
    Route::get('settings/breakingnews','SettingController@breakingNews')->name('settings.breakingnews');
    Route::post('settings/breakingnews/store','SettingController@storeBreakingNews')->name('settings.breakingnews.store');

    Route::resource('advertisements','AdvertisementController')->only(['index','store']);
});

// BOTH EDITOR AND ADMIN
Route::group(['prefix'=>'admin','as'=>'admin.','middleware'=>['auth','roles'],'roles'=>['editor','admin']], function(){
    Route::resource('category','CategoryController');
    Route::resource('news','NewsController');
});

Route::get('/news', 'FrontController@index');
Route::get('/page/category/{slug}', 'FrontController@pageCategory')->name('page.category');
Route::get('/page/news/{slug}', 'FrontController@pageNews')->name('page.news');
Route::get('/page', 'FrontController@pageArchive')->name('page');
Route::get('/page/search', 'FrontController@pageSearch')->name('page.search');

/* Offer in logistic ERP strat here*/
Route::any('offer','ShipmentController@offer')->name('offer');
Route::any('offer/add','ShipmentController@offeradd')->name('offer.add');
Route::any('offer/view/{id}','ShipmentController@offerview')->name('offer.view');

Route::any('offer/get_cost_type','ShipmentController@get_cost_type')->name('offer.get_cost_type');
Route::any('offer/confirm/','PublicController@offerconfirm')->name('offer.offerconfirm');
Route::any('offer/received','ShipmentController@offerreceived')->name('offer.received');
Route::post('offer/invite','ShipmentController@offerinvite')->name('offer.invite');
/* Offer in logistic ERP End here*/

// Routes for customer data
Route::get('customer','CustomerController@index')->name('customer');
Route::get('customer/add','CustomerController@newcustomer')->name('newcustomer');
Route::get('customer/view/{id}','CustomerController@viewcustomer')->name('customer/view');
Route::get('customer/edit/{id}','CustomerController@edit')->name('customer/edit');
Route::get('customer/delete/{id}','CustomerController@delete')->name('customer/delete');
Route::post('customer/add','CustomerController@addcustomer')->name('addcustomer');
Route::post('customer/update','CustomerController@update')->name('customer/update');
// Routes for ajax

/*Offer page ajax functions start here*/
Route::any('getcountry','FreightController@getcountry')->name('getcountry');
Route::any('getportsbyword','FreightController@getportsbyword')->name('getportsbyword');
Route::any('get_freights_by','FreightController@get_freights_by')->name('get_freights_by');
/*Offer page ajax functions End here*/

Route::get('getcity','FreightController@getcity')->name('getcity');
Route::get('getport','FreightController@getport')->name('getport');
Route::get('getportforairport','FreightController@getportforairport')->name('getportforairport');
Route::get('getcityforairport','FreightController@getcityforairport')->name('getcityforairport');
// End of Ajax Routes


/*Vessel start here*/
Route::get('/vessel', 'VesselController@index')->name('vessel');
Route::any('/get_vessel_by', 'VesselController@get_vessel_by')->name('get_vessel_by');
Route::any('/vessel/add', 'VesselController@store');
Route::any('/vessel/schedule', 'VesselController@vschedule')->name('vessel.schedule');
Route::any('/vessel/view/{id}', 'VesselController@v_view')->name('vessel.view');
Route::any('/vessel/edit/{id}', 'VesselController@v_edit')->name('vessel.edit');
Route::any('/vsendmail', 'VesselController@v_send_email')->name('vessel.sendmail');

/*-----------------------------*/
Route::any('/vessel/schedule/add', 'VesselController@vs_add')->name('vessel.schedule.add');
Route::any('/vessel/schedule/view/{id}', 'VesselController@vs_view')->name('vessel.schedule.view');
Route::any('/vessel/schedule/edit/{id}', 'VesselController@vs_edit')->name('vessel.schedule.edit');
Route::any('/vessel/schedule/delete/{id}', 'VesselController@vs_delete')->name('vessel.schedule.delete');
Route::any('/vessel/schedule/clone/{id}', 'VesselController@vs_clone')->name('vessel.schedule.clone');
Route::any('/vssendmail', 'VesselController@vs_send_email')->name('vessel.schedule.sendmail');
/*-----------------------------*/
/*Vessel End here*/

/*Customer-Brokers start*/
Route::get('/user/services','CustomerBrokers@index')->name('customerbrokerservices');
/*Customer-Brokers End*/

/*Find Memebers Search  Start*/
Route::any('/user/memebers/{service}', 'FreightController@getmemebers')->name('user.search.members');
Route::any('/user/member_profile/{user_id}', 'FreightController@member_profile')->name('user.search.member_profile');
Route::post('/member_send_email','FreightController@member_send_email')->name('member_send_email');

Route::any('/user/coprofile/{user_id}', 'UserDashboardController@coprofile')->name('user.coprofile');
/*Find Memebers Search  End*/

/*---------PRODUCER ROLE PAGES START---------*/
Route::resource('user/products','ProductController');

Route::post('user/products/removeimage','ProductController@removeimage');
// Route::get('/user/products','ProductController@index')->name('products');
/*---------PRODUCER ROLE PAGES END---------*/


/* Lashing-Fumigation start*/
Route::get('otherservice/create/{data}','OtherserviceController@create')->name('otherservice.create');
Route::put('otherservice/save','OtherserviceController@store')->name('otherservice.save');
Route::get('otherservice/show/{id}','OtherserviceController@show')->name('otherservice.show');
Route::get('otherservice/edit/{id}','OtherserviceController@edit')->name('otherservice.edit');
Route::put('otherservice/update/{id}','OtherserviceController@update')->name('otherservice.update');
Route::any('otherservice/delete/{id}','OtherserviceController@destroy')->name('otherservice.delete');
Route::post('otherservice/removeimage','OtherserviceController@removeimage');
/* Lashing-Fumigation End*/

/*Timeline data start*/
Route::post('timeline/save','TimelineController@store')->name('timeline.save');
Route::get('timeline/edit/{id}','TimelineController@edit')->name('timeline.edit');
Route::put('timeline/update/{id}','TimelineController@update')->name('timeline.update');
Route::post('timeline/delete','TimelineController@destroy')->name('timeline.delete');

Route::post('timeline/removeimage','TimelineController@removeimage');

Route::get('/post/{id}', 'PostsController@single');
Route::post('/posts/like', 'PostsController@like');
Route::post('/posts/likes', 'PostsController@likes');
Route::post('/posts/comment', 'PostsController@comment');
Route::post('/posts/comments/delete', 'PostsController@deleteComment');
/*Timeline data End*/

/*Friends Request Start*/
Route::post('friend/sendrequest','FriendshipController@send');
Route::post('friend/acceptrequest','FriendshipController@accept');
/*Friends Request End*/

/*Special Rate Request Start*/
Route::group(['prefix'=>'user','as'=>'user.','middleware'=>['auth','roles'],'roles'=>['Service Provider','Producer','Freelancer']], function(){
    Route::get('special-rate','SpecialraterequestController@index')->name('specialrate');
    Route::get('special-rate/addrate/{data}','SpecialraterequestController@create')->name('addrate');
    Route::get('special-rate/show/{id}','SpecialraterequestController@show')->name('specialrate.view');
    Route::get('special-rate/edit/{id}','SpecialraterequestController@edit')->name('specialrate.edit');
    Route::post('special-rate/store','SpecialraterequestController@store')->name('specialrate.store');
    Route::post('special-rate/update/{id}','SpecialraterequestController@update')->name('specialrate.update');
    Route::post('special-rate/destroy','SpecialraterequestController@destroy')->name('specialrate.destroy');
    
    Route::get('special-rate/listing','SpecialraterequestController@listing')->name('specialrate.list');
});
/*Special Rate Request End*/

Route::get('payment', 'PayPalController@payment')->name('payment');
Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'PayPalController@success')->name('payment.success');

Route::get('stripe', array('as' => 'stripe.index','uses' => 'StripeController@index',));