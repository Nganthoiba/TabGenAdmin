<!DOCTYPE html>
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
		
		<!-- This is what you need for sweet alert -->
		<script src="dist/sweetalert-dev.js"></script>
		<link rel="stylesheet" href="dist/sweetalert.css">
		<!--.......................-->
		
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
				//echo json_encode(array("status"=>false,"message"=>"Please send article ID"));
				echo "Please send article ID...";
			}
			else{
				$id = $_GET['article_id'];
				if($conn){
					$output=null;
					$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links as external_link_url,Active 
						from Article where Id='$id'";
					$res=$conn->query($query);
					if($res){
						while($row=$res->fetch(PDO::FETCH_ASSOC)){
							$row['CreateAt']=(double)$row['CreateAt'];
							$row['DeleteAt']=(double)$row['DeleteAt'];
							$row['UpdateAt']=(double)$row['UpdateAt'];
							$row['Name']=str_replace("''","'",$row['Name']);
							echo "<div class='headline'><h1>".$row['Name']."</h1></div>";
							$row['Images']=($row['Images']==null)?"":$row['Images'];
							$row['images_url']=($row['Images']==null)?"http://128.199.111.18/TabGenAdmin/img/noimage.jpg":
							"http://128.199.111.18/TabGenAdmin/".$row['Images'];
							if($row['Images']!=null){
								echo "<center><img class='img-thumbnail' src='".$row['Images']."'/></center>";
							}
							echo $row['Textual_content']."</br>";
							$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
							$row['short_description']=substr($row['Textual_content'],0,80)."...";
							$link=$row['external_link_url'];
							echo "<a href='$link'>".$link."</a><br/>";
							$row['Filenames']=getAttatchment($conn,$row['Id']);
							$attachment=getAttatchment($conn,$row['Id']);
							for($i=0;$i<sizeof($attachment);$i++){
								echo "<div class='col-sm-4'><a href='".$attachment[$i]['attachment_url']."' 
								target='_blank' download>".$attachment[$i]['file_name']."</a></div>";
							}
							$output[] = $row;
						}
						//echo json_encode(array("status"=>true,"response"=>$output));
					}
					else {
						//echo json_encode(array("status"=>false,"message"=>"Sorry, unable to get result."));
						echo "Sorry, unable to get result.";
					}
					
				}
				else{
					//echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
					echo "Sorry, unable to connect database.";
				}
			}
			function getAttatchment($conn,$article_id){
				$query = "select Id,caption,file_name from ArticleFiles where article_id='$article_id'";
				$res = $conn->query($query);
				$files_output=array();
				while($row = $res->fetch(PDO::FETCH_ASSOC)){
					$row['file_type']=getFileType($row['file_name']);
					$row['file_name']=substr($row['file_name'],strpos($row['file_name'],"/")+1);
					$row['attachment_url']="http://128.199.111.18/TabGenAdmin/".$row['file_name'];
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
			?>
		</div>
	</body>
</html>

