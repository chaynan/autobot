<?php
 $API_URL = 'https://api.line.me/v2/bot/message/reply';
 $ACCESS_TOKEN = 'JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
 $POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
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
                  $respMessage = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
                 }
                 else
                 $respMessage = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
                
                }
                else {
                    $respMessage = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
                
                 
                if( strlen($respMessage) > 0 )
                {
                 //$reply_message = iconv("tis-620","utf-8",$reply_message);
                 $data = [
                  'replyToken' => $reply_token,
                  'messages' => [['type' => 'text', 'text' => $respMessage]]
                 ];
                 $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
              
                 $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
                 echo "Result: ".$send_result."\r\n";
                }
               }
            }
        }
            
                
                    // switch($event['message']['type']){
                    //     case 'text':
                    //     $data_user = $event['message']['text'];
                    //     $respMessage = $data_user;
                        
                    //     $params = array(
                    //                 'userID' => $event['source']['userId'],
                    //                 'content' => $event['message']['text'],
                    //             );
                    //         $statement = $connection->prepare('INSERT INTO appointments ( user_id,content ) VALUES ( :userID, :content )');
                    //         $statement->execute($params);   

                    //     break;
                        
                    //     case 'image':
                    //         $messageID =$event['message']['id'];
                    //         $respMessage = $messageID; 
                   
    
                    //     break;

                    //     default: 
                    //         $respMessage = 'This is Default'; 

                    //         break;
                    // }
    
                $httpClient = new CurlHTTPClient($channel_token);
                $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
    
                $textMessageBuilder = new TextMessageBuilder($respMessage);
                $response = $bot->replyMessage($replyToken, $textMessageBuilder);

            
            } catch(Exception $e) {
                error_log($e->getMessage());
            }

		}
	}
}

echo "OK";