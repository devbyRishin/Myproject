<?php
include 'db.php'; // Connect to the database

// Get form values
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validate basic input (extra safety)
if (empty($username) || empty($email) || empty($password)) {
    echo "All fields are required. <a href='l1.html'>Go back</a>";
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$checkQuery = "SELECT * FROM users WHERE email='$email'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    echo "❌ Email is already registered. <a href='l1.html'>Login instead</a>";
    exit();
}

// Insert user into the database
$insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
if (mysqli_query($conn, $insertQuery)) {
    echo "✅ Signup successful! <a href='l1.html'>Click here to login</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
