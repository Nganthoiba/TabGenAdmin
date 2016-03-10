<?php
						
		include("ConnectAPI.php");$array = array("createdBy"=>"kh.nganthoiba");
						$url_send ="http://188.166.210.24:8065/api/v1/organisationUnit/track";
						$str_data = json_encode($array);
						$connect = new ConnectAPI();
						$result = $connect->sendPostData($url_send,$str_data);
						if($result!=null){
							try{
								$responseData = json_decode($result);
								if($connect->httpResponseCode==200){
									if(isarray($responseData)){
										foreach($responseData as $key => $val){
											if($key == "organisation_unit")
											echo "<li><label class='orgname'>".$val."</label></li>";
										}
									}
								}else if($connect->httpResponseCode==0){
									echo "Unable to connect API";
								}
								else 
									echo $responseData->message;
							}catch(Exception $e){
									echo "Exception: ".$e->getMessage();
							}
						}

					?>
