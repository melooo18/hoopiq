<?php
require 'config.php';
$query = "Isaiah Thomas";
$url = "https://api.balldontlie.io/v1/players?search=" . urlencode($query);
echo $url . "\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: " . BALLDONTLIE_API_KEY
]);
$r = curl_exec($ch);
echo "CODE: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
echo "RES: " . $r . "\n";
?>
