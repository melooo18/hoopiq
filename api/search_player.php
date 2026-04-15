<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$query = $_GET['q'] ?? '';

if (empty($query)) {
    echo json_encode(['error' => 'Query is required']);
    exit();
}

$url = "https://api.balldontlie.io/v1/players?search=" . rawurlencode($query);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: " . BALLDONTLIE_API_KEY
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($httpcode == 200) {
    echo $response;
} else {
    echo json_encode(['error' => 'Failed to fetch data from BallDontLie API', 'code' => $httpcode, 'curl_error' => $curl_error, 'response' => json_decode($response)]);
}
?>
