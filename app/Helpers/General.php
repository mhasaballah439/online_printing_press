<?php

use Illuminate\Support\Facades\Config;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


function get_default_languages()
{
    return Config::get('app.locale');
}

function admin(){
    return auth()->guard('admin')->user();
}

function user(){
    return auth()->guard('web')->user();
}
function upload_file($file,$num_pages,$folder,$modal,$mediable_id){
    $image_name = $file->hashName();
    $file->move(public_path('/uploads/'.$folder."/"), $image_name);
    $filePath = "/uploads/".$folder."/". $image_name;
    $media_file = new \App\Models\Media();
    $media_file->mediable_type = $modal;
    $media_file->file_name = $file->getClientOriginalName();
    $media_file->mediable_id = $mediable_id;
    $media_file->file_path = $filePath;
    $media_file->num_pages = $num_pages;
    $media_file->save();

    return $filePath;
}

function user_data(){
    return [
        'name' => auth()->user()->name,
        'email' => auth()->user()->email,
        'phone' => auth()->user()->phone,
        'active' => auth()->user()->active,
        'fcm_token' => auth()->user()->fcm_token,
        'image' => auth()->user()->image ? asset('public'.auth()->user()->image) : '',
    ];
}

function send_sms($message,$phone){

    $data = [
        "src" => "alshyuh",
        "dests" => [$phone],
        "body" => $message, // $user->email_code,
        "priority" => 0,
        "delay" => 0,
        "validity" => 0,
        "maxParts" => 0,
        "dlr" => 0,
        "prevDups" => 0,
        "msgClass" => "promotional"
    ];
    $dataString = json_encode($data);
    $headers = [
        'Authorization: Bearer ' . 'RJyZjOGIq_DaqJNytcl4',
        'Content-Type: application/json',
        'accept: application/json;charset=UTF-8'
    ];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.oursms.com/msgs/sms');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    $result = curl_exec($ch);
    $order_result = json_decode($result, true);
}
function sendToFcm($fcm, $text, $title)
{

    $header = [];
    $header[] = 'Content-Type: application/json ';
    $header[] = 'Authorization: key=AAAAOGdt3Do:APA91bFDaLzPf3l5Cn3sxFngssZjSbtgu8ldLZPdTz0vyL16meYcRbGT7FaRu64_jI0WJWjbEizhz6ENQKkyiGyRVdZuTa-My2pthj3PR0Z-pLGM9hSirtVhO6uMv7T3zvYRN1XMiznh';

    $data = [
        "to" => $fcm,
        "notification" => [
            "sound" => "default",
            "body" => $text,
            "title" => $title,
            "content_available" => true,
            "priority" => "high"
        ],
        "data" => [
            "sound" => "default",
            "body" => $text,
            "title" => $title,
            "content_available" => true,
            "priority" => "high"
        ]
    ];

    $url = 'https://fcm.googleapis.com/fcm/send';

    $data = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;

}
