<!DOCTYPE html>
<!-- This is a responsive mobile web view for displaying an article-->
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
		<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/main.css">
		
		<link href="css/toast.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/toast.js"></script>
		<!-- ********************************************** -->	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/npm.js"></script>
		<script src="homepage.js"></script>
		<style type="text/css">
			body {background-color:#FFFFFF}
		</style>
	</head>
	<body>
		<div class="col-sm-12" style="padding-bottom:10px">
		<?php
			include('tabgen_php_functions.php');
			include('connect_db.php');

			if(empty($_GET['article_id'])){
				echo "Please send article ID...";
			}
			else{
				$id = $_GET['article_id'];
				if($conn){
					$output=null;
					$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,
					Links as external_link_url,Active from Article where Id='$id'";
					$res=$conn->query($query);
					if($res){
						while($row=$res->fetch(PDO::FETCH_ASSOC)){
							//$row=$res->fetch(PDO::FETCH_ASSOC)
							$row['CreateAt']=(double)$row['CreateAt'];
							$row['DeleteAt']=(double)$row['DeleteAt'];
							$row['UpdateAt']=(double)$row['UpdateAt'];
							$row['Name']=str_replace("''","'",$row['Name']);
							echo "<div class='headline'><h1>".$row['Name']."</h1></div>";
							$row['Images']=($row['Images']==null)?"":$row['Images'];
							$row['images_url']=($row['Images']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
							"http://".SERVER_IP."/TabGenAdmin/".$row['Images'];
							if($row['Images']!=null && $row['Images']!=""){
								echo "<center><img width='100%' height='80%' src='".$row['Images']."'/></center>";
							}
							echo $row['Textual_content']."</br>";
							$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
							$row['short_description']=substr($row['Textual_content'],0,80)."...";
							$link=$row['external_link_url'];
							if($link!="" && $link!=null){
								if(getYouTubeID($link)!=null){
									$video_id=getYouTubeID($link);
									echo "<div class='videoWrapper'><iframe 
									allowfullscreen='true' src='https://www.youtube.com/embed/".$video_id."?autoplay=0'>
									</iframe></div>";
								}
								else{
									//echo curl($link);//"<a href='$link'>".$link."</a><br/>";
									//header('Location: '.$link);
									echo "<br/><center><a class='btn btn-success' href='$link'>
									Click here to see more</a></center>";
								}
							}
							
						}
						
					}
					else {
						
						echo "Sorry, unable to get result.";
					}
					
				}
				else{
					echo "Sorry, unable to connect database.";
				}
			}
			
			?>
		</div>
	</body>
</html>

