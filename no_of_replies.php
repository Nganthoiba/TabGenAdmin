
<?php
	include('connect_db.php');
	$post_id = $_GET['post_id'];
	$query = "select count(*) as no_of_replies from Posts where ParentId='$post_id'";
	$res=$conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	echo json_encode(array("no_of_replies"=>$row['no_of_replies']));
	
?>
