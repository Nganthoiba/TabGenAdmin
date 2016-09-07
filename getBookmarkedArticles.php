<?php 
	/*this web service is only for  mobile app: getting bookmarked articles*/
	header('Content-Type: application/json');
	include('tabgen_php_functions.php');
	include('connect_db.php');
	
	if($conn){
		$token = $_GET['token'];//getting token
		$user_id = getUserIdByToken($conn,$token);
		
		if($user_id!=null){
			$item=null;
			$count=0;//counter
			
			/*getting articles of cme and references*/
			$query = "select Id,CreateAt,Name,Textual_content as article_detail,Images as Image from Article 
						where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
						and Active='true' order by CreateAt desc";
												//union
			$res=$conn->query($query);
			
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['Name']=str_replace("''","'",$row['Name']);
				$row['article_detail']=str_replace("''","'",$row['article_detail']);
				$row['Image']=($row['Image']==null)?"":$row['Image'];
				$row['images_url']=($row['Image']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
								"http://".SERVER_IP."/TabGenAdmin/".$row['Image'];
				$row['detail_url'] =  "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$row['Id'];
				//$row['Filenames']=getAttatchment($conn,$row['Id']);
				$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
				$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
				$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);	
				$item[]=$row; 
				$count++;		
			}
			
			/*getting news articles*/
			$query = "select Id,CreateAt,title as Name,headline as article_detail,Image from News
						where Id in (select article_id from BookmarkArticle where user_id='$user_id')
						and Active='true' order by CreateAt desc";	
			$res=$conn->query($query);
			
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['Name']=str_replace("''","'",$row['Name']);
				$row['article_detail']=str_replace("''","'",$row['article_detail']);
				$row['Image']=($row['Image']==null)?"":$row['Image'];
				$row['images_url']=($row['Image']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
								"http://".SERVER_IP."/TabGenAdmin/".$row['Image'];
				$row['detail_url'] =  "http://".SERVER_IP."/TabGenAdmin/get_mobile_news_article.php?news_id=".$row['Id'];
				//$row['Filenames']=getAttatchment($conn,$row['Id']);
				$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
				$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
				$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);	
				$item[]=$row; 
				$count++;		
			}			
						
			if($count==0){
				$response=array("status"=>false,
								"message"=>"You have not bookmarked any article.",
								"response"=>$item);
				print json_encode($response);
			}
			else{	
				$response=array("status"=>true,"response"=>$item);
				print json_encode($response);
			}
				/*
				$type = $_GET['type'];
				
				if(!empty($type)){
					$template_name=getTemplate($type);
					if($template_name!=null){
						if($template_name=="CME Template" || $template_name=="Reference Template"){
							
							$query = "select Id,Name,Textual_content,Images as Image,Links as external_link_url 
							from Article where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
							and DeleteAt=0 and Active='true' 
							and TabId in (select Tab.Id from Tab,TabTemplate where Tab.TabTemplate=TabTemplate.Id 
											and TabTemplate.Name='$template_name')
							order by CreateAt desc";
							$item=null;
							$res=$conn->query($query);
							$count=0;//counter
							while($row=$res->fetch(PDO::FETCH_ASSOC)){
								$row['Name']=str_replace("''","'",$row['Name']);
								$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
								$row['short_description']=substr($row['Textual_content'],0,80)."...";
								$row['Image']=($row['Image']==null)?"":$row['Image'];
								$row['images_url']=($row['Image']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
								"http://".SERVER_IP."/TabGenAdmin/".$row['Image'];
								$row['detail_url']="http://".SERVER_IP."/TabGenAdmin/getAnArticle.php?article_id=".$row['Id'];
								$row['external_link_url'] =  "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$row['Id'];
								$row['Filenames']=getAttatchment($conn,$row['Id']);
								$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
								$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
								$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);	
								$item[]=$row; 
								$count++;		
							}
							
						
							if($count==0){
								$response=array("status"=>false,
								"message"=>"You have not bookmarked any article.",
								"response"=>$item);
								print json_encode($response);
							}
							else{	
								$response=array("status"=>true,"response"=>$item);
								print json_encode($response);
							}
						}
						else{
							
							$query = "select Id,title,headline,Details as detail_url,Image from News
							where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
							and Active='true' order by CreateAt desc";
							$item=null;
							$res=$conn->query($query);
							$count=0;//counter
							while($row=$res->fetch(PDO::FETCH_ASSOC)){
								
								$row['title']=str_replace("''","'",$row['title']);
								$row['headline']=str_replace("''","'",$row['headline']);
								$row['detail_url']="http://".SERVER_IP."/TabGenAdmin/get_mobile_news_article.php?news_id=".$row['Id'];
								$row['Image']=$row['Image']==null?"":$row['Image'];
								$row['image_url']=$row['Image']==null?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
								"http://".SERVER_IP."/TabGenAdmin/".$row['Image'];
								$row['Attachments']=getAttatchment($conn,$row['Id']);
								$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
								$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);
								$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
								$item[]=$row; 
								$count++;		
							}
							
						
							if($count==0){
								$response=array("status"=>false,
								"message"=>"You have not bookmarked any news article.",
								"response"=>null);
								print json_encode($response);
							}
							else{	
								$response=array("status"=>true,"response"=>$item);
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
				}*/
				
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
