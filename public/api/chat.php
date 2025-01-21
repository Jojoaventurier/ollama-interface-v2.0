<?php
require_once '../src/OllamaClient.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$model = $input['model'] ?? '';
$prompt = $input['prompt'] ?? '';

if ($model && $prompt) {
    $client = new OllamaClient();
    echo json_encode($client->chat($model, $prompt));
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Model and prompt are required']);
}