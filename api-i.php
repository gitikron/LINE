<?php
  $access_token = "zXgFz0Uf48wEYx/9zou6VRrQX8U6Yf5mZ00YzYrZb2DSGTj5isZRoQ1dlpdTR4ZMLSaHPBen+jd/ewTqgZIf29bovG6p6buSQ/qiG6+uMxiWdsvEP+E0L77piQGX4UX2Xx/IcAH5a558Wfh+nBqZbgdB04t89/1O/w1cDnyilFU=";
  $userId ='U09daf41b2443adfdfcbdfc3b3858f3eb';

  // Call LINE API
  $content = file_get_contents('php://input');
  $events = json_decode($content, true); 

  // Call Database Mysql
  //$database = file_get_contents('http://35.198.246.124/dbtestbme/jsonline.php');=
  //$datas = json_decode($database, true);  

  // Call Function GetName
  function CallLineGetName($access_token,$userId){

    $url = 'https://api.line.me/v2/bot/profile/'.$userId;
    $headers = array('Authorization: Bearer ' .$access_token);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  $result = CallLineGetName($access_token,$userId);
  $json = json_decode($result,TRUE); 

  if(!is_null($json['displayName'])){

    foreach ($json as $type => $value){
      if($type == 'displayName'){
          $Name = $value; // send reply text name gg
      }
    }
  }
 
  // Call Database Mysql
  if (!is_null($datas['id'])) {
      foreach ($datas as $type => $value) {
          if($type == 'id'){
            $id = $value;
          }elseif($type == 'temperature'){
            $Temperature = $value;
          }elseif($type == 'pressure') {
            $Pressure = $value;
          }elseif($type == 'approx') {
            $Approx = $value;          
          }elseif($type == 'humidity') {
            $Humidity = $value;         
          }elseif($type == 'datetime') {
            $datetime = $value;       
          }   
      } 
  }

  // Call Events LINE API
  if (!is_null($events['events'])) {
    foreach ($events['events'] as $event) {
      if ($event['type'] == 'message' && $event['message']['type'] == 'text'){
          $text = $event['message']['text'];
          if ($text == "สวัสดี"
           or $text == "สวัสดีครับ" 
           or $text == "open" 
           or $text == "hi"
           or $text == "Hi"
          ){
            $text = $text." คุณ ".$Name."\n สามารถเลือกเมนูได้เลยครับ ";
          }elseif($text == "1"){
            $text = "Temperature(อุณหภูมิ)ตอนนี้จะอยู่ที่ ".$Temperature." (°C) ครับ";
          }elseif ($text == "2"){
            $text = "Pressure(ความดัน)ตอนนี้จะอยู่ที่ ".$Pressure." (°Pa) ครับ";
          }elseif ($text == "3"){
            $text = " Approx. Altitude(ความสูงจากระดับน้ำทะเล)ตอนนี้จะอยู่ที่ ".$Approx." (°) ครับ";
          }elseif ($text == "4"){
            $text = "Humidity(ความชื้น)ตอนนี้จะอยู่ที่ ".$Humidity." (°) ครับ";
          }elseif ($text == "5"){
            $text = "วันที่/เวลา :".$datetime;
          }else if($text == "6"){
            $text = "อุณหภูมิ ตอนนี้จะอยู่ที่ ".$Temperature." (°C) ครับ \n
            - ความดันอยู่ที่ ".$Pressure." (°F) ครับ\n 
            - ความสูงจากระดับน้ำทะเล ตอนนี้จะอยู่ที่ ".$Approx." (°C) ครับ\n 
            - ความชื้น ตอนนี้จะอยู่ที่ ".$Humidity." (°F) ครับ\n 
            - อับเดจล่าสุดเมื่อวันที่ ".$datetime." ครับ";
          }else if($text == "ใครสร้าง Mark I"){
            $text ="Mark I ถูกสร้างโดย mr.mach ครับ";
          }else{
            $text = "ขอเวลาเรียนรู้ก่อนนะครับ :)";
          }

          $replyToken = $event['replyToken'];
          $messages = [
            'type' => 'text',
            'text' => $text
        ];
      }elseif ($event['type'] == 'message' && $event['message']['type'] == 'sticker'){
          $id = $event['message']['id'];
          $stickerId = $event['message']['stickerId'];
          $packageId = $event['message']['packageId'];

          $replyToken = $event['replyToken'];
          $messages = [
            'type' => 'sticker',
            'id' => $id,
            'stickerId' => $stickerId,
            'packageId' => $packageId      
          ];
      
      }
      // Make a POST Request to Messaging API to reply to sender
      $url = 'https://api.line.me/v2/bot/message/reply';
      $data = [
        'replyToken' => $replyToken,
        'messages' => [$messages],
      ];
      $post = json_encode($data);
      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      $result = curl_exec($ch);
      curl_close($ch);

      echo $result . "\r\n";
    }
  }
  echo "sucess full";
?>
