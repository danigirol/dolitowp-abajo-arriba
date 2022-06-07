<?php 
$url = "https://api.stressgijon.com/auth";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_USERAGENT, '*');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

echo $curl;
print_r($curl);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data ='{"usuario": "danigirol@gmail.com",
  "password": "123456"}';

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);

echo $resp;

$data=json_decode($resp);




//echo 'MI TOKEN:'.$token=$data->result->token;





?>
