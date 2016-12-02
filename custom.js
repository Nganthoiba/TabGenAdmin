$( document ).ready(function() {
  $("#test_conection").click(function(){
		var org = $("#choose_org_tabstrip").val();
		var add = $("#ipadd").val();
		var user = $("#datusr").val();
		var pass = $("#datpass").val();

		var dataString = 'or='+ org + '&ipadd='+ add + '&datusr='+ user + '&datpass='+ pass;
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