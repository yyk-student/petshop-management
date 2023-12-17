<?php

$conn = new mysqli('localhost','root','','petshop-management');
if ($conn->connect_error) {
    die("Connection failed!" . $conn->connect_error);
}

?>