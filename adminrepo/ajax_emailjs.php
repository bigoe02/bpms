<?php
session_start();
include('includes/dbconnection.php');
////////////////AJAX FOR CHECKING THE STATUS OF EMAIL SENT ONCE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['email_sent_today'])) {
        $_SESSION['email_sent_today'] = $data['email_sent_today'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>