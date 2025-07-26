<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create connection
include 'db.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);

// Execute the statement
$stmt->execute();

// Store the result
$stmt->store_result();

// Check if the user exists
if ($stmt->num_rows > 0) {
    // Bind the result to variables
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, start a new session
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid password
        echo "Invalid password. <a href='index.php'>Try again</a>";
    }
} else {
    // No user found with this email
    echo "No user found with this email. <a href='signup.php'>Sign up</a>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
