<?php
session_start();
if (isset($_POST['email']) && isset($_POST['password'])) {
	$email = htmlspecialchars(trim($_POST['email']));
	$password = htmlspecialchars(trim($_POST['password']));
	
	// Connect to the database
	$db = mysqli_connect('localhost', 'username', 'password', 'database_name');
	
	// Validate the input data
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: login.php?error=Invalid email format');
		exit();
	}
	
	// Check if the email is in the database
	$query = "SELECT * FROM users WHERE email='$email'";
	$result = mysqli_query($db, $query);
	
	if (mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		if (password_verify($password, $row['password'])) {
			// Login successful
			$_SESSION['email'] = $email;
			header('location: dashboard.php');
			exit();
		} else {
			// Login failed
			header('location: login.php?error=Incorrect password');
			exit();
		}
	} else {
		// Login failed
		header('location: login.php?error=Email not found');
		exit();
	}
} else {
	// Invalid request
	header('location: login.php');
	exit();
}
?>