<?php 
	/*this web service is only for  mobile app: getArticles_on_mobile_app.php: php file for listing out article for CME and Reference tabs in 3,2,1 format.*/
	header('Content-Type: application/json');
	include('tabgen_php_functions.php');
	include('connect_db.php');
	
	if($conn){
		$token = $_GET['token'];//getting token
		$user_id = getUserIdByToken($conn,$token);
		
		if($user_id!=null){
			
				
				$type = $_GET['type'];/*this will tell which type of article, i.e. News or Reference or CME*/
				
				if(!empty($type)){
					$template_name=getTemplate($type);
					if($template_name!=null){
						if($template_name=="CME Template" || $template_name=="Reference Template"){
							$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links as external_link_url 
							from Article where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
							and DeleteAt=0 and Active='true' 
							and TabId in (select Tab.Id from Tab,TabTemplate where Tab.TabTemplate=TabTemplate.Id 
											and TabTemplate.Name='$template_name')
							order by CreateAt desc";
							$item=null;
							$res=$conn->query($query);
							$count=0;//counter
							while($row=$res->fetch(PDO::FETCH_ASSOC)){
								$row['CreateAt']=(double)$row['CreateAt'];
								$row['DeleteAt']=(double)$row['DeleteAt'];
								$row['UpdateAt']=(double)$row['UpdateAt'];
								$row['Name']=str_replace("''","'",$row['Name']);
								$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
								$row['short_description']=substr($row['Textual_content'],0,80)."...";
								$row['Images']=($row['Images']==null)?"":$row['Images'];
								$row['images_url']=($row['Images']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
								"http://".SERVER_IP."/TabGenAdmin/".$row['Images'];
								$row['detail_url']="http://".SERVER_IP."/TabGenAdmin/getAnArticle.php?article_id=".$row['Id'];
								$row['external_link_url'] =  "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$row['Id'];
								$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
								$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
								$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);	
								$item[]=$row; 
								$count++;		
							}
							/*Response in json*/
						
							if($count==0){
								$response=array("status"=>false,
								"message"=>"You have not bookmarked any article.",
								"response"=>$item);
								print json_encode($response);
							}
							else{	
								$response=array("status"=>true,"user_id"=>$user_id,"response"=>$item);
								print json_encode($response);
							}
						}
						else{
							/*for news aticles*/
							$query = "Id,CreateAt,title,headline,Details,Image from News
							where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
							and Active='true' 
							and tab_id in (select Tab.Id from Tab,TabTemplate where Tab.TabTemplate=TabTemplate.Id 
											and TabTemplate.Name='$template_name')
							order by CreateAt desc";
							$item=null;
							$res=$conn->query($query);
							$count=0;//counter
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
							/*Response in json*/
						
							if($count==0){
								$response=array("status"=>false,
								"message"=>"You have not bookmarked any news article.",
								"response"=>null);
								print json_encode($response);
							}
							else{	
								$response=array("status"=>true,"user_id"=>$user_id,"response"=>$item);
								print json_encode($response);
							}
						}
					}
					else{
						$response=array("status"=>false,
						"message"=>"You have passed invalid value of the parameter type which the value should be either News or Reference or CME","response"=>null);
						print json_encode($response);
					}
				}
				else{
					$response=array("status"=>false,
					"message"=>"You have to pass a parameter called 'type', which the value should be either News or Reference or CME","response"=>null);
					print json_encode($response);
				}
				
		}
		else{
			$message = $token==null?"You have not passed token":"Sorry, you have passed invalid or expired token.";
			echo json_encode(array("status"=>false,"response"=>null,
			"message"=>$message,"token"=>$token));
		}
	}
	else{
		echo json_encode(array("status"=>false,"response"=>null,"message"=>"Sorry, unable to connect database."));
	}
	/*function to get template name*/
	function getTemplate($type){
		$template_name=null;
		switch($type){
			case "CME": 
			case "cme": $template_name = "CME Template";
						break;
			case "Reference":
			case "reference": $template_name = "Reference Template";
						break;
			case "News": 
			case "news": $template_name = "Latest News Template";
						break;
			default: $template_name = null;
		}
		return $template_name;
	}
?>
