<?php
$db_server = "188.166.210.24:3306";
$username = "mmuser";
$password = "mostest";
$database = "mattermost_test";
// Create connection
        try{
                $conn = new PDO("mysql:host=$db_server;dbname=mattermost_test", $username, $password);
                //$conn= new mysqli($db_server ,$username,$password,$database,3306);
				$org_unit_name = "org1";
                if($conn){
					$res = $conn->query("SELECT Id,Name from Teams where Name='$org_unit_name'");
					
					if($res){
						echo "<table border='1'><tr><th>Id</th><th>Team Name</th></tr>";
						$row=$res->fetch(PDO::FETCH_ASSOC);
							/*
							if($row['Name']==$org_unit_name){
								$id = $row['Id'];
								break;
							}*/
						echo "<tr><td>".$row['Id']."</td><td>".$row['Name']."</td></tr>";
						echo "</table>";
					}
				}
        }
        catch(Exception $e){
                echo $e->getMessage();
        }
                //$conn=mysqli_connect($db_server ,$username,$password,$database);
/*
$conn = mysql_connect($db_server,$username,$password);
mysql_select_db($database);
*/
// Check connection mysqli_connect_errno()

?>