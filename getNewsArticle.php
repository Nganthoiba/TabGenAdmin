<?php 
/*php file for geting news articles for admin on web*/
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
						$query = "select Id,CreateAt,title,headline,Details,Link,Image,Active from News where tab_id='$tab_id' 
								order by CreateAt desc limit 10";
					}
					else if($loading_mode=="after"){
						$timestamp = $_GET['timestamp'];
						$query = "select Id,CreateAt,title,headline,Details,Link,Image,Active from News where tab_id='$tab_id' 
									and CreateAt>'$timestamp'
								order by CreateAt desc limit 10";
					}
					else if($loading_mode=="before"){
						$timestamp = $_GET['timestamp'];
						$query = "select Id,CreateAt,title,headline,Details,Link,Image,Active from News where tab_id='$tab_id' 
									and CreateAt<'$timestamp'
								order by CreateAt desc limit 10";
					}
					$res = $conn->query($query);
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						$row['CreateAt']=(double)$row['CreateAt'];
						$row['title']=str_replace("''","'",$row['title']);
						$row['headline']=str_replace("''","'",$row['headline']);
						$row['snippet']=substr($row['headline'],0,60)."...";
						$row['Details']=str_replace("''","'",$row['Details']);
						$row['Link']=$row['Link']==null?"":$row['Link'];
						$row['Image']=$row['Image']==null?"":$row['Image'];
						$row['Attachments']=getFiles($conn,$row['Id']);
						$output[]=$row;
					}
					$result->status=true;
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
