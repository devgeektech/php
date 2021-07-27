<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\follow;
use App\user_block;
use App\User_accounts;
use App\Rating;
use App\Currencies;
use App\User_coins;
use JWTAuth;
use Twilio;
use App\Http\Requests\RequestItemCreateProfileData;
use App\Http\Controllers\ResponseController;
use Validator;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\DataService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Exceptions;
use App\user_category;
use App\reports;
use File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Twilio\Rest\Client;
use Config;
use Exception;
use Session;
use Stripe;
use App\Http\Requests\userCoins;
use App\Payments;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class UserController extends ResponseController
{
    public $loginAfterSignUp = true;

    protected $dataService;
    protected $userService;

    public function __construct(DataService $dataService, UserService $userService)
    {
        $this->dataService = $dataService;
        $this->userService = $userService;
        DB::enableQueryLog();
    }

    public function authenticate(Request $request)
    {
        $data = $this->dataService->getData($request);
        $rules = array('login_type' => 'required');
        $validator = Validator::make(collect($data)->toArray(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }
            $responseObject = [
                'message' => $validatorarray,
                'data' => (object) array(),
            ];

            return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
        }

        if ($data['login_type'] == 'social') {
            if (\Auth::attempt(['social_id' => $data['social_id'], 'password' => 123456])) {
                $user = \Auth::user();
                if ($user) {
                    $api_token = $user->api_token;
                    if ($api_token == null) {
                        return $this->_login($request);
                    }
                    try {
                        $user = JWTAuth::toUser($api_token);

                        return response()->json(['data' => $user, 'message' => 'Already logged in.'], Response::HTTP_OK);
                    } catch (JWTException $e) {
                        return $this->respondInternalError('Login unsuccessful. An error occurred while performing the action!');
                    }
                }
            } else {
                $checkEmail = User::where('email', $data['email'])->first();
                if ($checkEmail) {
                    return response()->json(['message' => 'Account already exists with the email associated with this social account.'], Response::HTTP_BAD_REQUEST);
                }
                $data['is_active'] = 1;
                $data['password'] = \Hash::make('123456');
                try {
                    $user = User::create($data);
                    $user_id = $user->id;
                    $account = new User_accounts();
                    $account->user_id = $user_id;
                    $account->save();
                    $imageUrl = $this->qrcode($user_id);
                    $this->updateQrcodeImageUser($user_id, $imageUrl);

                    return $this->_login($request);
                } catch (Exception $e) {
                    return response()->json(['data' => null, 'message' => 'Something went wrong'], Response::HTTP_UNAUTHORIZED);
                }
            }
        } else {
            $rules = array(
                'email' => 'required',
                'password' => 'required|min:6',
            );

            $validator = Validator::make(collect($data)->toArray(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $validator = $errors->all();
                for ($i = 0; $i < count($validator); ++$i) {
                    $validatorarray = $validator[$i];
                }

                $responseObject = [
                    'message' => $validatorarray,
                    'data' => (object) array(),
                ];

                return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
            } else {
                try {
                    if (!$token = JWTAuth::attempt(collect($data)->only(['email', 'password'])->toArray())) {
                        $responseData = $this->userService->verifyUser($request);
                        if (isset($responseData['status'])) {
                            return response()->json(['data' => null, 'message' => $responseData['message']], Response::HTTP_UNAUTHORIZED);
                        } else {
                            return $this->_login($request);
                        }
                    }
                } catch (JWTAuthException $e) {
                    return response()->json(['data' => null, 'message' => __('ApiLang.login.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                try {
                    $user = $this->userService->getRawProfileData(Auth::user()->id);
                    $user_account = $this->user_account(Auth::user()->id);
                    $user['token'] = $token;
                    $user['amount'] = isset($user_account) ? $user_account->amount : 0;
                    $user['coin'] = isset($user_account) ? $user_account->coin : 0;
                    $result['user'] = $user;

                    return response()->json(['data' => $user, 'message' => 'Login successful.'], Response::HTTP_OK);
                } catch (JWTException $e) {
                    return response()->json(['data' => null, 'message' => 'Login unsuccessful. An error occurred while performing the action!'], Response::HTTP_UNAUTHORIZED);
                }
            }
        }
    }

    public function register(Request $request)
    {
        $data = $this->dataService->getData($request);
        $rules = array(
            'username' => 'required|min:2|max:255|unique:users',
            'email' => 'required|email|min:6|max:255|unique:users',
            'country' => 'required|min:2|max:255',
            //'country_code' => 'required',
            'password' => 'required|min:6',
        );
        $validator = Validator::make(collect($data)->toArray(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }
            $responseObject = [
                'message' => $validatorarray,
                'data' => (object) array(),
            ];

            return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
        } else {
            $password = \Hash::make($data['password']);
            $userData = array(
                'username' => $data['username'],
                'email' => $data['email'],
                'country' => $data['country'],
                'country_code' => isset($data['country_code']) ? $data['country_code'] : "",
                'login_type' => 'normal',
                'is_active' => 1,
                'password' => $password,
            );
            $user = User::create($userData);
            $user_id = $user->id;
            $account = new User_accounts();
            $account->user_id = $user_id;
            $account->save();
            $imageUrl = $this->qrcode($user_id);
            $this->updateQrcodeImageUser($user_id, $imageUrl);

            return $this->_login($request);
        }
    }

    private function _login(Request $request)
    {
        $data = $this->dataService->getData($request);
        $rules = array('login_type' => 'required');
        $validator = Validator::make(collect($data)->toArray(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }
            $responseObject = [
                'message' => $validatorarray,
                'data' => (object) array(),
            ];

            return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
        }
        if ($data['login_type'] == 'social') {
            $user = User::where('social_id', '=', $data['social_id'])->first();
            if (!$token = JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(7)->timestamp])) {
                return response()->json(['message' => 'User does not exist!'], Response::HTTP_UNAUTHORIZED);
            }
            $id = $user['id'];
            $users = $this->userService->getRawProfileData($id);
            $user_account = $this->user_account($id);
            $users['amount'] = isset($user_account) ? $user_account->amount : 0;
            $users['coin'] = isset($user_account) ? $user_account->coin : 0;
            $users['token'] = $token;
            $is_active = $users['is_active'];
            $updated_by = $users['updated_by'];
            if ($is_active == 0 && $updated_by == 1) {
                return response()->json(['data' => $users, 'message' => 'Account deactivated, please contact administrator'], Response::HTTP_LOCKED);
            }
            if ($is_active == 0 && $updated_by == 2) {
                User::where('id', $id)->update(['is_active' => 1]);
            }

            return response()->json(['data' => $users, 'message' => 'Login successful!'], Response::HTTP_OK);
        } else {
            try {
                $token = null;
                if (!$jwt_token = JWTAuth::attempt(collect($data)->only(['email', 'password'])->toArray())) {
                    $responseData = $this->userService->verifyUser($request);

                    return response()->json(['data' => null, 'message' => $responseData['message']], Response::HTTP_UNAUTHORIZED);
                }
                $result['message'] = __('ApiLang.login.success');
                $id = Auth::user()->id;
                $user = $this->userService->getRawProfileData($id);
                $is_active = $user['is_active'];
                $updated_by = $user['updated_by'];
                if ($is_active == 0 && $updated_by == 1) {
                    return response()->json(['data' => $users, 'message' => 'Account deactivated, please contact administrator'], Response::HTTP_LOCKED);
                }
                if ($is_active == 0 && $updated_by == 2) {
                    User::where('id', $id)->update(['is_active' => 1]);
                }
                $user_account = $this->user_account($id);
                $user['amount'] = isset($user_account) ? $user_account->amount : 0;
                $user['coin'] = isset($user_account) ? $user_account->coin : 0;
                $user['token'] = $jwt_token ? $jwt_token : '';
                $result['data'] = $user ? $user : (object) array();

                return response()->json($result, Response::HTTP_OK);
            } catch (JWTAuthException $e) {
                return response()->json(['data' => null, 'message' => __('ApiLang.login.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * update device_id request.
     */
    public function updateDeviceInfo(Request $request)
    {
        $data = $this->dataService->getData($request);
        $rules = array(
            'user_id' => 'required',
            'device_id' => 'required',
            'device_type' => 'required',
        );
        $validator = Validator::make(collect($data)->toArray(), $rules);
        if ($validator->fails()) {
            if ($validator->fails()) {
                $errors = $validator->errors();
                $validator = $errors->all();
                for ($i = 0; $i < count($validator); ++$i) {
                    $validatorarray = $validator[$i];
                }
                $responseObject = [
                    'message' => $validatorarray,
                    'data' => (object) array(),
                ];

                return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
            }
        } else {
            $data = $this->updateDeviceId($request->user_id, $request->device_id, $request->device_type);
            if ($data) {
                return response()->json(['message' => 'Device info is updated successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_OK);
            }
        }
    }

    /**
     * update device_id.
     */
    public static function updateDeviceId(Int $id, String $device_id, Int $device_type)
    {
        return User::where('id', $id)->update(['device_id' => $device_id, 'device_type' => $device_type]);
    }

    /**
     * profile update.
     */
    public function profileUpdate(Request $request)
    {
        try {
            $user_id = $request->user_id;
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time().$file->getClientOriginalName();
                $dir = '/images/users/'.$user_id;
                $path = public_path().$dir;
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }
                $images = $file->move($path, $filename);
                $fileUpload = 'public'.$dir.'/'.$filename;
                $data['profile_pic'] = $fileUpload;
            }
            $data['username'] = $request->username;
            $data['first_name'] = $request->first_name;
            $data['last_name'] = $request->last_name;
            $data['mobile_no'] = $request->mobile_no;
            $data['website'] = $request->website;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['country'] = $request->country;
            $data['country_code'] = isset($request->country_code) ?  $request->country_code : "";
            $data['city_id'] = $request->city_id;
            $data['state_id'] = $request->state_id;
            $data['country_id'] = $request->country_id;
            $data['bio'] = $request->bio;
            $data['gender'] = $request->gender;
            $data['DOB'] = $request->DOB;
            $update = User::where('id', $user_id)->update($data);
            if ($update) {
                if ($request->hasFile('profile_pic')) {
                    $allImages = File::allFiles($path);
                    foreach ($allImages as $key => $value) {
                        if ($key < count($allImages) - 1) {
                            unlink($value->getPathname());
                        }
                    }
                }
                $userData = $this->userService->getRawProfileData($user_id);

                return response()->json(['data' => $userData, 'message' => 'Profile is updated successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Please try again.'], Response::HTTP_OK);
            }
        } catch (Exceptions $e) {
            return response()->json(['data' => [], 'message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get user profileData.
     */
    public function profileData($id)
    {
        try {
            $users = $this->profileDataUser($id);
            $items = $users->items;
            $ratingData = $this->userRatingCalculate($id);
            $users['sad'] = isset($ratingData->sad) ? $ratingData->sad : 0;
            $users['average'] = isset($ratingData->average) ? $ratingData->average : 0;
            $users['good'] = isset($ratingData->good) ? $ratingData->good : 0;
            $users['follow_count'] = $this->getFollowUser($id);
            $users['follower_count'] = $this->getFollowerUser($id);
            unset($users->items);
            $userDatas = $users;
            $wallet = User_accounts::select('amount')->where('user_id', $id)->first();
            $payments = Payments::select('currency')->where('user_id', $id)->latest('id')->first();
            $userDatas['currency'] = ((isset($payments->currency))?$payments->currency:'');
            $userDatas['wallet'] = ((isset($wallet->amount))?$wallet->amount:'');
            $userDatas['items'] = $this->dataService->get_selectedField($items, $id);
            $userDatas['reports'] = $this->getUserReportList();
            if ($users) {
                return response()->json(['data' => $userDatas, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * item relative user profile Data.
     */
    public function itemCreateProfileData(RequestItemCreateProfileData $Request)
    {
        try {
            $id = $Request['user_id'];
            $follower_id = $Request['follower_id'];
            $users = $this->profileDataUser($follower_id);
            $items = $users->items;
            $ratingData = $this->userRatingCalculate($follower_id);
            $users['sad'] = isset($ratingData->sad) ? $ratingData->sad : 0;
            $users['average'] = isset($ratingData->average) ? $ratingData->average : 0;
            $users['good'] = isset($ratingData->good) ? $ratingData->good : 0;
            $users['follow_count'] = $this->getFollowUser($follower_id);
            $users['follower_count'] = $this->getFollowerUser($follower_id);
            $users['follower_id'] = $this->getUserFollowerId($follower_id, $id);
            $users['block_id'] = $this->getUserBlockId($id, $follower_id);
            unset($users->items);
            $userDatas = $users;
            $userDatas['items'] = $this->dataService->get_selectedField($items, $id);
            $userDatas['reports'] = $this->getUserReportList();
            if ($users) {
                return response()->json(['data' => $userDatas, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get user profiledata update.
     */
    public function getUserProfileData($id)
    {
        try {
            $users = User::select('id', 'username', 'first_name', 'last_name', 'city', 'state', 'country', 'country_code', 'city_id', 'state_id', 'country_id', 'website', 'bio', 'profile_pic', 'email', 'mobile_no', 'gender', 'DOB')->where('id', $id)->first();
            if ($users) {
                $wallet = User_accounts::select('amount')->where('user_id', $id)->first();
                $payments = Payments::select('currency')->where('user_id', $id)->latest('id')->first();
                $users['currency'] = ((isset($payments->currency))?$payments->currency:'');
                $users['wallet'] = ((isset($wallet->amount))?$wallet->amount:'');
                return response()->json(['data' => $users, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get username and profile.
     */
    public function getNameProfile($userId)
    {
        try {
            $user_id = Crypt::decryptString($userId);
            $users = User::select('id', 'username', 'first_name', 'last_name', 'profile_pic')->where('id', $user_id)->first();
            if ($users) {
                return response()->json(['data' => $users, 'message' => 'Data Get Successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Data not found.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * change password.
     */
    public function changePassword(Request $request)
    {
        try {
            $data = $this->dataService->getData($request);
            $rules = array(
              'current_password' => 'required',
              'new_password' => 'required|min:6',
            );
            $validator = Validator::make(collect($data)->toArray(), $rules);
            if ($validator->fails()) {
                $data['message'] = 'Invalid input parameters';

                return response()->json($data, Response::HTTP_BAD_REQUEST);
            }
            $id = $request['user_id'];
            $userData = User::where('id', '=', $id)->first();
            if (!(Hash::check($request['current_password'], $userData->password))) {
                // The passwords matches
                return response()->json(['data' => [], 'message' => 'Your current password does not matches with the password you provided. Please try again.'], Response::HTTP_BAD_REQUEST);
            }
            if (strcmp($request['current_password'], $request['new_password']) == 0) {
                //Current password and new password are same
                return response()->json(['data' => [], 'message' => 'New Password cannot be same as your current password. Please choose a different password.'], Response::HTTP_BAD_REQUEST);
            }
            //Change Password
            $user = User::where('id', '=', $id)->first();
            $user->password = \Hash::make($request['new_password']);
            $user->save();

            return response()->json(['message' => 'Password changed successfully !'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * set user selected categroy.
     */
    public function selectedUserCategroy(Request $request)
    {
        try {
            $categoryIds = explode(',', $request->category_id);
            $user_id = $request->user_id;
            foreach ($categoryIds as $key => $value) {
                $data['user_id'] = $user_id;
                $data['category_id'] = $value;
                $datas[] = $data;
            }
            $save = user_category::insert($datas);
            if ($save) {
                User::where('id', '=', $user_id)->update(array('is_use' => 1));

                return response()->json(['data' => [], 'message' => 'Category preference is selected successfully.'], Response::HTTP_OK);
            } else {
                return response()->json(['data' => [], 'message' => 'Please try again.'], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search user list by name.
     */
    public function searchByUserName(Request $data, $id)
    {
        $request = json_decode($data->getContent(), true);
            if ($request === null) {
                $request = $data->input();
            }
            
        $username = $request['username'];
        $data = User::select(['id', 'username', 'profile_pic'])->where('username', 'like', '%'.$username.'%')->where('id', '!=', $id)->where('is_active', 1)->get();
        if (count($data) > 0) {
            $allDatas = $this->getWithFollowerId($data, $id);

            return response()->json(['message' => 'Get user search list.', 'data' => $allDatas], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Data not found.', 'data' => []], Response::HTTP_OK);
        }
    }

    /**
     * get search by username with followerId.
     */
    public function getWithFollowerId($users, $id)
    {
        return $users->map(function ($user, $key) use ($id) {
            $user['follower_id'] = $this->getUserFollowerId($user->id, $id);

            return $user;
        });
    }

    /**
     * get user follow.
     */
    public static function getFollowUser($user_id)
    {
        return follow::where('user_id', $user_id)->count();
    }

    /**
     * get user follower.
     */
    public static function getFollowerUser($user_id)
    {
        return follow::where('follower_id', $user_id)->count();
    }

    /**
     * get user profile Data static function.
     */
    public static function profileDataUser($id)
    {
        return User::with(['items', 'items.items_images', 'items.Likes', 'items' => function ($query) {
            $query->withCount('Likes');
        }])->select('id', 'username', 'created_at', 'profile_pic', 'email', 'mobile_no', 'website', 'city', 'state', 'country','country_code', 'city_id', 'state_id', 'country_id')->where('id', $id)->first();
    }

    /**
     * get user report list.
     */
    public static function getUserReportList()
    {
        return reports::where('type', 1)->get();
    }

    /**
     * get user follower id.
     */
    public static function getUserFollowerId($user_id, $follower_id)
    {
        $followData = follow::select('id')->where(['user_id' => $follower_id, 'follower_id' => $user_id])->first();
        if ($followData) {
            return $followData->id;
        }

        return 0;
    }

    /**
     * get user block id.
     */
    public static function getUserBlockId($user_id, $user_block_id)
    {
        $userBlockData = user_block::select('id')->where(['user_id' => $user_id, 'user_block_id' => $user_block_id])->first();
        if ($userBlockData) {
            return $userBlockData->id;
        }

        return 0;
    }

    /**
     * user rating calculate.
     */
    public static function userRatingCalculate($id)
    {
        $data = DB::select("SELECT COUNT(CASE WHEN rating <=2 THEN rating END)as sad,  COUNT(CASE WHEN rating >2 AND rating <4 THEN rating END)as average, COUNT(CASE WHEN rating >=4 THEN rating END)as good FROM `ratings` WHERE rated_user_id = $id");

        return isset($data[0]) ? $data[0] : '';
    }

    //sendmail forget password
    public function sendmail(request $request)
    {
        $baseurl = url('/');
        $email = $request->email;
        $data = DB::table('users')->where('email', $email)->first();
        if ($data) {
            $idd = DB::table('users')->where('email', $email)->select('id')->pluck('id')->first();
            $id = Crypt::encrypt($idd);
            $user = array('email' => $email);
            Mail::send('emails.welcome', ['user' => "$baseurl/forget/$id"], function ($message) use ($user) {
                $message->from('info@verkoopadmin.com', 'VerkoopApp');
                $message->to($user['email'])->subject('Welcome to VerkoopApp');
            });

            return response()->json(['message' => 'Email has been sent'], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Email does not exist'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Change phone number.
     */
    public function updatePhoneNo(Request $request, $id)
    {
        try {
            $sid = config('app.twilio.sid');
            $token = config('app.twilio.token');
            $client = new Client($sid, $token);
            $otp = mt_rand(1000, 9999);
            $mobile_no = $request->mobile_no;
            try {
                $client->messages->create(
                   $mobile_no,
                   [
                       'from' => config('app.twilio.from'),
                       'body' => $otp.' is the One Time Password (OTP) for verification of your Mobile number in Verkoop',
                   ]
               );
                // $res = Twilio::message($mobile_no, 'Your verification code '.$otp);
            } catch (\Twilio\Exceptions\RestException $e) {
                if ($e->getCode() == 20404) {
                    return response()->json(['data' => [], 'message' => 'Please enter valid phone number'], Response::HTTP_NOT_FOUND);
                } elseif ($e->getCode() == 21211) {
                    return response()->json(['data' => [], 'message' => 'Please enter valid country code or phone number'], Response::HTTP_NOT_FOUND);
                } else {
                    return response()->json(['data' => [], 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
            }
            $updated = User::where('id', $id)->update(['otp' => $otp]);
            if ($updated) {
                return response()->json(['message' => 'OTP sent to given number.', 'otp' => $otp], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'OTP not sent.', 'otp' => ''], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function mobileVerified(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $checkUser = User::where(['id' => $user_id, 'otp' => $request->otp])->first();
            if ($checkUser) {
                if (isset($request->mobile_no)) {
                    $req = array('mobile_verified' => 1, 'otp' => '', 'mobile_no' => $request->mobile_no);
                } else {
                    $req = array('mobile_verified' => 1, 'otp' => '');
                }
                $updated = User::where('id', $user_id)->update($req);
                if ($updated) {
                    return response()->json(['message' => 'Mobile verified successfully.'], Response::HTTP_OK);
                } else {
                    return response()->json(['message' => 'Mobile verification failed.'], Response::HTTP_OK);
                }
            } else {
                return response()->json(['message' => 'You entered wrong OTP.'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(['data' => [], 'message' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // qrcode image user

    public static function qrcode(int $id)
    {
        $encrpt = Crypt::encryptString($id);
        $file_name = 'image_'.time().'.png';
        $image_url = 'images/users/qrcode/'.$file_name;
        QrCode::format('png')
                ->merge('public/images/logo.png', 0.2, true)
                ->size(500)->errorCorrection('H')
                ->generate($encrpt, public_path($image_url));

        return 'public/'.$image_url;
    }

    // update user qrcode url

    public static function updateQrcodeImageUser(int $id, String $imageUrl)
    {
        return User::where('id', '=', $id)->update(array('qrCode_image' => $imageUrl));
    }

    public static function user_account($id)
    {
        return User_accounts::where('user_id', $id)->first();
    }

    public function deactivateAccount()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->is_active = 0;
        $user->updated_by = 2;
        $user->save();

        return response()->json(['message' => 'Account deactivated'], Response::HTTP_OK);
    }

    public function testSearch(Request $request)
    {
        echo Crypt::decryptString($request->id);
        die;
        // $data = User::search('upendra', ['email' => 10, 'userName' => 20, 'first_name' => 5])->take(10)->get();
        // return response()->json(['data'=>$data]);
    }

    public function updateCountry(Request $request){
        $data['country'] = $request->country;
        $data['country_code'] = $request->country_code;
        $update = User::where('id', $request->user_id)->update($data);
        if ($update) {
            $currency = Currencies::where('country_code',$request->country_code)->first();
            return response()->json(['message' => 'Country updated successfully.','currency_symbol'=>$currency->symbol,'currency'=>$currency->code], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Country updated failed.','currency_symbol'=>'','currency'=>''], Response::HTTP_OK);
        }

    }
    
    public function stripePost(Request $request)
    {
        try {    
            $data = $this->dataService->getData($request);        
            $rules = array(
                'name' => 'required',
                'card_num' => 'required|digits_between:11,17',
                'cvv' => 'required|digits:3',
                'ex_mon' => 'required|digits:2',
                'ex_year' => 'required|digits_between:1,5',
                'amount' => 'required|numeric|min:0.5',
                'currency' => 'required',
                'user_id' => 'required|numeric',
            );
            $validator = Validator::make(collect($data)->toArray(), $rules);
            if ($validator->fails()) {
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $validator = $errors->all();
                    for ($i = 0; $i < count($validator); ++$i) {
                        $validatorarray = $validator[$i];
                    }
                    $responseObject = [
                        'message' => $validatorarray,
                        'data' => (object) array(),
                    ];
    
                    return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
                }
            } else {
                $user_id = $request->user_id;
                $name = $request->name;
                $card_num = $request->card_num;
                $cvv = $request->cvv;
                $ex_mon = $request->ex_mon;
                $ex_year = $request->ex_year;
                $amount = $request->amount;
                $currency = $request->currency;
                $wallet = User_accounts::select('amount')->where('user_id', $user_id)->first();
                try {
                    //Create Stripe Token
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $response = \Stripe\Token::create(array(
                    "card" => array(
                        "number"    => $card_num,
                        "exp_month" => $ex_mon,
                        "exp_year"  => $ex_year,
                        "cvc"       => $request->input('cvc'),
                        "name"      => $name
                    )));
                    $response_array = $response->__toArray(true);
                    $stripeToken = $response_array['id'];
                    Stripe\Charge::create ([
                        "amount" => $amount,
                        "currency" => $currency,
                        "source" => $stripeToken,
                        "description" => "Wallet Add" 
                    ]);

                    $payment = new Payments();
                    $payment->user_id = $user_id;
                    $payment->amount = $amount;
                    $payment->currency = $currency;
                    $payment->status = 1;
                    $payment->type = 0;
                    $payment->save();

                    User_accounts::where('user_id', $user_id)->increment('amount', $amount);

                    return response()->json(['status'=>'1','wallet'=>$wallet->amount, 'message' => 'Success'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                catch ( \Exception $e ) {
                    return response()->json(['status'=>'0','wallet'=>$wallet->amount, 'message' => 'Token Issue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            
        } catch (Exceptions $e) {
            return response()->json(['data' => [], 'message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function authPay(Request $request)
    {
        try {    
            $data = $this->dataService->getData($request);        
            $rules = array(
                'name' => 'required',
                'card_num' => 'required|digits_between:11,17',
                'cvv' => 'required|digits:3',
                'ex_mon' => 'required|digits:2',
                'ex_year' => 'required|digits_between:1,5',
                'amount' => 'required|numeric|min:0.5',
                'currency' => 'required',
                'user_id' => 'required|numeric',
            );
            $validator = Validator::make(collect($data)->toArray(), $rules);
            if ($validator->fails()) {
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $validator = $errors->all();
                    for ($i = 0; $i < count($validator); ++$i) {
                        $validatorarray = $validator[$i];
                    }
                    $responseObject = [
                        'message' => $validatorarray,
                        'data' => (object) array(),
                    ];
    
                    return response()->json($responseObject, Response::HTTP_BAD_REQUEST);
                }
            } else {
                $user_id = $request->user_id;
                $name = $request->name;
                $card_num = $request->card_num;
                $cvv = $request->cvv;
                $ex_mon = $request->ex_mon;
                $ex_year = $request->ex_year;
                $amount = $request->amount;
                $currency = $request->currency;
                $wallet = User_accounts::select('amount')->where('user_id', $user_id)->first();
                try {
                    /* Create a merchantAuthenticationType object with authentication details
                    retrieved from the constants file */
                    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                    $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
                    $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));

                    // Set the transaction's refId
                    $refId = 'ref' . time();
                    $cardNumber = preg_replace('/\s+/', '', $card_num);

                    // Create the payment data for a credit card
                    $creditCard = new AnetAPI\CreditCardType();
                    $creditCard->setCardNumber($cardNumber);
                    $creditCard->setExpirationDate($ex_year . "-" .$ex_mon);
                    $creditCard->setCardCode($cvv);

                    // Add the payment data to a paymentType object
                    $paymentOne = new AnetAPI\PaymentType();
                    $paymentOne->setCreditCard($creditCard);

                    // Create a TransactionRequestType object and add the previous objects to it
                    $transactionRequestType = new AnetAPI\TransactionRequestType();
                    $transactionRequestType->setTransactionType("authCaptureTransaction");
                    $transactionRequestType->setAmount($amount);
                    $transactionRequestType->setPayment($paymentOne);

                    // Assemble the complete transaction request
                    $requests = new AnetAPI\CreateTransactionRequest();
                    $requests->setMerchantAuthentication($merchantAuthentication);
                    $requests->setRefId($refId);
                    $requests->setTransactionRequest($transactionRequestType);

                    // Create the controller and get the response
                    $controller = new AnetController\CreateTransactionController($requests);
                    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                    if ($response != null) {
                        // Check to see if the API request was successfully received and acted upon
                        if ($response->getMessages()->getResultCode() == "Ok") {
                            // Since the API request was successful, look for a transaction response
                            // and parse it to display the results of authorizing the card
                            $tresponse = $response->getTransactionResponse();
            
                            if ($tresponse != null && $tresponse->getMessages() != null) {
                                $message_text = $tresponse->getMessages()[0]->getDescription().", Transaction ID: " . $tresponse->getTransId();
                                $msg_type = "success_msg";   
                                
                                $payment = new Payments();
                                $payment->user_id = $user_id;
                                $payment->amount = $amount;
                                $payment->currency = $currency;
                                $payment->status = 1;
                                $payment->type = 0;
                                $payment->save();

                                User_accounts::where('user_id', $user_id)->increment('amount', $amount);
                                $wallet = User_accounts::select('amount')->where('user_id', $user_id)->first();

                                return response()->json(['status'=>'1','wallet'=>$wallet->amount, 'message' => 'Success'], Response::HTTP_OK);
                            } else {
                                $message_text = 'There were some issue with the payment. Please try again later.';                                 
            
                                if ($tresponse->getErrors() != null) {
                                    $message_text = $tresponse->getErrors()[0]->getErrorText();                                   
                                }
                                return response()->json(['status'=>'0','wallet'=>$wallet->amount, 'message' => $message_text], Response::HTTP_INTERNAL_SERVER_ERROR);
                            }
                            // Or, print errors if the API request wasn't successful
                        } else {
                            $message_text = 'There were some issue with the payment. Please try again later.'; 
                            $tresponse = $response->getTransactionResponse();            
                            if ($tresponse != null && $tresponse->getErrors() != null) {
                                $message_text = $tresponse->getErrors()[0]->getErrorText();                    
                            } else {
                                $message_text = $response->getMessages()->getMessage()[0]->getText();
                            }
                            return response()->json(['status'=>'0','wallet'=>$wallet->amount, 'message' => $message_text], Response::HTTP_INTERNAL_SERVER_ERROR);               
                        }
                    } else {
                        return response()->json(['status'=>'0','wallet'=>$wallet->amount, 'message' => 'Payment Gateway Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
                catch ( \Exception $e ) {
                    return response()->json(['status'=>'0','wallet'=>$wallet->amount, 'message' => 'Token Issue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            
        } catch (Exceptions $e) {
            return response()->json(['data' => [], 'message' => 'Server not responding.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}