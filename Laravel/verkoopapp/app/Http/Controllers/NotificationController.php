<?php

namespace App\Http\Controllers;

use App\follow;
use App\Item_images;
use App\User;
use App\Items;
use App\NotificationActivity;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function getDeviceInfo(Int $item_id, $message, $message_type, $user_id = '', $data = array())
    {
        $user = Items::getUserId($item_id);
        if (!$user_id) {
            $user_id = $user->user_id;
        }
        $userData = User::getDeviceInfo($user_id);

        return $this->notificationSend($userData->device_type, $userData->device_id, $message, $message_type, $data);
    }

    public function notificationSend($device_type, $device_id, $message, $message_type, $data = array())
    {
        if ($device_type == 1) {
            return $this->pushAndroid($device_id, $message, $message_type, $data);
        } elseif ($device_type == 2) {
            return $this->pushIOS($device_id, $message, $message_type, $data);
        } else {
            return false;
        }
    }

    public function pushAndroid($deviceToken, $mess, $message_type, $data)
    {
        $message['title'] = $data['title'] ? $data['title'] : 'Verkoop';

        $message['body'] = $mess;

        $registrationIds = array($deviceToken); //Replace this with

        $fields = array(
                'registration_ids' => $registrationIds,

                'data' => array('title' => $message['title'], 'message' => $message['body'], 'type' => $message_type),

                'notification' => array('title' => $message['title'], 'body' => $message['body'], 'type' => $message_type),
            );

        foreach ($data as $key => $value) {
            $fields['data'][$key] = $value;
            $fields['notification'][$key] = $value;
        }

        $headers = array(
                'Authorization: key=AIzaSyBIs2y6woqdlf_yoFL7VYAVt5xyi_R6uEQ',

                'Content-Type: application/json',
            );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send'); //For firebase, use https://fcm.googleapis.com/fcm/send

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($result);

        if ($data->success == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function pushIOS($deviceToken, $mess, $message_type, $data)
    {
        $keyfile = storage_path('app/AuthKey_V7KMNYSB28.p8');               // <- Your AuthKey file

        $keyid = 'V7KMNYSB28';                            // <- Your Key ID

        $teamid = 'V9XU4TFBXY';                           // <- Your Team ID (see Developer Portal)

        $bundleid = 'com.mobilecoderz.Verkoop';                // <- Your Bundle ID

        $url = 'https://api.push.apple.com';  // <- development url, or use http://api.push.apple.com for production environment

        $message['title'] = $data['title'] ? $data['title'] : 'Verkoop';

        $message['notificationMessage'] = $mess;

        $token = $deviceToken;
        $iOSbundle['aps']['sound'] = 'default';
        $iOSbundle['aps']['alert']['title'] = $message['title'];
        $iOSbundle['aps']['alert']['body'] = $message['notificationMessage'];
        $iOSbundle['aps']['alert']['type'] = $message_type;
        foreach ($data as $key => $value) {
            $iOSbundle['aps']['alert'][$key] = $value;
        }

        $message = json_encode($iOSbundle);

        // $message = '{"aps":{"alert":{"title": "'.$message['title'].'","body":"'.$message['notificationMessage'].'", "type": "'.$message_type.'"},"sound":"default"}}';

        $key = openssl_pkey_get_private('file://'.$keyfile);

        $header = ['alg' => 'ES256', 'kid' => $keyid];

        $claims = ['iss' => $teamid, 'iat' => time()];

        $header_encoded = $this->base64($header);

        $claims_encoded = $this->base64($claims);

        $signature = '';

        openssl_sign($header_encoded.'.'.$claims_encoded, $signature, $key, 'sha256');

        $jwt = $header_encoded.'.'.$claims_encoded.'.'.base64_encode($signature);

        // only needed for PHP prior to 5.5.24

        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }

        $http2ch = curl_init();

        curl_setopt_array($http2ch, array(
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,

              CURLOPT_URL => "$url/3/device/$token",

              CURLOPT_PORT => 443,

              CURLOPT_HTTPHEADER => array(
                "apns-topic: {$bundleid}",

                "authorization: bearer $jwt",
              ),

              CURLOPT_POST => true,

              CURLOPT_POSTFIELDS => $message,

              CURLOPT_RETURNTRANSFER => true,

              CURLOPT_TIMEOUT => 30,

              CURLOPT_HEADER => 1,
        ));

        $result = curl_exec($http2ch);

        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        if ($status == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function base64($data)
    {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }

    public function getNotificationActivityList($user_id)
    {
        $followings = follow::getFollowingIds($user_id);
        $notiData = NotificationActivity::getActivityData($user_id, $followings->all());
        $data['activities'] = array();
        foreach ($notiData as $value) {
            $activity = array();
            $activity['message'] = ucfirst($value['title']).' '.lcfirst($value['message']);
            $activity['description'] = '';
            $activity['created_at'] = $value['created_at'];
            $activity['type'] = $value['type'];
            if ($value['type'] == 1 && $value['items']) {
                $activity['description'] = $value->items['description'];
                $image = Item_images::where('item_id', $value->items->id)->first();
                if ($image) {
                    $activity['image'] = $image->url != '' ? asset('/').$image->url : '';
                } else {
                    $activity['image'] = '';
                }
                $activity['item_id'] = $value->items->id;
            }
            if ($value['type'] == 2) {
                $image = User::find($value['from']);
                if ($image->profile_pic) {
                    $activity['image'] = asset('/').$image->profile_pic;
                } else {
                    $activity['image'] = '';
                }
                $activity['user_id'] = $value['from'];
            }
            if ($value['type'] == 3 && $value['items_like']) {
                $item = Items::find($value->items_like->item_id);
                $image = Item_images::where('item_id', $value->items_like->item_id)->first();
                $activity['description'] = $item['description'];
                if ($image) {
                    $activity['image'] = $image->url != '' ? asset('/').$image->url : '';
                } else {
                    $activity['image'] = '';
                }
                $activity['item_id'] = $value->items_like->item_id;
            }
            if ($value['type'] == 4 && $value['rating']) {
                $image = User::find($value->rating->user_id);
                if ($image->profile_pic) {
                    $activity['image'] = asset('/').$image->profile_pic;
                } else {
                    $activity['image'] = '';
                }
                $activity['rating'] = $value->rating->rating;
                $activity['item_id'] = $value->rating->item_id;
            }
            if ($value['type'] == 5) {
                $image = User::find($value['from']);
                if ($image->profile_pic) {
                    $activity['image'] = asset('/').$image->profile_pic;
                } else {
                    $activity['image'] = '';
                }
                $activity['user_id'] = $value['from'];
            }
            if ($value['type'] == 6 && $value['comments']) {
                $image = User::find($value->comments->user_id);
                if ($image->profile_pic) {
                    $activity['image'] = asset('/').$image->profile_pic;
                } else {
                    $activity['image'] = '';
                }
                $activity['description'] = $value->comments->comment;
                $activity['item_id'] = $value->comments->item_id;
                $activity['comment_id'] = $value->comments->id;
            }
            array_push($data['activities'], $activity);
        }
        $message = 'No Notification Found';
        if (count($data['activities'])) {
            $message = 'Data Get Successfully.';
        }

        return Response()->json(['data' => $data, 'message' => $message], Response::HTTP_OK);
    }

    public function sendNotificationByAdmin(Request $request)
    {
        $success = false;
        $validator = Validator::make(collect($request)->toArray(), [
            'to_all_users' => 'required',
            'content' => 'required',
            'allow_push' => 'required',
            'allow_email' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => $validator->errors(),
                'data' => null,
            ];

            return response()->json($data, 400);
        }
        $content = $request->input('content');
        $subject = $request->input('subject');
        $allow_push = $request->input('allow_push');
        $allow_email = $request->input('allow_email');
        if (!$allow_email && !$allow_push) {
            $data = [
                'message' => 'Atleast one way is required to send notification',
            ];

            return response()->json($data, 400);
        }
        if ($request->input('to_all_users') === true || $request->input('to_all_users') == 'true') {
            $users = User::where('is_active', 1)->where('email', '<>', '')->whereNotNull('email')->get();
            if ($allow_push === true || $allow_push == 'true') {
                $data['title'] = $subject;
                $devices = $users->where('device_id', '<>', '')->pluck('device_type', 'device_id');
                /*
                 * Push Notification processing sending to all users.
                 */
                foreach ($devices as $token => $type) {
                    $message_type = 8;
                    $noti = $this->notificationSend($type, $token, $content, $message_type, $data);
                }
            }
            if ($allow_email === true || $allow_email == 'true') {
                $emails = $users->pluck('email');
                /**
                 * Email Processing in BCC to send one email to all users.
                 */
                $template = 'notification.txt';
                $email = env('DEVELOPER_EMAIL', 'amit.kaushik@mobilecoderz.com');
                $hooks = array(
                        'searchStrs' => array('#CONTENT#'),
                        'subjectStrs' => array($content),
              );
                $classCtrl = app()->make(\App\Helpers\EmailHelper::class);
                if ($classCtrl->newTemplateMsg($template, $hooks)) {
                    if ($classCtrl->sendMail($email, $subject, null, null, null, null, null, null, null, null, null, null, null, $emails)) {
                        $success = true;
                    } else {
                        $data = [
                      'message' => 'Something went wrong',
                    ];

                        return response()->json($data, 400);
                    }
                }
            }
        } else {
            $validator = Validator::make(collect($request)->toArray(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $data = [
                  'message' => $validator->errors(),
                  'data' => null,
                ];

                return response()->json($data, 400);
            }
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                if (($allow_push === true || $allow_push == 'true') && $user->device_id) {
                    $data['title'] = $subject;
                    $message_type = 8;
                    $noti = $this->notificationSend($user->device_type, $user->device_id, $content, $message_type, $data);
                }
                $template = 'notification.txt';
                $email = $request->input('email');
                $hooks = array(
                        'searchStrs' => array('#CONTENT#'),
                        'subjectStrs' => array($content),
                        );
                $classCtrl = app()->make(\App\Helpers\EmailHelper::class);
                if ($classCtrl->newTemplateMsg($template, $hooks)) {
                    if ($classCtrl->sendMail($email, $subject)) {
                        $success = true;
                    } else {
                        $data = [
                        'message' => 'Something went wrong',
                      ];

                        return response()->json($data, 400);
                    }
                }
            } else {
                $data = [
                'message' => 'User does not exists',
              ];

                return response()->json($data, 400);
            }
        }
        $data = [
          'message' => 'Notification sent successfully',
        ];

        return response()->json($data);
    }
}
