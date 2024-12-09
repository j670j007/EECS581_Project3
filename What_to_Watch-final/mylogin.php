

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['emailText'];
    $password = $_POST['passwordText'];

    // Database connection
    $conn = new mysqli('localhost', 'root', 'eecs581fa24', 'imdb');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "<p>You searched for: " . htmlspecialchars($username) . "</p>";

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
            header("Location: LoginError.html");
            //echo "Incorrect password.";
        }
    } else {
        //echo "No account found with that user name.";
        header("Location: NoAccount.html");
    }

    $stmt->close();
    $conn->close();
}
?>