
<?php
session_start();

// Database connection (replace placeholders with your database credentials)
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "eecs581fa24";            // Replace with your database password
$dbname = "imdb"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        $action = $input['action'];
        
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }
        
        $user_id = $_SESSION['user_id'];

        if ($action === 'create_list' && isset($input['listName'])) {
            // Create a new list
            $list_name = $input['listName'];
            $created_at = date('Y-m-d H:i:s');

            // Calculate the next `list_id` value
            $result = $conn->query("SELECT MAX(list_id) AS max_list_id FROM user_movie_lists");
            $row = $result->fetch_assoc();
            $next_list_id = $row['max_list_id'] ? $row['max_list_id'] + 1 : 1;

            // Insert into `user_movie_lists` table
            $stmt = $conn->prepare("INSERT INTO user_movie_lists (list_id, id, list_name, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiss', $next_list_id, $user_id, $list_name, $created_at);
            
            if ($stmt->execute()) {
                echo json_encode(['list_id' => $next_list_id]);
            } else {
                echo json_encode(['error' => 'Failed to create list']);
            }

            $stmt->close();
        } elseif ($action === 'add_movies' && isset($input['list_id']) && isset($input['movie_ids'])) {
            // Add movies to the list
            $list_id = $input['list_id'];
            $movie_ids = $input['movie_ids'];
            $added_at = date('Y-m-d H:i:s');

            // Prepare statement for inserting into `list_contents`
            $stmt = $conn->prepare("INSERT INTO list_contents (content_id, list_id, movie_id, added_at) VALUES (?, ?, ?, ?)");

            foreach ($movie_ids as $movie_id) {
                // Calculate the next `content_id` value
                $result = $conn->query("SELECT MAX(content_id) AS max_content_id FROM list_contents");
                $row = $result->fetch_assoc();
                $next_content_id = $row['max_content_id'] ? $row['max_content_id'] + 1 : 1;

                // Bind and execute the insert statement
                $stmt->bind_param('iiis', $next_content_id, $list_id, $movie_id, $added_at);
                $stmt->execute();
            }

            echo json_encode(['success' => true]);
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Invalid action or missing parameters']);
        }
    }
}

$conn->close();
?>

