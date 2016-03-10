<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div>
			<h4 class="modal-title" id="myModalLabel" align="center">Update Tab and Templates</h4>
			<form class="form-horizontal">
				<center><table class="table table-hover">
				<tr><td>Organisation Unit: <select class="form-control" id="orgUnitSelect"><option>neworgunit</option><option></option></select></td>
					<td>Role: <select class="form-control" id="roleSelect">
											<option>All</option>
											<option>Nurse</option>
											<option>Doctor</option>
											<option>Radiologist</option>
											<option>Administrator</option>
							   </select>
					</td>
					<td>
						<br/><Button type="submit" class="btn btn-default" id="getTabsTemplate">Get Tabs and Templates</Button>
					</td>
				</tr>
				</table></center>
			</form>
		</div>
		<hr/>
		<script type="text/Javascript">
			/*JavaScript function for getting Tabs and corresponding Templates assigned for respectives roles of a particular organisation*/
			$(document).ready(function (){
				$('#getTabsTemplate').click(function() {
					document.getElementById("tabs_template_result").innerHTML="<center><p>Wait please...</p></center>";
					var orgunit = $("#orgUnitSelect").val();
					var user_role = $("#roleSelect").val();
					//document.getElementById("tabs_template_result").innerHTML="<center><p>You have selected "+orgunit+" and "+user_role+"</p></center>";
					$.ajax({
						type: "GET",
						url: "getTabsTemplateAssociation.php",
						data: "orgunit="+orgunit+"&role="+user_role,
						success: function(data){
							document.getElementById("tabs_template_result").innerHTML="<center><p>"+data+"</p></center>";
						}
						
					});
					return false;
				});
			});
		</script>
		<div id="tabs_template_result"></div>
	</body>
</html>