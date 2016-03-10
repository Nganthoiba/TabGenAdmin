<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
	<style type="text/css">
		.listShow {list-style-type:none}
		ul.listShow li:hover {background:#F0F0F0;cursor:pointer;color:#202020;font-size:16px}
		.circular {
					width: 100px;
					height: 100px;
					border-radius: 150px;
					-webkit-border-radius: 150px;
					-moz-border-radius: 150px;
					box-shadow: 0 0 8px rgba(0, 0, 0, .8);
					-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
					-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
		}
	</style>
	
</head>
<body>
	<?php
        session_start();
        if(!isset($_SESSION['user_details'])){
                echo "<p align='center'>You have to <a href='index.html'>login</a> first<br/>";
        }
        else {
                $user_data = json_decode($_SESSION['user_details']);
                $user_name = $user_data->username;
                $user_role = $user_data->roles;
				include ('ConnectAPI.php');
					
	?>
	<div class="container-fluid"><br><br>
		<div class="row">
			<div class="col-md-2">
				<div class="col-md-12" style="padding-bottom:10px;">
					<div class="col-md-2"></div>
					<div class="col-md-2"><img src="img\user_profile.png" class="circular" alt="No profile Image found"/>
						<p id="userID"><?php echo $user_name; ?></p>
						<p><?php echo $user_role ?></p>
					</div>
					<div class="col-md-2"></div>

				</div>
				
				<ul class="nav nav-tabs nav-stacked" bgcolor="green">
					<a href="#" data-toggle="modal" data-target="#createorg"><li >Create Organization</li></a>
					<a href="#" data-toggle="modal" data-target="#createorgunit"><li>Create Organization Unit</li></a>
					<a href="#" data-toggle="modal" data-target="#createrole">	<li>Create Roles</li></a>
					<a href="#" data-toggle="modal" data-target="#createuser">	<li>Create Users</li></a>
					<li>Create Tabs Strips</li>
					<a href="#" data-toggle="modal" data-target="#createTemplateDialog"><li>Create Tabs template</li></a>
					<a href="#" data-toggle="modal" data-target="#assocRole2Tab"><li>Associate Role to Tab</li></a>
					<a href="#" data-toggle="modal" data-target="#assocTab2Template"><li>Associate Tab to Template</li></a>
					<li>Edit Profiles</li>
					<a href="#" data-toggle="modal" data-target="#logoutConfirmation"><li>logout</li></a>

				</ul>

			</div>
			<div class="col-md-8">
				<div class="col-md-12" style="padding-top:55px">
					<table width="100%">
						<tr>
							<td><h4>ORGANIZATION UNITS</h4></td>
							<td align="right">
								<form class="form-horizontal" method="post">
										<input type="text" name="org" id="org" style="width:60%;padding-left:5px; padding-right:5px"
										placeholder="Search an organisation unit by name">
										<button type="submit" class="btn btn-default" id="search_org_unit">Search </button>
										<center><label id="find_org_unit_msg"></label></center>
								</form>
							</td>
						</tr>
					</table>
					<hr/><br/>	
					<script type="text/JavaScript"> </script>
					<ul  class='listShow' id="showOrgUnits">
						<script>
							$(document).ready(function(){
								viewOrgUnits("list","showOrgUnits","few");
							});
						</script>
					</ul>
					<input type="button" id="viewAllOrgUnitLists" style="float:right" value="VIEW ALL"/>
		</div>
		<div class="col-md-12">
		<br/>
			<table width="100%">
				<tr>
					<td><h4>ORGANIZATION</h4></td>
					<td align="right">
						<form class="form-horizontal" method="post">
							<input type="text" name="org_name" id="org_name" style="width:60%;padding-left:5px; padding-right:5px"
								placeholder="Search an organisation by name">
							<button type="submit" class="btn btn-default" id="search_org">Search</button><br/>
							<label id="find_org_msg"></label>
						</form>
					</td>
				</tr>
			</table>
			<hr/><br/>
			<ul class='listShow' id="showOrgsList">
				<script>
					$(document).ready(function(){
						viewOrgs("list","showOrgsList","few");
					});
				</script>
			</ul>
			<input type="button" id="viewAllOrgLists" style="float:right" value="VIEW ALL"/>
</div>
</div>
</div>
</div>

<!--- popup start for each one -->
<!-- Modal for create Organization -->
<div class="modal fade" id="createorg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Organization</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" action="createorg.php">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-4  control-label">Organization Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" value="" name="orgname" id="orgname" placeholder="Organization name">
						</div>
					</div>
					<div class="form-group">
                        <label for="inputPassword3" class="col-sm-4  control-label">Display Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" name="display_name" id="display_name">
                        </div>
                    </div>
					
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" class="btn btn-default" style="width:70%" id="submit">Create </button>
						</div>
						<div class="col-sm-4"></div>
					</div>
					<center><label id="error1" align="center"></label></center>
				</form>
			</div>			</div>
	</div>
</div>
<!-- Modal for create Organization Unit-->
<div class="modal fade" id="createorgunit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Organization Unit</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-4  control-label">Organization Unit Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" value="" id="orgunit" name="orgunit" placeholder="Organization name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-4  control-label">Display Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" value="" id="displaynameunit"  placeholder="Display Name" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4  control-label">Organization</label>
						<div class="col-sm-8">
							<select id="orgnamesel" class="form-control">
								<script type="text/JavaScript">
									$(document).ready(function(){
										viewOrgs("dropdown","orgnamesel","all");
									});
								</script>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" class="btn btn-default" style="width:70%" id="createorgunitbtn">Create </button>
						</div>
						<div class="col-sm-4"></div>
					</div>
				</form>



			</div>
			<center><label id="error2" align="center"></label></center>
			
		</div>
	</div>
</div>

<!-- Modal for create role -->
<div class="modal fade" id="createrole" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Role</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-4  control-label">Role Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="rolaname" id="rolaname" placeholder="Role name">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4  control-label">Organization Unit</label>
						<div class="col-sm-8">
							<select class="form-control" id="ousel">
								<script type="text/JavaScript">
									$(document).ready(function(){
										viewOrgUnits("dropdown","ousel","all");
									});
								</script>
							</select>
						</div>
					</div> 
					<!--<div class="form-group">
						<label for="inputEmail3" class="col-sm-4  control-label">Select a Template</label>
						<div class="col-sm-8">
							<select class="form-control" id="SelectTemplate">
							<script type="text/JavaScript">
								$(document).ready(function(){
									viewTemplates("dropdown","SelectTemplate","all"); //This will display list of templates
								});
							</script>
							</select>
						</div>
					</div>-->
					<div class="form-group">
						<label class="col-sm-4  control-label">Can access other OU</label>
						<div class="col-sm-8">
							<label class="radio-inline"><input type="radio" name="optradio" id="access" value="Yes">Yes</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="access" value="No">No</label>
						</div>
					</div>
	
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" class="btn btn-default" style="width:70%" id="btnrole">Create </button>
						</div>
						<div class="col-sm-4"></div>
					</div>
				</form>
			<center><label id="error3"></label></center>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for create user -->
<div class="modal fade" id="createuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create User</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="username" class="col-sm-4  control-label">User Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control"  placeholder="Username" id="username">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-4  control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control"  placeholder="Password" id="password">
						</div>
					</div>
					<div class="form-group">
						<label for="conf_pwd" class="col-sm-4  control-label">Conform Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control"  placeholder="Conform Password" id="conf_pwd">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-4  control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" class="form-control"  placeholder="Email" id="email">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4  control-label">Organization Unit</label>
						<div class="col-sm-8">
							<select class="form-control" id="OrgUnitList">
								<script type="text/JavaScript">
									$(document).ready(function(){
										viewOrgUnits("dropdown","OrgUnitList","all");
									});
								</script>
							</select>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-sm-4  control-label">Role</label>
						<div class="col-sm-8">
							<select class="form-control " id="UserRole">
								<option> </option>
								<option>Doctor</option>
								<option>Nurse</option>
								<option>Radiologist</option>
								<option>Administrator</option>
								<option>admin</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4  control-label">Can Allow Offer</label>
						<div class="col-sm-8">
							<label class="radio-inline"><input type="radio" name="optradio" id="type" value="Yes">Yes</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="type" value="No">No</label>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" class="btn btn-default" style="width:70%" id="CreateUser">Create </button>
						</div>
						<div class="col-sm-4"></div>
					</div>
					<center><label id="error4"></label></center>
				</form>
			</div>
			
		</div>
	</div>
</div>
<!-- Modal for creating Tab Template -->
<div class="modal fade" id="createTemplateDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Tabs Template</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="templateName" class="col-sm-4  control-label">Template Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control"  placeholder="Name of template" id="templateName">
						</div>
					</div>
					<div class="form-group">
						<label for="template" class="col-sm-4  control-label">Template</label>
						<div class="col-sm-8">
							<input type="text" class="form-control"  placeholder="HTML Content" id="template">
						</div>
					</div>
					<center><Button type="submit" class="btn btn-default" style="width:20%" id="createTemplate">Create</Button></center>
					<div class="form-group">
						<center><br/><label id="createTemplateResponse"></label></center>
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for Associating Role to Tab -->
<div class="modal fade" id="assocRole2Tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Associate Role to Tab</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<table border="0" align="center">
							<tr>
								<th width="250px"><label>Roles</label>
									<select class="form-control" id="sel_roles" width="80%">
										<option>Nurse</option>
										<option>Doctor</option>
										<option>Radiologist</option>
										<option>Administrator</option>
									</select>	
								</th>
								<th>&nbsp;&nbsp;</th>
								<th width="250px"><label>Number of Tabs</label>
									<select class="form-control" id="no_of_tabs" >
										<option>1</option><option>2</option><option>3</option><option>4</option>
										<script type="text/JavaScript">
											/*
											$(document).ready(function(){
												for(var i=1;i<=5;i++){
													document.write("<option>"+i+"</option>");
												}
											});*/
										</script>
									</select>
								</th>
							</tr>
							<tr>
								<td></td><td></td>
								<td align="right"><br/><br/>
								<Button class="btn btn-default" style="width:40%" id="saveAsscRole2Tab">Save</Button>
								</td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<center><br/><label id="saveAsscRole2TabResponse"></label></center>
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>

<script type="text/JavaScript">
/*Javascript code to associate role to a to a tab*/
$(document).ready(function(){	

	$("#saveAsscRole2Tab").click(function(){
		$("#saveAsscRole2TabResponse").text("Wait Please...");
		var role = $("#sel_roles").val();
		var tabs = parseInt($("#no_of_tabs").val());
		$.ajax({
				type:"POST",
				url:"createTabs.php",
				data:"role_name="+role+"&no_of_tabs="+tabs,
				success:function(s){
					$("#saveAsscRole2TabResponse").text(s);		
				}
			});
		setAssocRole2TabLayout();
		return false;
	});	
});


function setAssocRole2TabLayout(){
	var tab_templates;
	var role = $("#sel_roles").val();
	var tabs = parseInt($("#no_of_tabs").val());
	var layout = "<table class='table table-hover' align='center'><tr><th>Role</th><th>Tabs</th><th>Tab Templates</th></tr>";
	var i=1;
	while(i<=tabs){
		tab_templates = "<select class='form-control' id='selTemplate"+i+"'></select>";			
		layout+="<tr><td>"+role+"</td><td>Tab"+i+"</td><td>"+tab_templates+"</td></tr>";
		i++;
	}	
	layout+= "</table>";
	document.getElementById("mapRoleTabsTemplate").innerHTML=layout;
	for(i=1;i<=tabs;i++){
		viewTemplates("dropdown","selTemplate"+i,"all");
	}
	$("#saveAsscTab2TemplateResponse").text(" ");		
}
/*$(document).ready(function(){
	$("#saveAssocRole2TabLayout").click(function(){
		$("#saveAsscTab2TemplateResponse").text("Wait, saving your data.");	
		var no_of_tabs = parseInt($("#no_of_tabs").val());
		//window.alert("Wait, updating your "+no_of_tabs+" tab(s)");
		var i=1;
		var result="";
		var role_name = $("#sel_roles").val();
		while(i<=no_of_tabs){
			
			//var tab_name = "Tab"+i;	
			var template_name = $("#selTemplate"+i).val();
			alert(template_name);
			$.ajax({
				type:"POST",
				url:"updateTabs.php",
				data:"role_name="+role_name+"&tab_name="+tab_name+"&template_name="+template_name,
				success: function(s){
					//$("#saveAsscTab2TemplateResponse").text(s);
					//result=result+"Tab"+i+s+"<br/>";	
					window.alert(s);
				}
			});
			i++;
		}
		//window.alert(result);
		//$("#saveAsscTab2TemplateResponse").text(result);
		//document.getElementById("saveAsscTab2TemplateResponse").innerHTML=result;
		return false;
	});
});*/
	
$(document).ready(function(){
	$("#saveAssocRole2TabLayout").click(function(){
		$("#saveAsscTab2TemplateResponse").text("Saving template(s)..");
		var role_name = $("#sel_roles").val();
		var no_of_tabs = parseInt($("#no_of_tabs").val());
		for(var x=1;x<=no_of_tabs;x++){
			var tab_name = "Tab"+x;
			var template_name = $("#selTemplate"+x).val();
			$.ajax({
				type:"POST",
				url:"updateTabs.php",
				data:"role_name="+role_name+"&tab_name="+tab_name+"&template_name="+template_name,
				success: function(s){
					$("#saveAsscTab2TemplateResponse").text(s);
				}
			});	
		}
		return false;
	});
});
	
</script>
<!-- Modal for Associating Tab to TabTemplates -->
<div class="modal fade" id="assocTab2Template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Associate Tab to Tab Templates</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<script type="text/JavaScript">
						$(document).ready(function(){
							setAssocRole2TabLayout();
						});
					</script>
					<div class="form_group" id="mapRoleTabsTemplate">
					</div>
					<center><Button type='submit' width='40%' id='saveAssocRole2TabLayout' class='btn btn-default'>Save</Button></center>
					<div class='form-group'>
					<center><label id='saveAsscTab2TemplateResponse'>  </label></center></div>
				</form>
			</div>	
		</div>
	</div>
</div>
<!-- Modal for logout -->
<div class="modal fade" id="logoutConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Logout Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger">
				  <center><strong>Logout! &nbsp;</strong> Are you sure?</center>
				</div>
				<center><a href="logout.php" class="btn btn-default" style="width:20%" id="YesLogout">Yes</a></center>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">
/* script for creating organisation*/
            $(document).ready(function (){
			
                $('#submit').click(function() {
 
                    var orgname=$("#orgname").val();
					var display_name =$("#display_name").val();
					$("#error1").css('color', 'black');
                    $("#error1").text("Wait a moment please...");
                    $.ajax({
                   
                        type: "POST",
                        url: "createorg.php",
                        data: "orgname="+orgname+"&display_name="+display_name,
                    
                        success: function(s){  
							//alert(s);						  
                            if(s.trim()=="true")
                            {
								$("#error1").css('color', 'green');
								$("#error1").text("Organization Created ");
								viewOrgs("dropdown","orgnamesel","all");/*this will display drop down list of 
								organisation at the popup dialog for creating Organisation Units*/
								viewOrgs("list","showOrgsList","few");
                                
                            }else if(s.trim()=="false"){
								$("#error1").css('color', 'red');
								$("#error1").text("Oops Some Goes Wrong Please Try Agian");
							} 
							else{
								$("#error1").css('color', 'red');
								$("#error1").text(s);
							}
                        }
                    });				
                    return false;
                });
        
            });
</script>

<script type="text/javascript">
/*JavaScript for creating organisation unit*/
        $(document).ready(function (){
                $('#createorgunitbtn').click(function() {
				//alert('hh');
 
                    var orgunit=$("#orgunit").val();
		    var displaynameunit =$("#displaynameunit").val();
		    var orgnamesel =$("#orgnamesel").val();
    
		    if(orgunit.length<4){
			$("#error2").css('color', 'red');
			$("#error2").text("Name of organisation unit should be at least 4 characters length");
		    }
		   else{

                    $.ajax({
                   
                        type: "POST",
                        url: "createorgunit.php",
                        data: "orgnamesel="+orgnamesel+"&displaynameunit="+displaynameunit+"&orgunit="+orgunit,
                    
                        success: function(e){  
						//alert(e);
                           
                            if(e.trim()=="true")
                            {
								$("#error2").css('color', 'green');
								$("#error2").text("Organization Unit Created ");
								viewOrgUnits("list","showOrgUnits","few");
								viewOrgUnits("dropdown","ousel","all");/*this will display drop down list of 
								organisation units at the popup dialog for creating role*/
								viewOrgUnits("dropdown","OrgUnitList","all");/*this will display drop down list of 
								organisation units at the popup dialog for creating users*/     
                            }else if(e.trim()=="false"){
								$("#error2").css('color', 'red');
								$("#error2").text("Oops Some Goes Wrong Please Try Agian");
							}
							else{
								$("#error2").css('color', 'red');
								$("#error2").text(e);
							}
                        }
                    });
		}
					
                    return false;
                });
			$('#createorgunit').on('hidden.bs.modal', function () {
				$("#error2").text("");
			});
        });
</script>

<script type="text/javascript">
/* Javascript for creating roles*/
    $(document).ready(function (){
		$('#createrole').on('hidden.bs.modal', function () {
			$("#error3").text("");
		});
        $('#btnrole').click(function() {
			var rolaname=$("#rolaname").val();
			var ousel =$("#ousel").val();
			var access =$("#access").val();
    
			$.ajax({
                type: "POST",
                url: "createrole.php",
                data: "rolaname="+rolaname+"&ousel="+ousel+"&access="+access,    
                success: function(e){  
					if(e.trim()=="true")
                    {
						$("#error3").css('color','green');
						$("#error3").text("Role Created ");
						viewOrgUnits("dropdown","OrgUnitList","all");/*this will display drop down list of 
								organisation units at the popup dialog for creating users*/
                                
                    }else if(e.trim()=="false"){
						$("#error3").css('color','red');
						$("#error3").text("Oops Some Goes Wrong Please Try Agian");
					}
					else{
						$("#error3").css('color','red');
						$("#error3").text(e);
					}
                }
            });
            return false;
        });
	});
</script>
<script type="text/javascript">
/*JavaScript for creating users*/
	$(document).ready(function(){
		$('#CreateUser').click(function(){
			var username = $("#username").val();
			var password = $("#password").val();
			var conf_pwd = $("#conf_pwd").val();
			var email = $("#email").val();
			var org_unit = $("#OrgUnitList").val();
			var user_role = $("#UserRole").val();
			var type = $("#type").val();
			
			if(username.length==0){
				document.getElementById("error4").innerHTML="Username is blank";
				document.getElementById("error4").style.color="green";
				return false;
			}
			else if(password.length==0){
				document.getElementById("error4").innerHTML="Password is blank";
				document.getElementById("error4").style.color="green";
				return false;
			}
			else if(conf_pwd.length==0){
				document.getElementById("error4").innerHTML="Confirm Password is blank";
				document.getElementById("error4").style.color="green";
				return false;
			}
			else if(conf_pwd!=password){
				document.getElementById("error4").innerHTML="Confirm Password does not match with the password";
				document.getElementById("error4").style.color="green";
				return false;
			}
			else if(email.length==0){
				document.getElementById("error4").innerHTML="Email is blank";
				document.getElementById("error4").style.color="green";
				return false;
			}
			else{
				document.getElementById("error4").innerHTML="Wait Please....";
				document.getElementById("error4").style.color="green";
				$.ajax({
					type: "POST",
					url: "createUsers.php",
					data:"username="+username+"&password="+password+"&conf_pwd="+conf_pwd+"&email="+email+"&org_unit="+org_unit+"&Role="+user_role+"&type="+type,    
					success: function(e){  
						if(e.trim()=="true")
						{
							$("#error4").css('color','green');
							$("#error4").text("User Created ");
									
						}else if(e.trim()=="false"){
							$("#error4").css('color','red');
							$("#error4").text("Oops Some Goes Wrong Please Try Agian");
						}
						else{
							$("#error4").css('color','red');
							$("#error4").text(e);
						}
					}
				});
			}
			return false;
		});
	
	});

</script>	
<!-- JavaScript for finding organisation -->		
<script type="text/JavaScript">
	$(document).ready(function(){
		$("#search_org").click(function(){
			var org_name = $('#org_name').val();
			$("#find_org_msg").text("Wait please...");
			$.ajax({
				type:"POST",
				url: "find_org.php",
				data: "name="+org_name,
				success: function(e){
						$("#find_org_msg").text(e);
				}
			});
			return false;
		});
	});
</script>
<!-- JavaScript for viewing list of organisation unit  -->	
	
<script type="text/JavaScript">
	/* Here in this function, 
	"method" --> specifies what type of displaying method i.e.whether the data will be displayed in dropdown manner or simply in html list.
				 This parameter must have only two possible values "list" & "dropdown"
	"id"	 --> specifies the id of the html tag where the retrieved data will be displayed.
	"type"	 --> specifies whether to view only few data or all data, it has only two possible values: 
					"few" --> to display only few list of data &
					"all" --> to display all list of data*/
	function viewOrgUnits(method,id,type){		
			$.ajax({
				type:"GET",
				url: "view_org_unit_list.php",
				data: "method="+method+"&viewType="+type,
				success: function(e){
					document.getElementById(id).innerHTML=e;
				}
			});
			return false;
	}
	$(document).ready(function(){
		$("#viewAllOrgUnitLists").click(function(){
			document.getElementById("showOrgUnits").innerHTML="Wait Please....";
			viewOrgUnits("list","showOrgUnits","all");
		});
	});
</script>
	
<!-- JavaScript for viewing list of organisation -->		
<script type="text/JavaScript">
	/* Here in this function, 
	"method" --> specifies what type of displaying method i.e.whether the data will be displayed in dropdown manner or simply in html list.
				 This parameter must have only two possible values "list" & "dropdown"
	"id"	 --> specifies the id of the html tag where the retrieved data will be displayed.
	"type"	 --> specifies whether to view only few data or all data, it has only two possible values: 
					"few" --> to display only few list of data &
					"all" --> to display all list of data*/
	function viewOrgs(method,id,type){
			$.ajax({
				type:"GET",
				url: "view_org_list.php",
				data: "method="+method+"&viewType="+type,
				success: function(e){
					document.getElementById(id).innerHTML=e;	
				}
			});
			return false;
	}
	$(document).ready(function(){
		$("#viewAllOrgLists").click(function(){
			document.getElementById("showOrgsList").innerHTML="Wait Please....";
			viewOrgs("list","showOrgsList","all");
		});
	});
</script>

<!-- JavaScript for creating Template -->
<script type = "text/JavaScript">
	$(document).ready(function(){
		$("#createTemplate").click(function(){
			$("#createTemplateResponse").text(" ");
			var template_name = $("#templateName").val();
			var	template = $("#template").val();
			$.ajax({
				type:"POST",
				url:"createTemplate.php",
				data: "template_name="+template_name+"&template="+template,
				success: function(resp){
					$("#createTemplateResponse").text(resp);
					//viewTemplates("dropdown","SelectTemplate","all");
					//window.alert(resp);
					setAssocRole2TabLayout();
				}
			});
			return false;
		});
	});
	
	/* Javascript function for viewing list of templates, Here in this function, 
	"method" --> specifies what type of displaying method i.e.whether the data will be displayed in dropdown manner or simply in html list.
				 This parameter must have only two possible values "list" & "dropdown"
	"id"	 --> specifies the id of the html tag where the retrieved data will be displayed.
	"type"	 --> specifies whether to view only few data or all data, it has only two possible values: 
					"few" --> to display only few list of data &
					"all" --> to display all list of data*/
	function viewTemplates(method,id,type){
			$.ajax({
				type:"GET",
				url: "TemplateList.php",
				data: "method="+method+"&viewType="+type,
				success: function(e){
					document.getElementById(id).innerHTML=e;	
				}
			});
			return false;
	}
</script>		
<?php } ?>
</body>
</html>
