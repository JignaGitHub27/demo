<?php
$servername = '127.0.0.1';
$mysqli = new mysqli("127.0.0.1","root","","test");

if ($mysqli -> connect_errno) 
{
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  //exit();
}
?>