<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "eecs581fa24";
$dbname = "imdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize input data
$myTitle = isset($_POST['myTitle']) ? $conn->real_escape_string(trim($_POST['myTitle'])) : '';
$Year = isset($_POST['Year']) ? $conn->real_escape_string(trim($_POST['Year'])) : '';
$Genre = isset($_POST['Genre']) ? $conn->real_escape_string(trim($_POST['Genre'])) : '';
$Rating = isset($_POST['Rating']) ? $conn->real_escape_string(trim($_POST['Rating'])) : '';

// Initialize query and build conditions only if fields are not empty
$query = "SELECT * FROM movies";
$whereClauses = [];

// Add conditions based on non-empty fields
if ($myTitle !== '') {
    $whereClauses[] = "myTitle LIKE '%$myTitle%'";
	//myTitle == '%$myTitle%;
}
if ($Year !== '' && is_numeric($Year)) {
    $whereClauses[] = "Year = '$Year'";

}
if ($Genre !== '') {
    $whereClauses[] = "Genre LIKE '%$Genre%'";
}
if ($Rating !== '' && is_numeric($Rating)) {
    $whereClauses[] = "Rating = '$Rating'";
}

// Add WHERE clause if there are any conditions
if (count($whereClauses) > 0) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

// Debugging: Output the generated query for testing
//echo "<p><strong>Generated Query:</strong> $query</p>";

// Debugging: Display each input field value to verify POST data
//echo "<p><strong>Debugging Input Data:</strong><br>";
//echo "Title: " . htmlspecialchars($myTitle) . "<br>";
//echo "Year: " . htmlspecialchars($Year) . "<br>";
//echo "Genre: " . htmlspecialchars($Genre) . "<br>";
//echo "Rating: " . htmlspecialchars($Rating) . "<br></p>";

// Execute the query
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    // Output results
    echo "<h2>Search Results:</h2>";
    while($row = $result->fetch_assoc()) {
        echo "Title: " . htmlspecialchars($row["myTitle"]) . "<br>";
        echo "Year: " . htmlspecialchars($row["Year"]) . "<br>";
        echo "Genre: " . htmlspecialchars($row["Genre"]) . "<br>";
        echo "Rating: " . htmlspecialchars($row["Rating"]) . "<br><br>";
    }
} else {
    echo "No results found.";
}

// Close connection
$conn->close();
?>