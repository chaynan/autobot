<?php
 
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'fce33b682da751c51e13169d81c9b7a8';
date_default_timezone_set("Asia/Bangkok");

$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {


	foreach ($events['events'] as $event) {
    
  
		if ($event['type'] == 'message' ) {

 
            $replyToken = $event['replyToken'];

            try {

                $host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
                $dbname = 'dfitqn78lbn0av';
                $user = 'gwuaimhybkhmyz';
                $pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
                $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 

                if($event['message']['type']=='text'){
                   $text = $event['message']['text'];
                   
                   $appointments = explode('==', $event['message']['text']);

                if(count($appointments) == 2) {
                    $key = $appointments[0];

                   $params = array(
                    'key' => $appointments[0],
                    'result' => $appointments[1],
                    'time' => date("Y-m-d h:i:sa")
                   );

                            $checkkey = $connection->query("SELECT * FROM test WHERE key='$key' LIMIT 1")->fetchAll();
                            if($checkkey){
                                foreach ($checkkey as $row) {
                                    $id = $row['id'];
                                    
                                }

                                $sqlupdate= $connection->prepare("UPDATE test SET key=:key, result=:result, time=:time WHERE id='$id' ");
                                $result = $sqlupdate->execute($params);
                                
                                if($result){
                                    $respMessage = 'อัพเดทแล้ว';
                                }else{
                                    $respMessage = 'อัพเดทข้อผิดพลาด';
                                }
                                
                            }else{
                                    
                                $data = $connection->prepare("INSERT INTO test (key,result,time) VALUES (:key,:result,:time)");
                                $result = $data->execute($params);
                                
                                if($result){
                                    $respMessage = 'บันทึกแล้ว';
                                }else{
                                    $respMessage = 'บันทึกข้อผิดพลาด';
                                }
                            }
                 
                   }else{

                    $data = $connection->query("SELECT result FROM test WHERE key='$text' LIMIT 1")->fetchAll();
                   
                    if($data){
                        foreach ($data as $row) {
                        $respMessage = $row['result'];
                        }

                    }else{
            
                        $data = $connection->query("SELECT result FROM test WHERE key LIKE '%$text%' LIMIT 1")->fetchAll();
                        if($data){
                            foreach ($data as $row) {
                            $respMessage = $row['result'];
                            }
                        }else{
                            $respMessage = "ไม่พบข้อมูล";
                        }
                    }
                }
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
}switch ($typeMessage){
case (preg_match('/[image|audio|video]/',$typeMessage) ? true : false) :
$response = $bot->getMessageContent($idMessage);
if ($response->isSucceeded()) {
    // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
    // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
    $dataBinary = $response->getRawBody(); // return binary
    // ดึงข้อมูลประเภทของไฟล์ จาก header
    $fileType = $response->getHeader('Content-Type');    
    switch ($fileType){
        case (preg_match('/^image/',$fileType) ? true : false):
            list($typeFile,$ext) = explode("/",$fileType);
            $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
            $fileNameSave = time().".".$ext;
            break;
        case (preg_match('/^audio/',$fileType) ? true : false):
            list($typeFile,$ext) = explode("/",$fileType);
            $fileNameSave = time().".".$ext;                        
            break;
        case (preg_match('/^video/',$fileType) ? true : false):
            list($typeFile,$ext) = explode("/",$fileType);
            $fileNameSave = time().".".$ext;                                
            break;                                                      
    }
$botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
$botDataUserFolder = $botDataFolder.$userID; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
    mkdir($botDataUserFolder, 0777, true);
}   
// กำหนด path ของไฟล์ที่จะบันทึก
$fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
file_put_contents($fileFullSavePath,$dataBinary); // ทำการบันทึกไฟล์
$textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
$replyData = new TextMessageBuilder($textReplyMessage);
break;
}
$failMessage = json_encode($idMessage.' '.$response->getHTTPStatus() . ' ' . $response->getRawBody());
$replyData = new TextMessageBuilder($failMessage);  
break;                                                      
default:
$textReplyMessage = json_encode($events);
$replyData = new TextMessageBuilder($textReplyMessage);         
break;  
}
echo "OK";
?>
