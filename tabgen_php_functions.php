<?php
/* function for creating a tab */	
function create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific){
	$id = randId(26);//creating unique id
	$createAt = time()*1000;
	$query=null;
	
	$org_name=$_POST['org_name'];
	$ou_name = $_POST['ou_name'];
	$role_name = $_POST['role_name'];
	$role_id = $_POST['role_id'];
	$flag = $ou_specific == "true"?1:0;
		
	$query="INSERT INTO Tab(Id,CreateAt,UpdateAt,DeleteAt,Name,RoleName,CreatedBy,TabTemplate,RoleId,OU_Specific,
					Organisation,OrganisationUnit)
				values('$id','$createAt','$createAt',0,'$tab_name','$role_name','$createdBy','$template_id','$role_id','$flag',
					'$org_name','$ou_name')";
		
	if($role_id==null){
		echo json_encode(array("status"=>false,"message"=>"Oops! Role does not exist. Please refresh the page and try again."));
	}
	else{
		if($conn->query($query)){
			$conn->query("insert into RoleTabAsson values('$role_id','$id')");//automatically associating default tab
			echo json_encode(array("status"=>true,"message"=>"Tab created successfully"));
		}
		else{ 
			echo json_encode(array("status"=>false,"message"=>"Oops! Something is not right, try again later"));
		}
	}	
}
//to update user role and display name
function updateUserRoleAndDisplayName($userId,$con,$role,$user_displayname){
	$query="UPDATE Users SET Roles='$role',FirstName='$user_displayname' WHERE Id='$userId'";
	if($con->query($query)){
		return true;
	}
	else 
		return false;
}
//function to set either the user has access right accross all other OU or not
function userUniversalAccess($conn,$user_id,$yes_no){
	$id = randId(26);
	$query="INSERT INTO UserUniversalAccessibility(Id,UserId,UniversalAccess) values('$id','$user_id',$yes_no)";
	$conn->query($query);
}
// function to test whether the user has Universal access right
function isUserUniversalAccessRight($conn,$user_id){
	$query="select * from User_OU_Mapping where user_id='$user_id'";
	$result = $conn->query($query);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$flag = (int)$row['UniversalAccess'];
	if($flag==1)
		return true;
	else
		return false;
}
	
function randId($length){
	$id = md5(uniqid());
	$char = str_shuffle($id);
	for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
		$rand .= $char{mt_rand(0, $l)};
	}
	return $rand;
}
/* function to find number of tabs of a particular role*/
function existingNoOfTabs($roleName,$org_unit,$conn){
	$res = $conn->query("SELECT COUNT(*) AS NO_OF_TABS 
							FROM Tab,Role 
							where Tab.RoleName='$roleName' and 
							Tab.RoleId=Role.Id and 
							Role.OrganisationUnit='$org_unit'");
	$row = $res->fetch(PDO::FETCH_ASSOC);
	$no_of_tabs = (int)$row['NO_OF_TABS'];
	return $no_of_tabs;
}
	
//to get OU id
function findOUId($conn,$org_unit){
	$query_result = $conn->query("select Id from OrganisationUnit where OrganisationUnit='$org_unit'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$ou_id = $row_data['Id'];
	if(isset($ou_id))
		return $ou_id;
	else return null;
}
// to get RoleId
function findRoleId($conn,$org_unit,$role_name){
	$query_result = $conn->query("select Id from Role where OrganisationUnit='$org_unit' and RoleName='$role_name'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$role_id = $row_data['Id'];
	if(isset($role_id))
		return $role_id;
	else return null;
}

function mapUserwithOU($conn,$user_id,$ou_id,$role_id,$type){
	$query = "insert into User_OU_Mapping values('$user_id','$ou_id','$role_id','$type')";
	return($conn->query($query));
}
//to get template Id
function findTemplateId($conn,$template_name){
	$query_result = $conn->query("SELECT Id,Name FROM TabTemplate where Name='$template_name'");
	$curr_row = $query_result->fetch(PDO::FETCH_ASSOC);
	$template_id = $curr_row['Id'];
	if(isset($template_id))
		return $template_id;
	else return null;
}
//for getting template name
function getTemplateName($conn,$template_id){
	$query_res = $conn->query("select TabTemplate.Name as Template_Name from TabTemplate where id='$template_id'");
	$result_row=$query_res->fetch(PDO::FETCH_ASSOC);
	return($result_row['Template_Name']);
}
//function to get Template name for a tab
function getTemplateNameByTab_id($conn,$tab_id){
	$query_res = $conn->query("select TabTemplate.Name as Template_Name 
	from TabTemplate where id=(select TabTemplate from Tab where Tab.Id='$tab_id')");
	$result_row=$query_res->fetch(PDO::FETCH_ASSOC);
	return($result_row['Template_Name']);
}

//to check if the user role in an OU is a universal or not
function isUniversalRole($conn,$role_name,$orgunit){
	$resp = $conn->query("select * from Role where RoleName='$role_name' and OrganisationUnit='$orgunit'");
	if($resp){
		$row = $resp->fetch(PDO::FETCH_ASSOC);
		$universal_role = $row['UniversalRole'];
		if($universal_role=="true")
			return true;
		else return false;
	}
	return false;
}
//to get team_name
function getTeamName($conn,$team_id){
	$result = $conn->query("select * from Teams where Id='$team_id'");
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$team_name = $row['Name'];
		return $team_name;
	}
	else
		return null;
}

//getting team id by username from the users table
function getTeamIdByUsername($conn,$user_name){
	$result = $conn->query("select * from Users where Username='$user_name'");
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$team_id = $row['TeamId'];
		return $team_id;
	}
	else
		return null;
}
//function for getting parent OU Id for an organisation
function getParentOuId($conn,$ou_id){
	$query="select ParentOUId from OUHierarchy where OUId='$ou_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['ParentOUId'];
}
// function to get OU Id (which the user belong) by providing user Id
function getOuIdByUserId($conn,$user_id){
	$query = "select * from User_OU_Mapping where user_id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['OU_id'];
}
//function to get user role by providing user id
function getRoleByUserId($conn,$user_id){
	$query="select Roles from Users where Id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Roles'];
}

//function to find role id by user id
function findRoleIdByUser_id($conn,$user_id){
	$query = "select * from User_OU_Mapping where user_id='$user_id'";
	$res = $conn->query($query);
	if($res){
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['RoleId'];
	}else{
		return null;
	}
}

//function to concate two arrays
function concate_array($arr1,$arr2){
	$res_arr = array();
	$i=0;
	for($i=0;$i<sizeof($arr1);$i++){
		$res_arr[$i]=$arr1[$i];
	}
	$j=0;
	for($j=0;$j<sizeof($arr2);$j++){
		$res_arr[($i+$j)]=$arr2[$j];
	}
	return $res_arr; 	
}

//function to find list of Teams accessible by the user by providing user id
function getTeams($conn,$user_id){
	$output=null;
	if(isUserUniversalAccessRight($conn,$user_id)){//checks whether the user is universal access right
		$query="select Teams.Name as team_name from Teams,OrganisationUnit where Teams.Name=OrganisationUnit order by team_name";
		$res = $conn->query($query);
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
		}
	}
	else{		
		$ou_id = getOuIdByUserId($conn,$user_id);
		$my_team = getTeamByOUId($conn,$ou_id);		
		$output= array(array("team_name"=>$my_team));
	}
	return $output;
}

//function to find list of OUs accessible by the user by providing user id
function getOUs($conn,$user_id){
	$output=null;
	$org = getOrg_Byuser_Id($conn,$user_id);
	if(isUserUniversalAccessRight($conn,$user_id)){//checks whether the user has universal access right
		$query="select Id,OrganisationUnit as team_name 
				from OrganisationUnit  
				where DeleteAt=0 and 
				Organisation='$org'
				order by team_name ";
		$res = $conn->query($query);
		if($res){
			$count=0;
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
				$count++;
			}
			//$output[$count]['team_name']="Associated Tabs";
		}
	}
	else{		
		$my_ou = getOU_Byuser_Id($conn,$user_id);
		$query="select Id,OrganisationUnit as team_name 
				from OrganisationUnit  
				where DeleteAt=0 and 
				OrganisationUnit='$my_ou'";
		$res = $conn->query($query);
		if($res){
			$count=0;
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
				$count++;
			}
		}
	}
	return $output;
}


//function to get OU name by providing OU id
function getOU_Byuser_Id($conn,$user_id){
	$query = "SELECT OrganisationUnit
				FROM User_OU_Mapping,OrganisationUnit
				WHERE OU_id=OrganisationUnit.Id
				and user_id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['OrganisationUnit'];
}
//function to get OU name by tab name
function getOU_by_tab_Name($conn,$tab_name){
	$query = "select OrganisationUnit from Tab where Name='$tab_name'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['OrganisationUnit'];
}
//function to get OU name by providing OU id
function getOrg_Byuser_Id($conn,$user_id){
	$query = "SELECT Organisation
				FROM User_OU_Mapping,OrganisationUnit
				WHERE OU_id=OrganisationUnit.Id
				and user_id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Organisation'];
}

//function to get team name by providing OU id
function getTeamByOUId($conn,$ou_id){
	$query="select Teams.Name as team_name, OrganisationUnit as org_unit_name 
			from Teams,OrganisationUnit 
			where Teams.Name=OrganisationUnit and 
				OrganisationUnit.Id='$ou_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['team_name'];
}

//function to update role type
function updateRoleType($conn,$role_id,$role_type){
	if($conn){
		$query = "Update Role set RoleType='$role_type' where Id='$role_id'";
		$conn->query($query);
	}
}

//function to get role_type using role_name
function getRoleType($conn,$role_name){
	$query = "select RoleType from Role where RoleName='$role_name'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['RoleType'];
}


//function to get users in a channel
function getUserInPrivateMessageChannel($conn,$channel_id,$my_id){
	$query = "select UserId, Username
				from ChannelMembers,Users
				where UserId=Users.Id and
				ChannelId='$channel_id' and
				UserId != '$my_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Username'];
}
//function to get name of an Organisation Unit by providing OU id
function getOUNameByOuId($conn,$ou_id){
	$query = "select * from OrganisationUnit
				where Id='$ou_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['OrganisationUnit'];
}
//function to get team id by providing OU name
function getTeamId_by_OU_name($conn,$ou_name){
	$query = "select * from Teams where Name='$ou_name'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Id'];
}

//function to get channel details by using channel name
function getChannelByName($conn,$channel_name){
	$query = "select * from Channels where DisplayName='$channel_name' and DeleteAt=0";
	$res = $conn->query($query);
	$count=0;
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		$output[]=$row;
		$count++;
	}
	if($count==1)
		return $output;
	else return null;	
}

//function to check if tab name already exists by using tab name
function isTabExist($conn,$tab_name){
	$query = "select count(*) as count from Tab where Name='$tab_name' and DeleteAt=0";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
			return true;
	}
	else{
			return false;
	}
}
//function to check if tab name already exists by using tab ID
function isTabExistById($conn,$tab_id){
	$query = "select count(*) as count from Tab where Id='$tab_id' and DeleteAt=0";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
			return true;
	}
	else{
			return false;
	}
}
/*function to check if article of a particular name exists*/
function isArticleExist($conn,$name){
	$query = "select count(*) as count from Article where Name='$name' and DeleteAt=0";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
		return true;
	}
	else{
		return false;
	}
}

//function to check if tabstrip name already exists
function isTabstripExist($conn,$tabstrip_name){
	$query = "select count(*) as count from Tabstrip where Name='$tabstrip_name' and DeleteAt=0";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
			return true;
	}
	else{
			return false;
	}
}

function getUserNameById($conn,$user_id){
	$name=null;
	$query = "select * from Users where Id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	/*if($row['FirstName']!=null)
		$name=$row['FirstName'];
	else */
	if($row['Username']!=null)
		$name=$row['Username'];
	return $name;
}

function getUserDisplayNameById($conn,$user_id){
	$name=null;
	$query = "select * from Users where Id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if($row['FirstName']!=null)
		$name=$row['FirstName'];
	else if($row['Username']!=null)
		$name=$row['Username'];
	return $name;
}

//function to check if the tab is already associated to the specific role
	function isTabAlreadyAssociated($conn,$role_id,$tab_id){
		$query = "select count(*) as count from RoleTabAsson where RoleId='$role_id' and TabId='$tab_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$count = (int)$row['count'];
		if($count>0)
			return true;
		else 
			return false;
	}
	//getting a tab with its template name
	function getTabWithTemplate($conn,$tab_id){
		$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
				FROM Tab,TabTemplate
				where Tab.TabTemplate=TabTemplate.Id and Tab.Id='$tab_id'";
		$res = $conn->query($query);
		if($res){
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		else 
			return null;
	}
	
	//function to delete a tab along with channels 
	function deleteATab($conn,$tab_id,$token_id){
		$tab_details = getTabWithTemplate($conn,$tab_id);
			if($tab_details['Template_Name']=="Chat Template"){
				if($token_id!=null){
					/*getting channel details for the channel having same name as the earlier tab name*/
					$tab_name=$tab_details['Name'];
					$channel_details = getChannelByName($conn,$tab_name);//this returns null of the channel does not exists
					if($channel_details!=null){
						/* it means a channel already exists with the same name as tab name, so we are going to delete that channel name
						with the new tab name.	*/		
						
						$delete_channel_data = null;
											
						$delete_channel_url = "http://".IP.":8065/api/v1/channels/".$channel_details[0]['Id']."/delete";
						$deleteChannel = new ConnectAPI();
						$delete_channel_response = json_decode($deleteChannel->sendPostDataWithToken($delete_channel_url,$delete_channel_data,$token_id));
						if($deleteChannel->httpResponseCode==200){
							//it means channel has been deleted successfully
							return deleteTab($conn,$tab_id);	
						}
						else if($deleteChannel->httpResponseCode==0){
							return json_encode(array("status"=>false,"message"=>"Unable to connect API for updating channel name"));
						}
						else{
							return json_encode(array("status"=>false,"message"=>$delete_channel_response->message));
						}	
					}else{
						return json_encode(array("status"=>false,"message"=>"No channel exists with the earlier tab name"));
					}
					
				}
				else{
					return json_encode(array("status"=>false,"message"=>"Token not found. Login again."));
				}
						
			}
			else{
				//deleting Tabs which is not chat template
				return deleteTab($conn,$tab_id);
			}
		
	}
	//this function will delete a tab from the table in database
	function deleteTab($conn, $tab_id){
		$time = time()*1000;
		/*delete all the tuples from the role and tab association where tab id is $tab_id*/
		$query1 = "delete from RoleTabAsson where TabId='$tab_id'";
		/*setting DeleteAt time of a tab*/
		$query2 = "update Tab set DeleteAt='$time' where Id='$tab_id'";
		if($conn->query($query1) && $conn->query($query2)){	
			return json_encode(array("status"=>true,"message"=>"Successfully deleted"));
		}
		else{
			return json_encode(array("status"=>false,"message"=>"Failed to delete"));
		}		
	}
	
	//function to update first name (display name)
	function updateUserFirstName($conn,$user_id,$display_name){
		$query = "update Users set FirstName='$display_name' where Id='$user_id'";
		$conn->query($query);
	}
	
	//function to get number of replies of a message
	function getNoOfReplies($conn,$post_id){
		$query = "select count(*) as no_of_replies from Posts where RootId='$post_id'";
		$res=$conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return (int)$row['no_of_replies'];
	}
	
	/*****************************LIKE FUNCTIONALITIES OF AN ARTICLE**************************************/
	
	//function to check whether the user has already liked the article or not
	function isArticleAlreadyLiked($conn,$article_id,$user_id){
		$query = "select count(*) as count from LikeArticle where article_id='$article_id' and user_id='$user_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)//checks for existence of row
			return true;
		else
			return false;
	}
	//function to dislike an article
	function dislikeAnArticle($conn,$article_id,$user_id){
		$query = "delete from LikeArticle where article_id='$article_id' and user_id='$user_id'";
		$res = $conn->query($query);
		return $res;
	}

	//function to like an article
	function likeAnArticle($conn,$article_id,$user_id){
		$query = "insert into LikeArticle values('$article_id','$user_id')";
		$res = $conn->query($query);
		return $res;
	}
	//function to count the number of likes for a particular article
	function getNoOfLikesOfArticle($conn,$article_id){
		$query = "select count(*) as count from LikeArticle where article_id='$article_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return (int)$row['count'];
	}
	/***********************END OF LIKE FUNCTIONALITIES OF AN ARTICLE**************************/
	
	
	/****************************BOOKMARK FUNCTIONALITIES OF AN ARTICLE************************/
	//function to bookmark an article
	function bookmarkAnArticle($conn,$article_id,$user_id){
		if(isArticleAlreadyBookmarked($conn,$article_id,$user_id)){
			return true;//in case if the article has already been bookmarked
		}
		else{
			$query = "insert into BookmarkArticle values('$article_id','$user_id')";
			$res = $conn->query($query);
			return $res;
		}
	}
	
	//function to check whether an article has already been bookmarked by a user or not
	function isArticleAlreadyBookmarked($conn,$article_id,$user_id){
		$query="select count(*) as count from BookmarkArticle where article_id='$article_id' and user_id='$user_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)//check for existence of row
			return true;//in case if row exists
		else
			return false;//in case if row does not exist
	}
	
	//function to unbookmark an article
	function unbookmarkAnArticle($conn,$article_id,$user_id){
		if(isArticleAlreadyBookmarked($conn,$article_id,$user_id)){
			//in case if the article has already been bookmarked, then remove the article from the bookmarked list of the user
			$query = "delete from BookmarkArticle where article_id='$article_id' and user_id='$user_id'";
			$res = $conn->query($query);
			return $res;
		}
		else{
			return true;
		}
	}
	/***********************END OF BOOKMARK FUNCTIONALITIES OF AN ARTICLE**********************/
	
	
	/*****************************LIKE FUNCTIONALITIES OF A POST**************************************/
	
	//function to check whether the user has already liked the post or not
	function isAlreadyLiked($conn,$post_id,$user_id){
		$query = "select count(*) as count from Likes where post_id='$post_id' and user_id='$user_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)//check for existence of row
			return true;
		else
			return false;
	}

	//function to dislike a post
	function dislikeAPost($conn,$post_id,$user_id){
		$query = "delete from Likes where post_id='$post_id' and user_id='$user_id'";
		$res = $conn->query($query);
		return $res;
	}

	//function to like a post
	function likeAPost($conn,$post_id,$user_id){
		$query = "insert into Likes values('$post_id','$user_id')";
		$res = $conn->query($query);
		return $res;
	}
	
	//function to count the number of likes for a particular messsage
	function getNoOfLikes($conn,$post_id){
		$query = "select count(*) as count from Likes where post_id='$post_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return (int)$row['count'];
	}
	
	/***********************END OF LIKE FUNCTIONALITIES OF A POST**************************/
	
	
	/***********************BOOKMARK FUNCTIONALITIES OF A POST******************************/
	//function to add bookmark
	function addBookmark($conn,$post_id,$user_id){
		//check if the post has already been bookmarked or not
		if(!isAlreadyBookmarked($conn,$post_id,$user_id)){
			//in case if the post has not already been bookmarked
			$time = time()*1000;
			$id = randId(26);
			$query="insert into Bookmark(Id,PostId,UserId,BookmarkAt) 
					values('$id','$post_id','$user_id','$time')";
			$res = $conn->query($query);
			return $res;
		}
		else{
			return true; //in case if the post has already been bookmarked
		}
	}
	
	//function to remove bookmark
	function removeBookmark($conn,$post_id,$user_id){
		//check if the post has already been bookmarked or not
		if(isAlreadyBookmarked($conn,$post_id,$user_id)){
			//in case if the post has already been bookmarked
			$query="delete from Bookmark where PostId='$post_id' and UserId='$user_id'";
			$res = $conn->query($query);
			return $res;
		}
		else{
			return true; //in case if the post has not already been bookmarked
		}
	}
	
	//function to check whether a post has already been bookmarked by a user or not
	function isAlreadyBookmarked($conn,$post_id,$user_id){
		$query="select count(*) as count from Bookmark where PostId='$post_id' and UserId='$user_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)//check for existence of row
			return true;//in case if row exists
		else
			return false;//in case if row does not exist
	}
	
	//function to get a list of bookmarked post
	function getBookmarks($conn,$user_id){
		$query="select Posts.* from Bookmark,Posts 
				where Posts.Id=Bookmark.PostId
				and Bookmark.UserId='$user_id' 
				order by BookmarkAt desc";
		$res = $conn->query($query);
		$output=null;
		while($row = $res->fetch(PDO::FETCH_ASSOC)){
			$output->order[]=$row['Id'];
			$output->posts->$row['Id']->id=$row['Id'];
			$output->posts->$row['Id']->create_at=(double)$row['CreateAt'];
			$output->posts->$row['Id']->update_at=(double)$row['UpdateAt'];
			$output->posts->$row['Id']->delete_at=(double)$row['DeleteAt'];
			$output->posts->$row['Id']->user_id=$row['UserId'];
			$output->posts->$row['Id']->channel_id=$row['ChannelId'];
			$output->posts->$row['Id']->root_id=$row['RootId'];
			$output->posts->$row['Id']->parent_id=$row['ParentId'];
			$output->posts->$row['Id']->original_id=$row['OriginalId'];
			$output->posts->$row['Id']->message=$row['Message'];
			$output->posts->$row['Id']->type=$row['Type'];
			$output->posts->$row['Id']->props=json_decode($row['Props']);
			$output->posts->$row['Id']->hashtags=$row['Hashtags'];
			$output->posts->$row['Id']->filenames=json_decode($row['Filenames']);
			$output->posts->$row['Id']->no_of_reply=getNoOfReplies($conn,$row['Id']);
			$output->posts->$row['Id']->no_of_likes=getNoOfLikes($conn,$row['Id']);
			$output->posts->$row['Id']->isLikedByYou=isAlreadyLiked($conn,$row['Id'],$user_id);
			$output->posts->$row['Id']->isBookmarkedByYou=isAlreadyBookmarked($conn,$row['Id'],$user_id);
		}
		return json_encode($output);
	}
	
	/***********************END OF BOOKMARK FUNCTIONALITIES OF A POST**************************/
	
	//function to find the number of members in a particular channel
	function getMembersCount($conn,$channel_id){
		$query="select count(*) as members_count from ChannelMembers where ChannelId='$channel_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return (int)$row['members_count'];
	}
	
	//function to get channel_id by post_id from the channel table
	function getChannelIdByPost_id($conn,$post_id){
		$query="select ChannelId from Posts where Id='$post_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['ChannelId'];
	}
	
	function getChannelNameById($conn,$channel_id){
		$query="select DisplayName from Channels where Id='$channel_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['DisplayName'];
	}
	
	//function to get OU by role
	function getOUbyRole($conn,$role_id){
		$query = "select Role.Id,Role.RoleName,OrganisationUnit.OrganisationUnit as OU,Organisation
					from OrganisationUnit,Role
					where Role.OrganisationUnit=OrganisationUnit.OrganisationUnit
					and Role.Id='$role_id'";
					
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['OU'];
	}
	
	//function to get role id by Tab id
	function getRolebyTab($conn,$tab_id){
		$query = "select RoleId from Tab where Id='$tab_id'";			
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['RoleId'];
	}
	
	//function to check whether a tab is OU specific or not
	function isTabOUSpecific($conn,$tab_id){
		$query = "select OU_Specific from Tab where Id='$tab_id'";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['OU_Specific']==1){
			return true;
		}
		else{
			return false;
		}
	}
	
	//function to get role id by Tab id
	function getRoleNamebyId($conn,$role_id){
		$query = "select RoleName from Role where Id='$role_id'";			
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['RoleName'];
	}
	
	//function to associate a tab to a particular role
	function associateTabToRole($conn,$role_id,$tab_id){
		$query = "insert into RoleTabAsson values('$role_id','$tab_id')";
		if($role_id!=null){
			if(!isTabAlreadyAssociated($conn,$role_id,$tab_id)){
				if($conn->query($query)){
					echo json_encode(array("status"=>true,"message"=>"Successfully associated."));
				}
				else {
					echo json_encode(array("status"=>false,"message"=>"Unable to associate, an internal problem occurs."));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Tab is already associated!"));
			}		
		}
		else 
			echo json_encode(array("status"=>false,"message"=>"Role does not exist!"));
		
	}
	
//function to get news details
function getNewsDetails($conn,$tab_name){
	$query = "select * from News where title='$tab_name'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Details'];
}

function getOrgbyOU($conn,$ou){
	$query = "select * from OrganisationUnit where OrganisationUnit='$ou'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['Organisation'];
}
//get attatched files of an article

function getAttatchment($conn,$article_id){
	$query = "select Id,caption,file_name from ArticleFiles where article_id='$article_id'";
	$res = $conn->query($query);
	$files_output=array();
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		$row['file_type']=getFileType($row['file_name']);
		$row['attachment_url']="http://".SERVER_IP."/TabGenAdmin/".$row['file_name'];
		$row['file_name']=substr($row['file_name'],strripos($row['file_name'],"/")+1);
		$row['caption']=($row['caption']==null)?"":$row['caption'];
		$files_output[]=$row;
	}
	return $files_output;
}

function getFileType($filename){
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$file_type="";
	switch($ext){
		case "gif": 
		case "jpeg":
		case "png":	
		case "bmp":
		case "jpg":	$file_type="image";
					break;
		case "pdf": $file_type="pdf";
					break;
		case "docx":
		case "doc":	$file_type="word";
					break;
		case "pptx":
		case "ppt":	$file_type="power_point";
					break;	
		case "mkv":
		case "mpeg":
		case "mp4":	$file_type="video";
					break;
		default: 	$file_type="others";	
	}
	return $file_type;
}
//get list of attached files in an article
function getFiles($conn,$article_id){
	$query = "select Id,caption,file_name from ArticleFiles where article_id='$article_id'";
	$res = $conn->query($query);
	$files_output=array();
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		$row['file_type']=pathinfo($row['file_name'], PATHINFO_EXTENSION);
		$row['file_icon']=file_icon($row['file_name']);
		$row['attachment_url']="http://128.199.111.18/TabGenAdmin/".$row['file_name'];
		$row['caption']=($row['caption']==null)?"":$row['caption'];
		$files_output[]=$row;
	}
	return $files_output;
}

function file_icon($filename){
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$icon_path="file_icons/";
	switch($ext){
		case "gif": $icon_path=$icon_path."gif.png";
					break;
		case "jpeg":
		case "jpg":	$icon_path=$icon_path."jpeg.png";
					break;
		case "zip":	$icon_path=$icon_path."zip.png";
					break;
		case "pdf": $icon_path=$icon_path."pdf.png";
					break;
		case "xlsx": $icon_path=$icon_path."xlsx.png";
					break;
		case "accdb":$icon_path=$icon_path."accdb.png";
					break;
		case "docx":
		case "doc":	$icon_path=$icon_path."docx_win.png";
					break;
		case "pptx":
		case "ppt":	$icon_path=$icon_path."pptx_win.png";
					break;
		case "bmp":	$icon_path=$icon_path."bmp.png";
					break;
		case "rar":	$icon_path=$icon_path."rar.png";
					break;
		case "psd":	$icon_path=$icon_path."psd.png";
					break;	
		case "png":	$icon_path=$icon_path."png.png";
					break;
		case "mp4":	$icon_path=$icon_path."mp4.png";
					break;
		case "java":	$icon_path=$icon_path."java.png";
					break;
		default: 	$icon_path=$icon_path."default.png";	
	}
	return $icon_path;
}

/* function to check if news with a given title already exists or not */
function isNewsTitleExists($conn,$title){
	$query = "select count(*) as count from News where title='$title'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
			return true;
	}
	else{
			return false;
	}
}
/*check if the user is admin or not*/
function isAdmin($conn,$user_id){
	$query = "select * from Users where Id='$user_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if($row['Roles']=='system_admin')
		return true;
	else
		return false;
}

/*php function to check whether the user is a valid user or not.*/
function isValidUser($conn,$user_id){
	$query = "select count(*) as count from Users where Id='$user_id' and DeleteAt=0";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
		return true;
	}
	else{
		return false;
	}
}
/*get file names from db*/
function get_filename($conn,$file_id){
	$query = "select * from ArticleFiles where Id='$file_id'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	return $row['file_name'];
}
/*delete a file*/
function delete_a_file($file_name){
	if(unlink($file_name) || !file_exists($file_name)){
		return true;
	}
	else{
		return false;
	}
}

/*getting the whole contents of a website*/
function curl($url) {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
 }
 
/*php function to parse header data received from other api*/
function http_parse_headers( $header )
{
	$retVal = array();
	$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
	foreach( $fields as $field ) {
		if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
			$match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
		    if( isset($retVal[$match[1]]) ) {
				$retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
		    } 
		    else{
		        $retVal[$match[1]] = trim($match[2]);
		    }
		}
	}
	return $retVal;
}
//function to extract token which was stored in session/cookies at the time of login
function get_token(){
		$token=null;
		if(isset($_COOKIE['login_header_response'])){
			$header = $_COOKIE['login_header_response'];
			$token = getTokenFromHeader($header);
		}
		else 
			$token=null;
											
		return $token;
}
/*parse for token*/
function getTokenFromHeader($header){
	$header_array = http_parse_headers($header); 
	$token = null;				
	foreach ($header_array as $name => $value) {
		if($name=="Token"){
			$token = $value;
			break;
		}
	}
	return $token;
}
/*php function to get token / any data from header passed from client side.*/	
function get_token_from_header(){
	$request_header=null;
	if (isset($_SERVER['Authorization'])) {
		$request_header = $_SERVER['Authorization'];
		return $request_header;
	} else {
		if (function_exists('getallheaders')) {
			foreach (getallheaders() as $header_name => $header_value) {
				if ($header_name == 'Authorization') {
					$request_header = $header_value;
				}
			}
			return $request_header;
		}
		else return null;
	}
}
/*getting user id from token*/
function getUserIdByToken($conn,$token){
	$query = "select UserId from Sessions where Token='$token'";
	$res = $conn->query($query);
	if($res){
		$row = $res->fetch(PDO::FETCH_ASSOC);
		return $row['UserId'];
	}
	else return null;
}
/* function to get youtube video id*/
function getYouTubeID($youtube_url){
	if(is_youtube_url($youtube_url)){
	$regex = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
	$YouTubeCheck = preg_match($regex, $youtube_url, $Data);
		If($YouTubeCheck){
			$VideoID = $Data[4];
			return $VideoID;
		}
		else{
			return null;
		}
	}
	else{
		//echo "Not a youtube url...<br/>";
		return null; 
	}
}
/*function to check whether the url is of youtube or not. */
function is_youtube_url($youtube_url){
	$valid = preg_match("/((http\:\/\/){0,}(www\.){0,}(youtube\.com){1} || (youtu\.be){1}(\/watch\?v\=[^\s]){1})/", $youtube_url);
	return $valid;	
}

function get_notification_tokens_for_chat_tabs($conn,$post_id,$user_id){
	/*
	  select user_id  from User_OU_Mapping where RoleId in (select RoleId from RoleTabAsson where TabId = (select Id from Tab where Name=(Select DisplayName from Channels,Posts where Channels.Id=ChannelId and Posts.Id='g5tc6rimwid7jkotknmzx3g86o')));
	*/
	$query="select token_id, user_id from FCM_Users
	  where user_id in (select user_id from User_OU_Mapping 
	  where RoleId in (select RoleId from RoleTabAsson 
	  where TabId = (select Id from Tab where Name=(Select DisplayName from Channels,Posts 
	  where Channels.Id=ChannelId and Posts.Id='$post_id'))))";
	  
	$res = $conn->query($query);
	$tokens = array();
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		if($row['user_id']!=$user_id)
			$tokens[]=$row['token_id'];
	}
	return $tokens;
}

/*FCM Services for sending messages to GOOGLE seerver in php*/

function sendFirebasedCloudMessage($fcm_token, $message) {
	/*fcm_token is a list of tokens*/
    $apiKey = 'AIzaSyAnnGIii3XM3HfV5n8WsFatXluNr8bAiSo';
    
    $url = 'https://fcm.googleapis.com/fcm/send';
	 //'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $fcm_token,
        'data' => $message
    );
    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, flase);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if($result==false){
		die('Curl failed: '.curl_error($ch));
	}
	return $result;   
}
?>
