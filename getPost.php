<?php 
	$channel_id = $_GET['channel_id'];
	include('connect_db.php');
	$query="select Posts.Id as postId,Posts.CreateAt,Message,Username as messaged_by,Posts.UserId,Channels.LastPostAt,Posts.Filenames as filenames
		from Posts,Channels,Users 
		where Channels.Id=ChannelID and 
		ChannelID='$channel_id' and 
		Users.Id=Posts.UserId and Posts.DeleteAt=0
		order by CreateAt desc limit 60";

	if($conn){
		$res = $conn->query($query);					
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row_data = $row['filenames'];
				$output[]=$row_data;
				//echo "Filename: ".$row['filenames']."<br/>";
				
			}
			print($output[5]);
		}
	}

?>
