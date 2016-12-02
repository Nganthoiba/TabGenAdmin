$( document ).ready(function() {
  $("#test_conection").click(function(){
		var org = $("#choose_org_tabstrip").val();
		var add = $("#ipadd").val();
		var user = $("#datusr").val();
		var pass = $("#datpass").val();

		var dataString = 'org1='+ org + '&add1='+ add + '&user1='+ user + '&pass1='+ pass;
		//alert(dataString);
		if(choose_org_tabstrip==''||ipadd==''||datusr==''||datpass=='')
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
					
				}
			});
		}
		return false;
	});
});
