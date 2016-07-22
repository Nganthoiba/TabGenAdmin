<?php
/* php code for getting list of news*/

if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$role_id = findRoleIdByUser_id($conn,$user_id);
		$query="select Id,CreateAt,title,headline,Details,Image from News where title in
		 (select Tab.Name from Tab where Tab.Id in (select TabId from RoleTabAsson where RoleId='$role_id')) order by CreateAt desc";
		
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
			$row['Details']="http://128.199.111.18/TabGenAdmin/get_mobile_news_article.php?news_id=".$row['Id'];
			//str_replace("''","'",$row['Details']);
			$row['Image']=$row['Image']==null?"":$row['Image'];
			$row['image_url']=$row['Image']==null?"":"http://128.199.111.18/TabGenAdmin/".$row['Image'];
			//$row['Attachments']=getFiles($conn,$row['Id']);
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
		$response=array("response"=>$outer_arr);
		print json_encode($response);
	}	
}


?>
