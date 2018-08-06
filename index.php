<?php require_once('./vendor/autoload.php'); 

//Namespace 
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
    foreach ($events['events'] as $event) {
         
        // Line API send a lot of event type, we interested in message only. 
        if ($event['type'] == 'message'&& $event['message']['type']='text') { 
        // Get replyToken 
                $replyToken = $event['replyToken']; 
        //Split message then keep it in database.
        if(count($appointments==2))
        $host =
        $dbname = 
        $pass = 
        $connection =new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $params=array(
            'time' => $appointments[0], 
            'content' => $appointments[1], 
        );
        $statement = $connection->prepare("INSERT INTO appointments (time, content) VALUES (:time,:content)");
        $result = $statement->execute($params);
        $respMessage = 'Your appointment has saved.'; }else{
        $respMessage = 'You can send appointment like this "12.00,House keeping." '; }
        $httpClient = new CurlHTTPClient($channel_token); 
        $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
        $textMessageBuilder = new TextMessageBuilder($respMessage); 
        $response = $bot->replyMessage($replyToken, $textMessageBuilder); 
        } 
    } 
} 
echo "OK";