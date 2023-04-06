<?php
session_start();
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
	$name = htmlspecialchars(trim($_POST['name']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = htmlspecialchars(trim($_POST['password']));
	$confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

	// Connect to the database
	$db = mysqli_connect('localhost', 'username', 'password', 'database_name');

	// Validate the input data
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: register.php?error=Invalid email format');
		exit();
	}
	if ($password !== $confirm_password) {
		header('location: register.php?error=Passwords do not match');
		exit();
	}

	// Check if the email already exists in the database
	$query = "SELECT * FROM users WHERE email='$email'";
	$result = mysqli_query($db, $query);
	if (mysqli_num_rows($result) > 0) {
		header('location: register.php?error=Email already exists');
		exit();
	}

	// Hash the password
	$password = password_hash($password, PASSWORD_DEFAULT);

	// Insert the user data into the database
	$query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
	mysqli_query($db, $query);

	// Redirect the user to the login page
	header('location: login.php');
	exit();
} else {
	// Invalid request
	header('location: register.php');
	exit();
}
?>
