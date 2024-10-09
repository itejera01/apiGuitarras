<?php

$hostname = 'localhost';
$user = 'root';
$password = '';
$database = 'guitarras';

$mysqli = new mysqli($hostname, $user, $password, $database);

if ($mysqli -> connect_error){
  die("ERROR: ". $conn->connect_error);
}