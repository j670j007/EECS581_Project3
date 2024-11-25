<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "eecs581fa24";
$dbname = "imdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get user ID from the query string
$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    $conn->close();
    exit;
}

// Query to fetch user lists
$sql = "SELECT list_id, list_name FROM user_movie_lists WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$lists = [];
while ($row = $result->fetch_assoc()) {
    $lists[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($lists);
?>
