<?php
require 'config.php';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . GEMINI_API_KEY;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpcode\n";
if ($httpcode == 200) {
    $data = json_decode($response, true);
    if(isset($data['models'])) {
        foreach($data['models'] as $m) {
            if (strpos($m['name'], 'gemini') !== false) {
                echo $m['name'] . "\n";
            }
        }
    }
}
?>
