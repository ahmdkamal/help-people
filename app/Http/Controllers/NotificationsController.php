<?php

namespace App\Http\Controllers;

use App\Device;

class NotificationsController extends Controller
{

    public static function send($fields, $headers, $URL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }


    private static function sendUsingFirebase($fields)
    {
        $headers = array
        (
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json'
        );
        $URL = 'https://fcm.googleapis.com/fcm/send';

        self::send($fields, $headers, $URL);
    }

    public static function sendToAll($array = [])
    {
        self::sendToTopicAndroid('all', $array);
        self::sendToTopicIOS('all', $array);

        return;
    }

    public static function sendToTopicAndroid($topic, $array)
    {
        $fields = self::androidObject($array);
        $fields = array_add($fields, 'to', '/topics/' . $topic.'_android');
        self::sendUsingFirebase($fields);
        return;
    }

    public static function sendToTopicIOS($topic, $array)
    {
        $fields = self::iosObject($array);
        $fields = array_add($fields, 'to', '/topics/' . $topic.'_ios');
        self::sendUsingFirebase($fields);
        return;
    }

    private static function androidObject($array)
    {
        $fields = array(
            'data' => $array,
            'priority' => 'high',
            'content_available' => true
        );
        return $fields;
    }

    private static function iosObject($array)
    {
        $apns = [];
        $payload = [];

        $payload['category'] = $array['type'];
        $paylaod['content_available'] = 1;

        $apns = array_add($apns, 'payload', $payload);
        if (array_key_exists('image', $array)) {

            $array['click_action'] = 'WithImage';
            $array['media-attachment'] = $array['image'];
            $array['sound'] = 'default';

            unset($array['image']);

            $fields = array(
                'notification' => $array,
                'apns' => $apns,
                'priority' => 'high',
                'content_available' => true,
                "mutable_content" => true,
            );
        } else {
            $array['click_action'] = $array['type'];
            $array['media-attachment'] = $array['image'];
            $array['sound'] = 'default';

            unset($array['image']);


            $fields = array(
                'notification' => $array,
                'apns' => $apns,
                'priority' => 'high',
                'content_available' => true,
                "mutable_content" => true,
            );

        }

        return $fields;
    }

    public static function sendToUser($user_id, $array)
    {
        $userDevices = Device::where('user_id', $user_id)
            ->where('device_token' , '!=' , null)->get();

        if (count($userDevices)) {
            foreach ($userDevices as $device) {
                if ($device->device_type == 'IOS') {
                    $fields = self::androidObject($array);
                } elseif ($device->device_type == 'ANDROID') {
                    $fields = self::iosObject($array);
                }

                $fields = array_add($fields, 'to', $device->device_token);
                self::sendUsingFirebase($fields);
            }
        }

        return ;
    }

}
