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
		if ($event['type'] == 'message' ) {

            // Get replyToken
            $replyToken = $event['replyToken'];

            try {
                // Check to see user already answer
                $host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
                $dbname = 'dfitqn78lbn0av';
                $user = 'gwuaimhybkhmyz';
                $pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
                $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
    
                    switch($event['message']['type']){
                        case 'text':
                     // Split message then keep it in database. 
                        $appointments = explode(',', $event['message']['text']);
                         if(count($appointments) == 2) {
                            $params = array(
                                'user_name' => $appointments[0],
                                'answer' => $appointments[1],
                                'user_id'=>$event['source']['userId'],
                            );
                            $statement = $connection->prepare("INSERT INTO poll (user_name, answer , user_id ) VALUES (:user_name,:answer, :user_id)");
                            $result = $statement->execute($params);
                
                            $respMessage = 'บันทึกแล้วจ้า.';
                        }
                    if(count($appointments) == 3){
                        $params = array(
                            'user_name' => $appointments[0],
                            'answer' => $appointments[1],
                            'time_id'=>$appointments[2],
                            'user_id'=>$event['source']['userId'],
                        );
                            $statement = $connection->prepare("INSERT INTO poll (user_name, answer , user_id ,time_id ) VALUES (:user_name,:answer, :user_id,time_id)");
                            $result = $statement->execute($params);
                
                            $respMessage = 'บันทึกแล้วจ้า.';
                        }else{
                            $respMessage = 'กรุณากรอกข้อมูลตามรูปแบบ เช่น สตท.1,ปัญหา,ว/ด/ป. ';
                        }
                        // $data_user = $event['message']['text'];
                        // $respMessage = $data_user;
                        
                        // $params = array(
                        //             'userID' => $event['source']['userId'],
                        //             'answer' => $event['message']['text'],
                        //         );
                        //     $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                        //     $statement->execute($params);   

                        break;
                        
                        case 'image':
                          
                            $fileID = $event['message']['id'];
                    
                            //$response = $bot->getMessageContent($fileID);
                            $fileName = md5(date('Y-m-d')).'.jpg';
                            $respMessage = $fileName;
                            // if ($response->isSucceeded()) {
                            //     $respMessage = "Complete";
                            //     // Create file.
                            //     // $file = fopen($fileName, 'w');
                            //     // fwrite($file, $response->getRawBody());
                                    $params = array(
                                        'user_id' => $event['source']['userId'] ,
                                        'image_test' => $fileName,
                                        'content' => "test",
                                    );
                                    $statement = $connection->prepare('INSERT INTO appointments (user_id, image_test, content) VALUES (:user_id, :image_test, :content)');
                                    $statement->execute($params);

                            //     //     $respMessage = 'Complete';
                            //     }else{
                            //         $respMessage = "Not Complete";
                            //     }
        

                        break;

                        default: 
                            $respMessage = 'This is Default'; 

                            break;
                    }
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