<?php 
  $access_token = 'zXgFz0Uf48wEYx/9zou6VRrQX8U6Yf5mZ00YzYrZb2DSGTj5isZRoQ1dlpdTR4ZMLSaHPBen+jd/ewTqgZIf29bovG6p6buSQ/qiG6+uMxiWdsvEP+E0L77piQGX4UX2Xx/IcAH5a558Wfh+nBqZbgdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v1/oauth/verify';
  $headers = array('Authorization: Bearer ' . $access_token);
  $ch = curl_init($url);curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);curl_close($ch);
  echo $result;
