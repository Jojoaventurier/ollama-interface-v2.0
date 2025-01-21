<?php
require_once '../src/OllamaClient.php';

header('Content-Type: application/json');

$client = new OllamaClient();
echo json_encode($client->listModels());