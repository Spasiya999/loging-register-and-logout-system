<?php

$server = 'localhost';
$dbname= 'login';
$user = 'root';
$password = 'Sachiya999@';

$conn = new mysqli($server, $user, $password, $dbname);

if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}
?>