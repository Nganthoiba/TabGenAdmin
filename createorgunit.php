<?php
session_start();
if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);

include ('ConnectAPI.php');
//include ('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');
$orgunit = $_POST['orgunit'];
$displaynameunit = $_POST['displaynameunit'];
$orgnamesel = $_POST['orgnamesel'];
if($orgunit!='' && $orgnamesel!=''){
	//setting data for creating organistion unit
	$data = array(
	"name"=>$displaynameunit,
	"email"=>$user_details->email,
	"organisation" => $orgnamesel,
  	"organisation_unit" => $orgunit,
	"createdBy"=>$user_details->username);
	
	//setting data for creating a team
	$arrayTeam = array(
		"display_name"=>$displaynameunit,
		"email"=>$user_details->email,
		"name"=>$orgunit,
		"type"=>"O"
		);

	$url_send ="http://".IP.":8065/api/v1/organisationUnit/create";
	$str_data = json_encode($data);
	$str_createTeamData = json_encode($arrayTeam);
	
	$createTeam = new ConnectAPI();//connecting api for creating team
	$connect = new ConnectAPI();//connecting api for creating an organisation unit
	
	//Creating Organisation Unit
	$result = $connect->sendPostData($url_send,$str_data);
	if($result!=null){		
		$responseData = json_decode($result);
		if($connect->httpResponseCode==200){
			//creating team
			$createTeamResult = $createTeam->sendPostData("http://".IP.":8065/api/v1/teams/create",$str_createTeamData);
			$responseTeamResult = json_decode($createTeamResult);
			if( $createTeam->httpResponseCode==200){
				//$team_id = $responseTeamResult->id;
				//renameChannel($conn,$team_id,"Public Site","Town Square");
				//deleteChannel($conn,$team_id,"Off-Topic");
				echo "true";
			}
			else if($createTeam->httpResponseCode==0) 
				echo "Unable to connect API for creating Team";
			else
				echo "Failed to create team ".$responseTeamResult->message;
		}else if($connect->httpResponseCode==0)
			echo "Unable to connect API for creating Organisation Unit";
		else
			echo $responseData->message;			
	}
	else echo "false";
}
else{
	echo "Don't leave fields blank.";
}
}
else echo "Please login back again";
/*
function sendPostData($url, $post){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
  $result = curl_exec($ch);
  curl_close($ch);  // Seems like good practice
  return $result;
  echo $result;
}*/
?>
