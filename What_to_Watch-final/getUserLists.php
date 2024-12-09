<?php
header('Content-Type: application/json'); // Ensure the response is JSON

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Include your database connection script
$pdo = new PDO('mysql:host=localhost;dbname=imdb', 'root', 'eecs581fa24');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query to fetch user lists
$sql = "SELECT list_id, list_name FROM user_movie_lists WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// fetch
$userLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are any lists for the user
if (empty($userLists)) {
    echo json_encode(['error' => 'No lists found for the user']);
    exit;
}

// Return the lists as JSON
 echo json_encode($userLists);



?>
