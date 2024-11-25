

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //echo "CONNECTED.";
    // Database connection
    $conn = new mysqli('localhost', 'root', 'eecs581fa24', 'imdb');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch user information from the database
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashedPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Set session variables to log the user in
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            
            // Redirect to the target page, e.g., dashboard
            header("Location: DiscoverPage.html");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        //echo "No account found with that user name.";
	header("Location: index.html");

    }

    $stmt->close();
    $conn->close();
}
?>