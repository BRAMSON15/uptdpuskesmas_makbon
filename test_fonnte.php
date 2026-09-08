<?php
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.fonnte.com/device",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => array(
    "Authorization: f4t3NCU6ab6tNyCC9Cas"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>
