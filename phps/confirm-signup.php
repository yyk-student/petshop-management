<?php
require 'connect.php';

$email = $_GET['email'];
$token = $_GET['token'];

// Check if the token and email are valid
$checkQuery = "SELECT * FROM user_details WHERE emailid='$email' AND token='$token'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    // Update the user's account status to verified
    $updateQuery = "UPDATE user_details SET verified=1 WHERE emailid='$email'";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Email verification successful!";
    } else {
        echo "Failed to verify email.";
    }
} else {
    echo "Invalid token or email.";
}
?>