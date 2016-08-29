
<?php 
	
	/*getArticles_on_mobile_app.php: php file for listing out article*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
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
			$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links as external_link_url,Active 
			from Article where TabId='$tab_id' and DeleteAt=0 and Active='true' order by CreateAt desc";
			
			$item=null;
			$res=$conn->query($query);
			$count=0;
			
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['DeleteAt']=(double)$row['DeleteAt'];
				$row['UpdateAt']=(double)$row['UpdateAt'];
				$row['Name']=str_replace("''","'",$row['Name']);
				$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
				$row['short_description']=substr($row['Textual_content'],0,80)."...";
				$row['Images']=($row['Images']==null)?"":$row['Images'];
				//$row['Filenames']=($row['Filenames']==null)?"":$row['Filenames'];
				$row['images_url']=($row['Images']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":"http://128.199.111.18/TabGenAdmin/".$row['Images'];
				//$row['Filenames']=getFiles($conn,$row['Id']);
				$row['detail_url']="http://".SERVER_IP."/TabGenAdmin/getAnArticle.php?article_id=".$row['Id'];
				$row['external_link_url'] =  "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$row['Id'];
				$item[]=$row; 
				$count++;		
			}
			
			$outer_arr=null;
			$inner_arr=null;
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
				//$response=array("status"=>false,"message"=>"No article has been added for this tab.","response"=>$outer_arr);
				//print json_encode($response);
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
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
?>
