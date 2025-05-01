<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = sanitize($_POST['email']);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $db->prepare("SELECT id FROM newsletter WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $response = ['success' => false, 'message' => 'Email already subscribed'];
            } else {
                $stmt = $db->prepare("INSERT INTO newsletter (email) VALUES (?)");
                $stmt->execute([$email]);
                $response = ['success' => true, 'message' => 'Thank you for subscribing!'];
            }
        } catch(PDOException $e) {
            $response = ['success' => false, 'message' => 'Subscription error: ' . $e->getMessage()];
        }
    } else {
        $response = ['success' => false, 'message' => 'Invalid email address'];
    }
    
    echo json_encode($response);
    exit;
}

$response = ['success' => false, 'message' => 'Invalid request'];
echo json_encode($response);
?>