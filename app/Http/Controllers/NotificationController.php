<?php

namespace App\Http\Controllers;

use CargoLogisticsModels\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function sendPushNotification($data, $to, $options)
    {
        $apiKey = '.....';
        $post = $options ?: array();
        $post['to'] = $to;
        $post['data'] = $data;
        $headers = array(
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }
        curl_close($ch);

        NotificationLog::create([
            'notification_data' => json_encode($data),
            'notification_to' => json_encode($to),
            'notification_options' => json_encode($options),
            'notification_response' => $result
        ]);
    }
}
