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

// Execute the query
$result = $conn->query($query);

// Debugging: Display each input field value to verify POST data
//echo "<p><strong>Debugging Input Data:</strong><br>";

$movies = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$movies[] = [ 
		'title' => htmlspecialchars($row['myTitle']), 
		'year' => htmlspecialchars($row['Year']), 
		'genre' => htmlspecialchars($row['Genre']), 
		'rating' => htmlspecialchars($row['Rating']) 
	];        
    }
} else {
    	
    $movies[] = ['title' => 'No results found', 'year' => '', 'genre' => '', 'rating' => ''];
}
//echo "</p>";

// Debugging: Check session variable content 
//echo "<p><strong>Session Data:</strong>//<br>"; 
//print_r($movies); 
//echo "</p>"; 

// Close connection
$conn->close();

// Store results in session variable 
$_SESSION['movies'] = $movies; 


// Redirect to results page 
header("Location: results.php"); 
exit;
?>