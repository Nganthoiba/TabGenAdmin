<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file

if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	if(!empty($_POST)){
		$tab_name = $_POST['tab_name'];
		$createdBy = $user_details->username;
		$template_name = $_POST['template_name'];
		$id = randId(26);//creating unique id
		$createAt = time();
		if($conn){
				$template_id=findTemplateId($conn,$template_name);
				$query = "INSERT INTO Tab(Id,CreateAt,UpdateAt,Name,CreatedBy,TabTemplate)
						values('$id','$createAt','$createAt','$tab_name','$createdBy','$template_id')";
				if($conn->query($query)){
						echo json_encode(array("status"=>true,"message"=>"Tab created"));
				}
				else echo json_encode(array("status"=>false,"message"=>"Failed to create tab"));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"No perameter passed..!"));
	}
}
else 
	echo json_encode(array("status"=>false,"message"=>"Session expired, login again."));

?>

