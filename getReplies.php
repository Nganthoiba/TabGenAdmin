<?php 
	//php code for getting replies of a particular post messages
	
	$post_id = $_GET['post_id'];
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	if($conn){
			$query = "select * from Posts where RootId='$post_id' or ParentId='$post_id'";
			$output=null;
			$res = $conn->query($query);
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode($output);	
	}
	else 
		echo null;
		
		
?>
