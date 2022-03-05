<?php
$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);

$cipher = 'AES-128-CBC';

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['delete-everything'])) {
  $sql = 'DROP DATABASE hsedatabase;';
  if (!$conn->query($sql) === TRUE) {
    die('Error dropping database: ' . $conn->error);
  }
}

$sql = 'CREATE DATABASE IF NOT EXISTS hsedatabase;';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}

$sql = 'USE hsedatabase;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS user (
id int NOT NULL AUTO_INCREMENT,
username varchar(20) NOT NULL,
password varchar(256) NOT NULL,
iv varchar(32) NOT NULL,
fullname varchar(256) NOT NULL,
address varchar(256) NOT NULL,
dob varchar(256) NOT NULL,
phoneNo varchar(256) NOT NULL,
img varchar(256) NOT NULL,
PRIMARY KEY (id));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS closecontact (
id int NOT NULL AUTO_INCREMENT,
assoc_id int NOT NULL,
fullname varchar(256) NOT NULL,
phoneNo varchar(256) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (assoc_id) REFERENCES user(id));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}
?>
