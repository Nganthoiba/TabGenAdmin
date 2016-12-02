$( document ).ready(function() {
  $("#test_conection").click(function(){
		var org = $("#choose_org_for_hisconnectivity").val();
		var add = $("#ipadd").val();
		var user = $("#datusr").val();
		var pass = $("#datpass").val();

		var dataString = 'or='+ org + '&ipadd='+ add + '&datusr='+ user + '&datpass='+ pass;
		alert(dataString);
		if(org==''||add==''||user==''||pass=='')
		{
			alert("Please Fill All Fields");
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