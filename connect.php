<?php
function Connect()
{
  $user = "cookbookadmin";
  $password = "R3d1s0nR0cks14";
  $host = "localhost";
  $databaseName = "cookbook";


    $conn = new mysqli($host, $user, $password, $databaseName);

    if ($conn->connect_error) {
      die("ERROR: Unable to connect: " . $conn->connect_error);
    }

    //echo 'Connected to the database.<br>';
    return $conn;
    $conn->close();


}

?>