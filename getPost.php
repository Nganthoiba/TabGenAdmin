<?php 
	//php code for getting post messages
	$channel_id = $_GET['channel_id'];
	include('connect_db.php');
	$query="select Posts.Id as postId,Posts.CreateAt,Message,Username as messaged_by,Posts.UserId,Channels.LastPostAt,Posts.Filenames as filenames
		from Posts,Channels,Users 
		where Channels.Id=ChannelID and 
		ChannelID='$channel_id' and 
		Users.Id=Posts.UserId and Posts.DeleteAt=0
		order by CreateAt desc limit 10";

	if($conn){
		$res = $conn->query($query);					
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;		
			}
			print json_encode($output);
		}
	}

?>
