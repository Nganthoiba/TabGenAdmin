<?php
	include('connect_db.php');
	if($conn){
			$query = "SELECT * FROM Tab where RoleId IS NULL";
			$res = $conn->query($query);
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode($output);
	}
	else echo "false";
?>
