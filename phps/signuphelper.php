<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
require 'C:/xampp/htdocs/petshop_management/vendor/phpmailer/phpmailer/src/PHPMailer.php';
//vendor/phpmailer/phpmailer/src/PHPMailer.php
require 'C:/xampp/htdocs/petshop_management/vendor/phpmailer/phpmailer/src/Exception.php';
//vendor/phpmailer/phpmailer/src/Exception.php

require 'C:/xampp/htdocs/petshop_management/vendor/autoload.php';
//vendor/autoload.php

$mail = new PHPMailer(true);

$fname = $_GET['fname'];
$lname = $_GET['lname'];
$emailid = $_GET['emailid'];
$password = $_GET['password'];
$password1 = $_GET['password1'];

if ($password != $password1) {
    echo '<script>alert("Passwords do not match!");history.go(-1)</script>';

} else {
    // Successful sign-up

    // Check if email already exists
    $checkQuery = "SELECT * FROM credentials WHERE emailid='$emailid'";
    $result = $conn->query($checkQuery);
    
    if ($result->num_rows > 0) {
        echo '<script>alert("Email already exists!");history.go(-1)</script>';
    } else {
        // Insert new user details
        $query = "INSERT INTO user_details(userid, fname, lname, emailid) VALUES ('', '$fname', '$lname', '$emailid')";
        
        if ($conn->query($query) === TRUE) {
            echo "User details updated!";
            
            // Insert new credentials
            $query1 = "INSERT INTO credentials(userid, emailid, password) VALUES ((SELECT userid FROM user_details WHERE emailid='$emailid'), '$emailid', '$password1')";
			
			// Generate a unique token for authentication
			$token = bin2hex(random_bytes(32));

			// Store the token in the database for the user
			$storeTokenQuery = "UPDATE user_details SET token='$token' WHERE emailid='$emailid'";
			$conn->query($storeTokenQuery);

			// Send the confirmation email
			$to = $emailid;
			$subject = "Sign-up Confirmation";
			$message = "Dear $fname $lname,\n\nThank you for signing up! Please click the following link to authenticate your account:\n\n";
			$message .= "http://127.0.0.1/petshop_management/confirm-signup.php?email=$emailid&token=$token";
			$headers = "From: 22005027g@connect.polyu.hk";

			$mailSent = mail($to, $subject, $message, $headers);

			if ($mailSent) {
				echo "Sign-up confirmation email sent!";
			} else {
				echo "Failed to send sign-up confirmation email.";
			}

            if ($conn->query($query1) === TRUE) {
                echo "Credentials updated!";
                header("Location: ../sign-up-success.html");
                exit();
            } else {
                echo "Error: " . $query1 . "<br>" . $conn->error;
                exit();
            }
        } else {
            echo "<script>alert('Cannot update user details'); window.location.href = document.referrer;</script>";
        }
    }
}
?>