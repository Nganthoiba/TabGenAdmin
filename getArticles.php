<?php 
	/*php file for geting article*/
include('tabgen_php_functions.php');
include('connect_db.php');
$token = get_token_from_header();
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized, please login and try again."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token!, please login and try again."));
	}
	else{
		
		if(isAdmin($conn,$user_id)){//check if the user is admin or not
			$tab_id = $_GET['tab_id'];
			if($conn){
				if(empty($_GET['tab_id'])){
					echo json_encode(array("status"=>false,"message"=>"Sorry, you have not passed the tab ID."));
				}
				else if(!isTabExistById($conn,$tab_id)){
					echo json_encode(array("status"=>false,"message"=>"Sorry, the tab does not exists, you have passed an invalid tab ID."));
				}
				else{
					$output=null;
					$query=null;
					$loading_mode=$_GET['loading_mode'];
					if($loading_mode=="first_time_load"){
						$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links,Active 
					from Article where TabId='$tab_id' and DeleteAt=0 order by CreateAt desc limit 6";
					}
					else if($loading_mode=="after"){
						$timestamp = $_GET['timestamp'];
						$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links,Active 
						from Article where TabId='$tab_id' and DeleteAt=0 and CreateAt>'$timestamp' order by CreateAt desc limit 6";
					}
					else if($loading_mode=="before"){
						$timestamp = $_GET['timestamp'];
						$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links,Active 
						from Article where TabId='$tab_id' and DeleteAt=0 and CreateAt<'$timestamp' order by CreateAt desc limit 6";
					}
					
					$res = $conn->query($query);
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						$row['CreateAt']=(double)$row['CreateAt'];
						$row['DeleteAt']=(double)$row['DeleteAt'];
						$row['UpdateAt']=(double)$row['UpdateAt'];
						$row['Name']=str_replace("''","'",$row['Name']);
						$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
						$row['Images']=($row['Images']==null)?"":$row['Images'];
						//$row['Filenames']=($row['Filenames']==null)?"":$row['Filenames'];
						$row['images_url']=($row['Images']==null)?"":"http://".SERVER_IP."/TabGenAdmin/".$row['Images'];
						$row['Filenames']=getFiles($conn,$row['Id']);
						$output[]=$row;
					}
					$result->status=true;
					$result->template_type=getTemplateNameByTab_id($conn,$tab_id);
					$result->output=$output;
					echo json_encode($result);
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, you are not authorised for this request."));
		}
	}
}
?>
