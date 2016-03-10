<?php
	if(!empty($_POST['method']))
	{	
		$method = $_POST['method'];
		include("ConnectAPI.php");
		include("server_IP.php");
		session_start();
		if(!isset($_SESSION['user_details'])){
                echo "<p align='center'>You have to <a href='index.html'>login</a> first, Your session is expired<br/>";
        }
        else {
                $user_data = json_decode($_SESSION['user_details']);
                $user_name = $user_data->username;
				$array = array("createdBy"=>$user_name);
				$url_send ="http://".IP.":8065/api/v1/organisation/track";
				$str_data = json_encode($array);
				$connect = new ConnectAPI();
				$result = $connect->sendPostData($url_send,$str_data);
				$str="";
				//$orgList = Array();
				if($result!=null){
					try{
						$responseData = json_decode($result);
						if($connect->httpResponseCode==200){
							//$orgList=parseOrgList($responseData,"name");
							for($j=0;$j<sizeof($responseData);$j++){
								if($method == "list")
									$str = $str."<li><label class='orgname'>".$responseData[$j]->name."</label></li>";
								else if($method == "dropdown")
									$str = $str."<option>".$responseData[$j]->name."</option>";
							}
							echo $str;
						}else if($connect->httpResponseCode==0){
							echo "<li>Unable to connect API</li>";
						}
						else 
							echo "<li>".$responseData->message."</li>";
					}catch(Exception $e){
							echo "Exception: ".$e->getMessage();
					}
				}
				else echo "<li>Unable to connect API</li>";
		}
	}
?>
