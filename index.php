<?php
 
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'JbkbMF0zqa9cDn91X8Vqhx0CMgD7haLJlO2V2bm8GpU4RZOZSvQHKw2stIMaFPN/Nthz2ZuAUdT7D3g2xUPcS4dvFtzF32s+C7zKtq+/hBR6VNIYXADNVgap6/7hMe46fUUW88Fm9JkRGbhdljSIvQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'fce33b682da751c51e13169d81c9b7a8';


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
                   $params = array(
                   'key' => $appointments[0],
                   'result' => $appointments[1],
                   'time' => date("Y-m-d h:i:sa")
                   );
                // $key = $appointments[0];
                // $result = $appointments[1];
                // $time= date("Y-m-d h:i:sa") ;
                            $checkkey = $connection->query("SELECT key FROM test WHERE key='$key'")->fetchAll();
                            if($checkkey){
                                foreach ($checkkey as $row) {
                                    $id = $row['id'];
                                }
                            
                                $sqlupdate= $connection->prepare("UPDATE test SET key=:key, result=:result,time=:time WHERE id='$id' ");
                                $sql_suc = $sqlupdate->execute($params);

                                if($sql_suc){
                                    $respMessage = 'อัพเดทแล้ว';
                                }else{
                                    $respMessage = 'เกิดข้อผิดพลาด1';
                                }
                                
                            }else{
                                $data = $connection->prepare("INSERT INTO test (key,result) VALUES (:key,:result,:time)");
                                $result = $data->execute($params);
                                
                                if($result){
                                    $respMessage = 'บันทึกแล้ว';
                                }else{
                                    $respMessage = 'เกิดข้อผิดพลาด2';
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
    
                    // switch($event['message']['type']){
                    //     case 'text':
 
                    //     $appointments = explode(',', $event['message']['text']);
                    //      if(count($appointments) == 2) {
                    //         $params = array(
                    //             'user_name' => $appointments[0],
                    //             'answer' => $appointments[1],
                    //             'user_id'=>$event['source']['userId'],
                    //         );
                    //         $statement = $connection->prepare("INSERT INTO poll (user_name, answer , user_id ) VALUES (:user_name,:answer, :user_id)");
                    //         $result = $statement->execute($params);
                
                    //         $respMessage = 'บันทึกแล้วจ้า.';
                    //     }
                    //     else if(count($appointments) == 3){
                    //     $params = array(
                    //         'user_name' => $appointments[0],
                    //         'answer' => $appointments[1],
                    //         'time_id'=>$appointments[2],
                    //         'user_id'=>$event['source']['userId'],
                    //     );
                    //         $statement = $connection->prepare("INSERT INTO poll (user_name, answer , user_id ,time_id ) VALUES (:user_name,:answer, :user_id,:time_id)");
                    //         $result = $statement->execute($params);
                
                    //         $respMessage = 'บันทึกแล้วจ้า.';
                    //     }else{
                    //         $respMessage = 'กรุณากรอกข้อมูลตามรูปแบบ เช่น สตท.1,ปัญหา หรือ สตท.1,ปัญหา,ว/ด/ป. ';
                    //     }
                        
                    //     break;
                        
                    // $respMessage = 'Your data has saved.';
                    // $replyToken = $event['replyToken'];
                    // $textMessageBuilder = new TextMessageBuilder($respMessage);
                    // $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    // break;

                    //     case 'image':
                          
                    //         $fileID = $event['message']['id'];
                      
                    //         $fileName = md5(date('Y-m-d')).'.jpg';
                    //         $respMessage = $fileName;

                    //                 $params = array(
                    //                     'user_id' => $event['source']['userId'] ,
                    //                     'image_test' => $fileName,
                    //                     'content' => "test",
                    //                 );
                    //                 $statement = $connection->prepare('INSERT INTO appointments (user_id, image_test, content) VALUES (:user_id, :image_test, :content)');
                    //                 $statement->execute($params);
  
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
?>