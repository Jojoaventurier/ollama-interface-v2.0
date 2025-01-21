<?php

class OllamaClient {
    private $host;

    public function __construct() {
        $this->host = getenv('OLLAMA_API_URL') ?: 'http://localhost:11434';
    }

    public function listModels() {
        return $this->request('/api/models');
    }

    public function chat($model, $prompt) {
        return $this->request('/api/chat', [
            'model' => $model,
            'prompt' => $prompt
        ]);
    }

    private function request($endpoint, $data = null) {
        $url = $this->host . $endpoint;
        $options = [
            'http' => [
                'method' => $data ? 'POST' : 'GET',
                'header' => 'Content-Type: application/json',
                'content' => $data ? json_encode($data) : null
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        return json_decode($response, true);
    }
}