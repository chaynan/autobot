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
                
                $sql = sprintf("SELECT * FROM poll WHERE user_id='%s' ", $event['source']['userId']);
                $result = $connection->query($sql);
                error_log($sql);
                if($result == false || $result->rowCount() <=0) {
    
                    switch($event['message']['text']) {
                        
                        case '1':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '1',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);
                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='1' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);
                             
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าเพื่อน = '.$amount;
                            break;
                        
                        case '2':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '2',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);
                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='2' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าแฟน = '.$amount;
                            break;
                        
                        case '3':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '3',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);
                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='3' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าพ่อแม่ = '.$amount;
    
                            break;
                        case '4':
                            // Insert
                            $params = array(
                                'userID' => $event['source']['userId'],
                                'answer' => '4',
                            );
                            
                            $statement = $connection->prepare('INSERT INTO poll ( user_id, answer ) VALUES ( :userID, :answer )');
                            $statement->execute($params);
                            // Query
                            $sql = sprintf("SELECT * FROM poll WHERE answer='4' AND  user_id='%s' ", $event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount();
                            }
                            $respMessage = 'จำนวนคนตอบว่าบุคคลอื่นๆ = '.$amount;
                            break;
                        default:
                            $respMessage = "
                                บุคคลที่โทรหาบ่อยที่สุด คือ? \n\r
                                กด 1 เพื่อน \n\r
                                กด 2 แฟน \n\r
                                กด 3 พ่อแม่ \n\r
                                กด 4 บุคคลอื่นๆ \n\r
                            ";
                            break;
                    }
    
                } else {
                    $respMessage = 'คุณได้ตอบโพลล์นี้แล้ว';
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
/*
// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

	// Loop through each event
	foreach ($events['events'] as $event) {
    
        // Line API send a lot of event type, we interested in message only.
		if ($event['type'] == 'message') {

            switch($event['message']['type']) {
                case 'text':
                    
                    $sql = sprintf(
                        "SELECT * FROM slips WHERE slip_date='%s' AND user_id='%s' ", 
                        date('Y-m-d'),
                        $event['source']['userId']);
                    $result = $connection->query($sql);

                    if($result !== false && $result->rowCount() >0) {
                        // Save database
                        $params = array(
                            'name' => $event['message']['text'],
                            'slip_date' => date('Y-m-d'),
                            'user_id' => $event['source']['userId'],
                        );
                        $statement = $connection->prepare('UPDATE slips SET name=:name WHERE slip_date=:slip_date AND user_id=:user_id'); 
                        $statement->execute($params);
                    } else {
                        $params = array(
                            'user_id' => $event['source']['userId'] ,
                            'slip_date' => date('Y-m-d'),
                            'name' => $event['message']['text'],
                        );
                        $statement = $connection->prepare('INSERT INTO slips (user_id, slip_date, name) VALUES (:user_id, :slip_date, :name)');
                         
                        $effect = $statement->execute($params);
                    }

                    // Bot response 
                    $respMessage = 'Your data has saved.';
                    $replyToken = $event['replyToken'];
                    $textMessageBuilder = new TextMessageBuilder($respMessage);
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);

                    break;
                case 'image':

                    // Get file content.
                    $fileID = $event['message']['id'];
                    
                    $response = $bot->getMessageContent($fileID);
                    $fileName = md5(date('Y-m-d')).'.jpg';
                    
                    if ($response->isSucceeded()) {
                        // Create file.
                        $file = fopen($fileName, 'w');
                        fwrite($file, $response->getRawBody());

                        $sql = sprintf(
                                    "SELECT * FROM slips WHERE slip_date='%s' AND user_id='%s' ", 
                                    date('Y-m-d'),
                                    $event['source']['userId']);
                        $result = $connection->query($sql);

                        if($result !== false && $result->rowCount() >0) {
                            // Save database
                            $params = array(
                                'image' => $fileName,
                                'slip_date' => date('Y-m-d'),
                                'user_id' => $event['source']['userId'],
                            );
                            $statement = $connection->prepare('UPDATE slips SET image=:image WHERE slip_date=:slip_date AND user_id=:user_id');
                            $statement->execute($params);
                            
                        } else {
                            $params = array(
                                'user_id' => $event['source']['userId'] ,
                                'image' => $fileName,
                                'slip_date' => date('Y-m-d'),
                            );
                            $statement = $connection->prepare('INSERT INTO slips (user_id, image, slip_date) VALUES (:user_id, :image, :slip_date)');
                            $statement->execute($params);
                        }
                    }

                    // Bot response 
                    $respMessage = 'Your data has saved.';
                    $replyToken = $event['replyToken'];
                    $textMessageBuilder = new TextMessageBuilder($respMessage);
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    
                    break; 
            }
		}
	}
}

echo "OK";



<?php
 
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

// Token
$channel_token = 'JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'fce33b682da751c51e13169d81c9b7a8';

// Create bot
$httpClient = new CurlHTTPClient($channel_token);
$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

// Database connection 
$host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
$dbname = 'dfitqn78lbn0av';
$user = 'gwuaimhybkhmyz';
$pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
$result = $connection->query("SELECT * FROM .appointments");
if($result !== null) {
    echo $result->rowCount();
}
*/


