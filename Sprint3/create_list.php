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
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    $conn->close();
    exit;
}

// Get the data from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$listName = $data['listName'];
$movies = $data['movies'];

$userId = $_SESSION['user_id']; // User ID from the session

// Generate a unique list id (simple integer)
$sql = "SELECT MAX(list_id) AS max_id FROM user_movie_lists";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$listId = $row['max_id'] + 1;

// Debugging output
error_log("User ID: $userId");
error_log("List Name: $listName");
error_log("Movies: " . json_encode($movies));
error_log("Generated list_id: $listId");

// Insert into user_movie_lists table
$createdAt = date('Y-m-d H:i:s');
$sql = "INSERT INTO user_movie_lists (id, list_id, list_name, created_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $userId, $listId, $listName, $createdAt);

// Output SQL statement for debugging
error_log("SQL (user_movie_lists): $sql");

if ($stmt->execute()) {
    error_log("Inserted into user_movie_lists successfully.");

    // Insert into list_contents table
    $sql = "SELECT movie_id FROM movies WHERE myTitle=? AND Year=? AND Genre=? AND Rating=? LIMIT 1";
    $stmt = $conn->prepare($sql);

    foreach ($movies as $movie) {
        list($myTitle, $Year, $Genre, $Rating) = explode(' - ', $movie);

        // Bind parameters and execute the statement to retrieve the movie_id
        $stmt->bind_param("ssss", $myTitle, $Year, $Genre, $Rating);
        $stmt->execute();
        $stmt->store_result();
        
        error_log("SQL (select movie_id): $sql with params: $myTitle, $Year, $Genre, $Rating");

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($movieId);
            $stmt->fetch();

            error_log("Retrieved movie_id: $movieId for movie: $myTitle");

            // Generate a unique integer content_id
            $contentIdQuery = "SELECT MAX(content_id) AS max_content_id FROM list_contents";
            $contentIdResult = $conn->query($contentIdQuery);
            $contentIdRow = $contentIdResult->fetch_assoc();
            $contentId = $contentIdRow['max_content_id'] + 1;

            error_log("Generated content_id: $contentId");

            $addedAt = date('Y-m-d H:i:s');
            $sqlInsert = "INSERT INTO list_contents (content_id, list_id, movie_id, added_at) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iiis", $contentId, $listId, $movieId, $addedAt);

            error_log("SQL (list_contents): $sqlInsert");

            if (!$stmtInsert->execute()) {
                error_log("Failed to insert movie into list_contents: " . $stmtInsert->error);
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert movie: ' . $stmtInsert->error]);
                $stmtInsert->close();
                $conn->close();
                exit;
            } else {
                error_log("Inserted into list_contents successfully.");
            }
            $stmtInsert->close();
        } else {
            error_log("Movie not found in database: $myTitle - $Year - $Genre - $Rating");
            echo json_encode(['status' => 'error', 'message' => 'Movie not found in database']);
            $stmt->close();
            $conn->close();
            exit;
        }
    }
    echo json_encode(['status' => 'success', 'message' => 'List created successfully.']);
    $stmt->close();
} else {
    error_log("Failed to create list: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to create list: ' . $stmt->error]);
    $stmt->close();
}

$conn->close();
?>
