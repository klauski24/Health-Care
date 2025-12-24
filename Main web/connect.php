<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "health_care";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_errno){
        die("Connect failed: " . $conn->connect_errno);
    }
    // echo "Connected successfully";
?>
