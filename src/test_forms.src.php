<?php
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'errors' => []
];

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Simple email validation
    if (empty($_POST['email'])) {
        $response['errors']['email'] = 'Dit veld is verplicht';
    } 
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Vul een geldig e-mailadres in.';
    }

    // Simple password validation
    if (empty($_POST['pwd'])) {
        $response['errors']['pwd'] = 'Dit veld is verplicht';
    }
    elseif (strlen($_POST['pwd']) < 8) {
        $response['errors']['pwd'] = 'Wachtwoord moet minstens 8 tekens lang zijn.';
    }

    // Check if there are any validation errors
    if (!isset($response['errors'])) {
        // Simulate successful form processing
        $response['success'] = true;
    }
} else {
    http_response_code(405);
    $response['errors']['method'] = 'Invalid request method';
}

// Return JSON response
echo json_encode($response);
?>