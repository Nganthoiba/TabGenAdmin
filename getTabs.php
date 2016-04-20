<?php
include('connect_db.php');
session_start();
if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	$created_by = $user_details->username;
	if($conn){
			$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
						FROM Tab,TabTemplate
						where Tab.TabTemplate=TabTemplate.Id and
							Tab.CreatedBy='$created_by'
						order by Tab.CreateAt";
			$res = $conn->query($query);
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode($output);
	}
	else echo "false";
}
else echo "sesssion_expired";
?>
