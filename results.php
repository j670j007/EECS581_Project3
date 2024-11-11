<?php session_start(); // Start the session 

// Retrieve results from session variable 
$movies = isset($_SESSION['movies']) ? $_SESSION['movies'] : []; 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
	<meta charset="UTF-8"> 
	<title>Results Page</title> 
</head> 
<body> 
	<h1>Results Page</h1> 
	<?php if (!empty($movies)) : ?> 
		<select size="10" style="width: 300px;"> <!-- Adjust the width as needed --> 			<?php foreach ($movies as $movie) : ?> 
			<option> 
				<?php echo htmlspecialchars($movie['title']) . ' - ' . htmlspecialchars($movie['year']) . ' - ' . htmlspecialchars($movie['genre']) . ' - ' . htmlspecialchars($movie['rating']); ?> 
			</option> 
	<?php endforeach; ?> 
		</select> 
	<?php else : ?> 
		<p>No results found.</p> 
	<?php endif; ?>

<h2>Your Lists</h2> 
<select size="10" style="width: 300px;"> <!-- Placeholder items, remove or replace in future --> </select>

<!-- Text Box for List Name --> 
<form action="#" method="post" style="margin-top: 20px;"> 
<label for="listName">Enter a name for your list:</label><br> 
<input type="text" id="listName" name="listName" style="width: 300px;"><br> 
<button type="button">Create List</button> 
<button type="button">Open List</button>
</form>

<form action="search.html" method="get" style="margin-top: 20px;"> 
<button type="submit">Back to Search</button> 
</form>
</body> 
</html>