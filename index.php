<?php require_once('./vendor/autoload.php'); 
// Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
$channel_token =
'8hBnzp5BbOIfATUrjeFH4zp77UTfPKvfDe9ExRDnT8FqE2h0qAautaOhFkuXnY34Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBQz/RqAguxX6zzf4xItuXBCmLI38QRnusP9gaId8xiLEQdB04t89/1O/w1cDnyilFU=';
$channel_secret = '5b133c7e2f48c202daa02a237b186d5a';
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