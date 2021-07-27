<?php

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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/about', function () {
    return view('app.about');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/termsServices', function () {
    return view('termsServices');
});

Route::get('/app/termsOfConditions', function () {
    return view('app.terms-of-condition');
});
Route::get('/app/privacyPolicy', function () {
    return view('app.privacy-policy');
});
Route::get('/app/helpCenter', function () {
    return view('app.help-center');
});
Route::get('/app/contactUs', function () {
    return view('app.contact-us');
});
Route::get('/app/about', function () {
    return view('app.about');
});
Route::get('/app/privacySettings', function () {
    return view('app.data-and-privacy-settings');
});

/*
 * Admin Panel Views
 */
Route::group(['middleware' => ['jwt.route']], function () {
    Route::get('admin/dashboard', function () {
        return view('dashboard');
    });

    Route::get('admin/users', 'Web\UsersController@getUsersList');

    Route::get('admin/user/create', function () {
        return view('users.create');
    });

    Route::get('admin/user/edit/{id}', 'Web\UsersController@getUserDetails');

    Route::get('admin/profile', 'Web\UsersController@getAdminProfile');

    Route::get('admin/payments', 'Web\TransactionController@getAppTransactions');

    Route::get('admin/disputes', 'ReportsController@getDisputesReport');

    Route::get('admin/report', function () {
        return view('admin/sale-report');
    });

    Route::get('admin/items', function () {
        return view('admin/items');
    });

    Route::get('admin/notify', function () {
        return view('admin/notify');
    });

    Route::get('admin/banners', 'AdvertismentController@getAllAdBanners');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/convert-currency','CurrencyController@index');
Route::get('/update-currency-fixer','CurrencyController@fixer_rate_update');
Route::get('/update-currency-apilayer','CurrencyController@apilayer_rate_update');
Route::get('forget/{id}', 'Auth\ForgotPasswordController@forget');

Route::post('updatepass', 'Auth\ForgotPasswordController@updatepass');

Route::get('/success', function () {
    return view('success');
});

Route::get('/error', function () {
    return view('error');
});

Route::post('admin_login', 'Auth\LoginController@authenticate');

Route::post('twilio_status', 'Auth\LoginController@twilioStatus');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('is_authorized', 'Auth\LoginController@isAuthorized');

    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('get_admin_dashboard_data', 'Web\DashboardController@getDashboardData');

    Route::post('add_user', 'Web\UsersController@addUser');

    Route::post('update_user', 'Web\UsersController@updateUser');

    Route::post('delete_user', 'Web\UsersController@deleteUser');

    Route::post('update_admin_info', 'Web\UsersController@updateAdminInfo');

    Route::post('update_admin_password', 'Web\UsersController@updateAdminPassword');

    Route::post('get_sales_report', 'Web\TransactionController@getSalesReport');

    Route::post('update_banner_status', 'AdvertismentController@updateBannerStatus');

    Route::post('get_items', 'Web\ItemsController@getItems');

    Route::post('delete_item', 'Web\ItemsController@deleteItem');

    Route::post('send_notification_by_admin', 'NotificationController@sendNotificationByAdmin');
});
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'cache cleared';
});
Route::get('/clear-config', function() {
    Artisan::call('config:clear');
    return 'config cleared';
});
Route::get('/clear-route', function() {
    Artisan::call('route:clear');
    return 'route cleared';
});