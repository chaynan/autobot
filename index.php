<?php
 
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

// Token
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

            // Split message then keep it in database. 
            $appointments = explode(',', $event['message']['text']);

            if(count($appointments) == 2) {

$host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
$dbname = 'dfitqn78lbn0av';
$user = 'gwuaimhybkhmyz';
$pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
 $params = array(
                    'time' => $appointments[0],
                    'content' => $appointments[1],
                );
    
                $statement = $connection->prepare("INSERT INTO appointments (time, content) VALUES (:time, :content)");
                $result = $statement->execute($params);
                $respMessage = 'Your appointment has saved.';
            }else{
                $respMessage = 'You can send appointment like this "12.00,House keeping." ';
            }
            
            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);
 
		}
	}
}

echo "1234";


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

