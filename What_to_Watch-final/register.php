<form action="register.php" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
</form>

<?php
// Database connection
$pdo = new PDO("mysql:host=localhost;dbname=imdb", "root", "eecs581fa24");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "Username already taken.";
    } else {
        // Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        echo "Registration successful! You can now log in.";
	header("Location: index.html"); 
    	exit(); // Make sure to call exit after redirecting to stop script execution
    }
}
?>