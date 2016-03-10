<?php
include('server_IP.php');
$db_server = IP;
$username = "mmuser";
$password = "mostest";
$database = "mattermost_test";
// Create connection

        try{
            $conn = new PDO("mysql:host=$db_server;dbname=$database", $username, $password);              
        }
        catch(Exception $e){
                echo "Database Connection failed: ".$e->getMessage();
        }
?>

