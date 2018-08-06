<?php require_once('./Desktop/autobot/vendor/autoload.php'); 

// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token ='JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'fce33b682da751c51e13169d81c9b7a8';

// Get message from Line API 
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) { 
    // Loop through each event 
    foreach ($events['events']as $event) { 
        // Line API send a lot of event type, we interested in message only. 
        if ($event['type'] == 'message') { 
            switch($event['message']['type']) { 
                case 'text': 
                // Get replyToken 
                $replyToken = $event['replyToken']; 

                // Reply message 
                $respMessage = 'Hello, your message is '. $event['message']['text'];

                $httpClient = new CurlHTTPClient($channel_token); 
                $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret)); 
                $textMessageBuilder = new TextMessageBuilder($respMessage); 
                $response = $bot->replyMessage($replyToken, $textMessageBuilder); 
            break; 
        } 
    } 
} 
} 
echo "OK";