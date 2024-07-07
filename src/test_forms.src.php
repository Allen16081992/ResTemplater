<?php
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'errors' => []
];

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['pwd']) ? $_POST['pwd'] : '';

    // Log received data
    error_log("Received email: " . $email);
    error_log("Received password: " . $password);

    // Simple email and password validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Invalid email address';
    }
    if (strlen($password) < 8) {
        $response['errors']['password'] = 'Password must be at least 8 characters long';
    }

    // Check if there are any validation errors
    if (empty($response['errors'])) {
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