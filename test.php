<?php
 
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'fce33b682da751c51e13169d81c9b7a8';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

	// Loop through each event
	foreach ($events['events'] as $event) {
    
        // Line API send a lot of event type, we interested in message only.
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];

            try {
                // Check to see user already answer
                $host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
                $dbname = 'dfitqn78lbn0av';
                $user = 'gwuaimhybkhmyz';
                $pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
                $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 

                if ( sizeof($request_array['events']) > 0 )
                {
                
                foreach ($request_array['events'] as $event)
                {
                  $reply_message = '';
                  $reply_token = $event['replyToken'];
                
                if ( $event['type'] == 'message' ) 
                {
                if( $event['message']['type'] == 'text' )
                {
                $text = $event['message']['text'];
                $reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
                }
                else
                $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';  
                }
                else
                $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
                if( strlen($reply_message) > 0 )
                {
                //$reply_message = iconv("tis-620","utf-8",$reply_message);
                $data = [
                'replyToken' => $reply_token,
                'messages' => [['type' => 'text', 'text' => $reply_message]]
                ];
                $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
                
                $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
                echo "Result: ".$send_result."\r\n";
                  }
                 }
                }
                $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                $statement->execute($params);      