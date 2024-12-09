<?php
session_start();

// Check if the session contains the user ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

if (!isset($_GET['list_id'])) {
    echo json_encode(['error' => 'List ID not provided']);
    exit;
}

$list_id = $_GET['list_id'];

// Set up the database connection
$pdo = new PDO('mysql:host=localhost;dbname=imdb', 'root', 'eecs581fa24');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare the SQL query to fetch movies for the selected list
$sql = "SELECT m.movie_id
        FROM list_contents m
        JOIN user_movie_lists ml ON m.list_id = ml.list_id
        WHERE ml.list_id = :list_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the results
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
echo json_encode($movies);
?>