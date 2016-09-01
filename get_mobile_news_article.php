<!DOCTYPE html>
<?php
/*mobile responsive web view of a particular news article. Only for mobile app*/
$news_id = $_GET['news_id'];
if(!empty($news_id)){
		include('connect_db.php');
		include('tabgen_php_functions.php');
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
		<!--<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">-->
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
			$query="select * from News where Id='$news_id'";
			if($conn){
				$res=$conn->query($query);
				$row=$res->fetch(PDO::FETCH_ASSOC);
				echo "<div class='headline'><h1>".$row['headline']."</h1></div>";
				if($row['Image']!=null){
					echo "<center><img width='100%' height='80%' src='".$row['Image']."'/></center>";
				}
				echo $row['Details'];
				$link=$row['Link'];
				if($link!=null && $link!=""){			
					if(getYouTubeID($link)!=null){
						$video_id=getYouTubeID($link);
						echo "<div class='videoWrapper'><iframe allowfullscreen='true' 
						src='https://www.youtube.com/embed/".$video_id."?autoplay=0'></iframe></div>";
					}
					else{
						//echo curl($link);
						header('Location: '.$link);
					}
				}
			}
		?>
		</div>
	</body>
</html>
<?php
}
else{
	echo "<center><p><h1>Please send proper news id.</h1></p></center>";
}
?>

