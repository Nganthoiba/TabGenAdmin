<?php
include('connect_db.php');//connecting database here
include('tabgen_php_functions.php');


$or = mysqli_real_escape_string($_POST['or']);  
$ipadd = mysqli_real_escape_string($_POST['ipadd']);
$datusr = mysqli_real_escape_string($_POST['datusr']);
$datpass = mysqli_real_escape_string($_POST['datpass']);

if($conn){
	$query = "select count(*) as count from HISConnectivity 
	where OrganisationName='$or' 
	and DataServerIPAdd = '$ipadd' 
	and DatabaseUsername = '$datusr'";
	$result = $conn->query($query);
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$count = (int)$row['count'];
		if($count>0){
			echo "Tested OK";
		}
		else{
			echo "No data found with the given details.";
		}
	}
	else{
		echo "Oops! An error with the query.";
	}
}
else{
	echo "Failed to connect database";
}
/*
$result = mysqli_query('select OrganisationName,DataServerIPAdd,DatabaseUsername,DatabasePassword from HISConnectivity where values = "'. $or .','. $ipadd .','. $datusr .','. $datpass .'"');  
  

if(mysqli_num_rows($result)>0){  
    
    echo "tested ok";  
}else{  
   
  
    echo "error";  
} 
*/
 
?>