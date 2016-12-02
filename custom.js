$(document).ready(function() {
  $("#test_connection").click(function(){
		var org = $("#choose_org_for_hisconnectivity").val();
		var add = $("#ipadd").val();
		var user = $("#datusr").val();
		var pass = $("#datpass").val();
		//alert("Hello World!");
		
		var dataString = 'or='+ org + '&ipadd='+ add + '&datusr='+ user + '&datpass='+ pass;
		//alert(dataString);
		if(org==''||add==''||user==''||pass=='')
		{
			$("#his_connectivity_resp").html("<center>Please Fill All Fields</center>");
		}
		else
		{
			$.ajax({
				type: "POST",
				url: "check.php",
				data: dataString,
				success: function(result){
					$("#his_connectivity_resp").html("<b>"+result+"</b>");
				}
			});
		}
		return false;
	});
});