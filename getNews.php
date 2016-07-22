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
			$row['Details']=str_replace("''","'",$row['Details']);
			$row['snippet']=$row['Details']==""||$row['Details']==null?"":substr($row['Details'],0,60)."...";
			$row['Image']=$row['Image']==null?"":$row['Image'];
			$row['image_url']=$row['Image']==null?"":"http://128.199.111.18/TabGenAdmin/".$row['Image'];
			$row['Attachments']=getFiles($conn,$row['Id']);
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
		
		
		/*
		$outer_arr[]=array("item_count"=>$count,"items"=>$item);
		$response=array(response=>$outer_arr);
		print json_encode($response);*/
		
		/*$role_id='kh5suqiwzjgszncoxqtr7mn6fa';
		$teams=getOUs($conn,$user_id);//getting a list of user accessible OUs
		$output=null;
		$query=null;
		
		//echo "hi Size: ".sizeof($teams);
		$role_id = findRoleIdByUser_id($conn,$user_id);
		$role_name = getRoleByUserId($conn,$user_id);
		$accessible_teams=null;
		for($i=0;$i<sizeof($teams);$i++){//finding all the possible channels for a team
			$team_name = $teams[$i]['team_name'];
			$team_id = $teams[$i]['Id'];
			//echo $team_name."<br/>";
			
				//Getting OU specific channels
				$query = "SELECT Tab.Id as id,Tab.Name as tab_name
					FROM Tab,RoleTabAsson,Role,TabTemplate
						where Tab.Id=RoleTabAsson.TabId
							and Tab.TabTemplate=TabTemplate.Id
							and TabTemplate.Name='Latest News Template'
							and Tab.RoleId =Role.Id
                            and Role.OrganisationUnit='$team_name'
							and Tab.DeleteAt=0
							and RoleTabAsson.RoleId = '$role_id'";
						
				$res = $conn->query($query);
				if($res){
					$count=0;
					$channels=null; 
					$tab_list=null;
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						$channels[]=$row;
						$count++;
					}	
					if($count>0){
						$output->response->org_units[]=array("id"=>$team_id,"name"=>$team_name,"tabs"=>$channels);
					}
				}
		}
		
		//$final_array = array("team_list"=>$accessible_teams,"channels"=>$output);
		print json_encode($output);
		*/
	}	
}


?>
