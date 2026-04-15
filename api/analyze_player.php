<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$playerData = $data['player'] ?? null;

if (!$playerData) {
    echo json_encode(['error' => 'Player data is required']);
    exit();
}

$playerName = $playerData['first_name'] . ' ' . $playerData['last_name'];
$teamInfo = $playerData['team']['full_name'] ?? 'Unknown Team';
$position = $playerData['position'] ?? 'Unknown Position';
$height = $playerData['height'] ?? 'N/A';
$weight = $playerData['weight'] ?? 'N/A';

$prompt = "You are a professional NBA analyst. Provide a brief, insightful analysis (about 3-4 sentences) of the NBA player $playerName who plays for the $teamInfo as a $position. Consider their playstyle, strengths, weaknesses, and potential impact on modern NBA games based on their general career. Make it sound professional and strictly about basketball.";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

$ch = curl_init();

$payload = json_encode([
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
]);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($httpcode == 200) {
    $json = json_decode($response, true);
    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
    if ($text) {
        echo json_encode(['analysis' => $text]);
    } else {
         echo json_encode(['error' => 'Failed to parse Gemini response', 'raw' => $response]);
    }
} else {
    echo json_encode(['error' => 'Failed to reach Gemini API', 'code' => $httpcode, 'curl_error' => $curl_error, 'raw' => $response]);
}
?>
