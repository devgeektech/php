<?php

/**
 * Created by PhpStorm.

 * User: mobilecoderz

 * Date: 4/10/18

 * Time: 10:11 AM.
 */

namespace App\Services;

use App\Constants\ErrorConstants;
use App\User;
use App\Currencies;

use Keygen\Keygen;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;

class UserService
{
    public function getUniqueApiUserId($length)
    {
        $key = strtoupper(Keygen::alphanum($length)->generate());

        if (!User::where('userId', $key)->exists()) {
            return $key;
        } else {
            return $this->getUniqueApiUserId($length);
        }
    }

    public function verifyJwtRequest($token)
    {
        $user = JWTAuth::authenticate($token);

        if ($user) {
            return [
                'status' => true,

                'user' => $user,
            ];
        } else {
            return [
                'status' => false,

                'user' => '',
            ];
        }
    }

    public function getRawProfileData($userId)
    {
        $userData = User::where('id', $userId)->first();
        if($userData->country_code){
         $currency = Currencies::where('country_code',$userData->country_code)->first();
        }
        $user = [
            'userId' => $userData->id,
            'email' => $userData->email,
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'login_type' => $userData->login_type,
            'social_id' => $userData->social_id,
            'mobile_no' => $userData->mobile_no,
            'website' => $userData->website,
            'city' => $userData->city,
            'state' => $userData->state,
            'country' => $userData->country,
            'country_code' => isset($userData->country_code)? $userData->country_code : "",
            'currency_symbol'=>isset($currency->symbol)? $currency->symbol : "",
            'currency'=>isset($currency->code)? $currency->code : "",
            'city_id' => $userData->city_id,
            'state_id' => $userData->state_id,
            'country_id' => $userData->country_id,
            'bio' => $userData->bio,
            'gender' => $userData->gender,
            'DOB' => $userData->DOB,
            'profile_pic' => $userData->profile_pic,
            'qrCode_image' => $userData->qrCode_image,
            'is_active' => $userData->is_active,
            'is_use' => $userData->is_use,
            'mobile_verified' => $userData->mobile_verified,
            'created_at' => $userData->created_at,
            'updated_by' => $userData->updated_by,
        ];

        return $user;
    }

    // Using in web view

    public function getProfileData($userId)
    {
        $userData = User::where('userId', $userId)->first();
        if($userData->country_code){
         $currency = Currencies::where('country_code',$userData->country_code)->first();
        }
        $user = [
            'userId' => $userData->userId,

            'userName' => $userData->username,

          //  'description' => $userData->description,

          //  'rideTypes' => $userData->rideType,

            'name' => $userData->firstName.' '.$userData->lastName,

            'email' => $userData->email,

            'location' => $userData->zip.' '.$userData->city,
            'city' => $userData->city,
            'state' => $userData->state,
            'country' => $userData->country,
            'country_code' => isset($userData->country_code)? $userData->country_code : "", 
            'currency_symbol'=>isset($currency->symbol)? $currency->symbol : "",
            'currency'=>isset($currency->code)? $currency->code : "",           
            'mobile_no' => $userData->mobile_no,
            'website' => $userData->website,
            'bio' => $userData->bio,
            'gender' => $userData->gender,
            'DOB' => $userData->DOB,
            'profile_pic' => $userData->profile_pic,
            'status' => $userData->isActive,
            'mobile_verified' => $userData->mobile_verified,
            'is_use' => $userData->is_use,

          //  'profileImage' => $userData->profileImage
        ];

        return $user;
    }

    public function verifyUser($data)
    {
        $user = User::where('email', $data['email']);

        //\DB::enableQueryLog();

        //dd(\DB::getQueryLog());

        if ($user->exists()) {
            return [
                'status' => ErrorConstants::INVALID_PASSWORD,

                'message' => __('ApiLang.login.invalidMatch.password'),
            ];
        } else {
            return [
                'status' => Response::HTTP_UNAUTHORIZED,

                'message' => 'Email doesn\'t exist',
            ];
        }
    }
}
