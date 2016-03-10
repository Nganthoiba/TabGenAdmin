
<?php	include('server_IP.php');
	if(!empty($_GET['method']))
	{			
		session_start();
		if(!isset($_SESSION['user_details'])){
                echo "<p align='center'>You have to <a href='index.html'>login</a> first<br/>";
        }
        else {
                $user_data = json_decode($_SESSION['user_details']);
                $user_name = $user_data->username;
				include ('ConnectAPI.php');
				$method = $_GET['method'];
				$type = empty($_GET['viewType'])?"all":$_GET['viewType'];
				echo getTemplateList($user_name,$method,$type);
		}
	}
	else echo "no parameter passed";

	function getTemplateList($username,$method,$type){
		$data="";
		$array = array("name"=>$username);
		$url_send ="http://".IP.":8065/api/v1/tabtemplate/track";
		$str_data = json_encode($array);
		$connect = new ConnectAPI();
		$result = $connect->sendPostData($url_send,$str_data);
				
		if($result!=null){
			try{
				$responseData = json_decode($result);
				$limit = $type=="all"?sizeof($responseData):5;
				if($connect->httpResponseCode==200){					
					if($method == "list"){
						for($j=0;$j<$limit;$j++){
							$data = $data."<li><label class='orgname'>".$responseData[$j]->name."</label></li>";
						}
					} 
					else {
						$data="<option></option>";
						for($j=0;$j<$limit;$j++){
							$data = $data."<option>".$responseData[$j]->name."</option>";
						}
					}
				}else if($connect->httpResponseCode==0){
					$data = "Unable to connect API";
				}
				else 
					$data = $responseData->message;
			}catch(Exception $e){
				$data = "Exception: ".$e->getMessage();
			}
		}
		else $data = "Unable to connect API";
		return $data;
	}
?>
