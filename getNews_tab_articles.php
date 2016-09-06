<?php
/* Mobile app api: php code for getting list of news articles in 3,2,1 format*/

if(!empty($_GET['tab_id'])){
	$tab_id = $_GET['tab_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		//$role_id = findRoleIdByUser_id($conn,$user_id);
		$token = $_GET['token'];
		$user_id = getUserIdByToken($conn,$token);
		if($user_id!=null){
			$query = "select Id,CreateAt,title,headline,Details,Image from News where tab_id='$tab_id' and Active='true'
							order by CreateAt desc ";
			$outer_arr=null;
			$inner_arr=null;
			$item=null;
			$res=$conn->query($query);
			$count=0;
			
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['title']=str_replace("''","'",$row['title']);
				$row['headline']=str_replace("''","'",$row['headline']);
				//$row['snippet']=$row['Details']==""||$row['Details']==null?"":substr($row['Details'],0,160)."...";
				$row['Details']="http://".SERVER_IP."/TabGenAdmin/get_mobile_news_article.php?news_id=".$row['Id'];
				$row['Image']=$row['Image']==null?"":$row['Image'];
				$row['image_url']=$row['Image']==null?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":"http://".SERVER_IP."/TabGenAdmin/".$row['Image'];
				$row['Attachments']=getAttatchment($conn,$row['Id']);
				$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
				$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);
				$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
				$item[]=$row; 
				$count++;		
			}
			
			if($count>3){
				$i=0;
				while($i<=2){
					$inner_arr[$i]=$item[$i];
					$i++;
				}
				$outer_arr[]=array("item_count"=>$i,"items"=>$inner_arr);
				$j=$i;
				while($j<$count){
					$k=0;
					$grp_arr=null;
					$lim=($count-$j>=2)?2:$count-$j;
					for($k=0;$k<$lim;$k++){
						if($k>0)
							$grp_arr[$k]=$item[$j+1];
						else
							$grp_arr[$k]=$item[$j];
					}
					$outer_arr[]=array("item_count"=>$k,"items"=>$grp_arr);
					$j=$j+$lim;
				}	
			}
			else if($count==0){
				$outer_arr=null;
			}
			else{
				$j=0;
				while($j<$count){
					$inner_arr[$j]=$item[$j];
					$j++;
				}
				$outer_arr[]=array("item_count"=>$j,"items"=>$inner_arr);
			}
			$response=array("status"=>true,"response"=>$outer_arr);
			print json_encode($response);
		}
		else{
			print json_encode(array("status"=>false,"response"=>null,"message"=>"You have passed invalid token."));
		}
	}
	else{
		print json_encode(array("status"=>false,"response"=>null,"message"=>"Failed to connect database."));
	}	
}
else{
	print json_encode(array("status"=>false,"response"=>null,"message"=>"You have not sent tab id."));
}


?>

