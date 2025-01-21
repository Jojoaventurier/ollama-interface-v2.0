<?php

require_once '../src/OllamaClient.php'; // Include the client logic
require_once '../src/helpers.php';     // Include any helper functions

// Start a session for saving user data (like conversations)
session_start();

// Routing logic based on the "route" query parameter
$route = $_GET['route'] ?? 'home'; // Default to "home" if no route is specified

switch ($route) {
    case 'home':
        showHomePage();
        break;
    case 'save-conversation':
        saveConversation();
        break;
    case 'view-conversations':
        viewConversations();
        break;
    default:
        show404Page();
        break;
}

// Function to render the homepage (main chat interface)
function showHomePage()
{
    include '../public/index.html'; // Use your HTML-based UI here
}

// Function to save a conversation
function saveConversation()
{
    // Check if data is sent via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = $_POST['model'] ?? '';
        $prompt = $_POST['prompt'] ?? '';
        $response = $_POST['response'] ?? '';

        // Validate data
        if ($model && $prompt && $response) {
            // Store conversation in the session (simple storage example)
            $_SESSION['conversations'][] = [
                'model' => $model,
                'prompt' => $prompt,
                'response' => $response,
                'timestamp' => time(),
            ];
            echo json_encode(['status' => 'success', 'message' => 'Conversation saved']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
    } else {
        http_response_code(405); // Method Not Allowed
        echo 'Method not allowed';
    }
}

// Function to view saved conversations
function viewConversations()
{
    $conversations = $_SESSION['conversations'] ?? [];

    // Render a simple page to display the conversations
    echo '<h1>Saved Conversations</h1>';
    echo '<a href="?route=home">Back to Home</a>';
    echo '<ul>';
    foreach ($conversations as $conv) {
        echo '<li>';
        echo '<strong>Model:</strong> ' . htmlspecialchars($conv['model']) . '<br>';
        echo '<strong>Prompt:</strong> ' . htmlspecialchars($conv['prompt']) . '<br>';
        echo '<strong>Response:</strong> ' . htmlspecialchars($conv['response']) . '<br>';
        echo '<strong>Timestamp:</strong> ' . date('Y-m-d H:i:s', $conv['timestamp']);
        echo '</li><hr>';
    }
    echo '</ul>';
}

// Function to handle 404 errors
function show404Page()
{
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    echo '<a href="?route=home">Go Back to Home</a>';
}