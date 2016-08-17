<?php

if(isset($_COOKIE['user_details'])){
	$user_details = json_decode($_COOKIE['user_details']);

include ('ConnectAPI.php');
//include ('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');
$orgunit = $_POST['orgunit'];
$orgnamesel = $_POST['orgnamesel'];
	if($orgunit!='' && $orgnamesel!=''){

		$data = array(
		"email"=>$user_details->email,
		"organisation" => $orgnamesel,
		"organisation_unit" => $orgunit,
		"createdBy"=>$user_details->username);
		
		$url_send ="http://".IP.":8065/api/v1/organisationUnit/create";
		$str_data = json_encode($data);
		//$str_createTeamData = json_encode($arrayTeam);
		
		//$createTeam = new ConnectAPI();//connecting api for creating team
		$connect = new ConnectAPI();//connecting api for creating an organisation unit
		
		//Creating Organisation Unit
		$result = $connect->sendPostData($url_send,$str_data);
		if($result!=null){		
			$responseData = json_decode($result);
			if($connect->httpResponseCode==200){
				echo "true";
				//creating team
				/*$createTeamResult = $createTeam->sendPostData("http://".IP.":8065/api/v1/teams/create",$str_createTeamData);
				$responseTeamResult = json_decode($createTeamResult);
				if( $createTeam->httpResponseCode==200){
					//$team_id = $responseTeamResult->id;

					$data_for_Team_admin = array(
					   "team_id" => $responseTeamResult->id,
						"email" => $user_details->email,
						"username" => $user_details->username, 
						"password" => "admin",
						"name" => $responseTeamResult->name	
					);
					
					$url_4_creating_user ="http://".IP.":8065/api/v1/users/create";
					$json_data_admin = json_encode($data_for_Team_admin);//json data for admin of the team
					
					$createAdminApi = new ConnectAPI();
					$createAdminResult = $createAdminApi->sendPostData($url_4_creating_user,$json_data_admin);
					$createAdminResponse = json_decode($createAdminResult);
					if($createAdminApi->httpResponseCode==200){
						updateUserRole($createAdminResponse->id,$conn,"system_admin");
						//echo "true";
					}else if($createAdminApi->httpResponseCode==0)
						echo "Unable to connect API, try again later.";
					else
						echo "Failed to create admin: ".$createAdminResponse->message;
				}
				else if($createTeam->httpResponseCode==0) 
					echo "Unable to connect API for creating Team";
				else
					echo "Failed to create team ".$responseTeamResult->message;*/
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

?>
