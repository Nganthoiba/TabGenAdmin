<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">-->
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/npm.js"></script>
	<script src="homepage.js"></script>
	<script type="text/JavaScript">
		/*
		 * global variables
		 * */
		 var arr; /*array for tab template association*/
		 var prev_tab_name = [];/*Global array for tab name*/
		 var templates_arr=""; /*list of templates*/
		 var tabs=[];
		 var json_arr;
		 /*
		 $(document).ready(function(){
			function alignModal(){
				var modalDialog = $(this).find(".modal-dialog");
				
				// Applying the top margin on modal dialog to align it vertically center
				modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
			}
			// Align modal when it is displayed
			$(".modal").on("shown.bs.modal", alignModal);
			
			// Align modal when user resize the window
			$(window).on("resize", function(){
				$(".modal:visible").each(alignModal);
			});   
		});*/
	</script>
	<!--ul.listShow li:hover {background-color:#F0F0F0;cursor:pointer;color:#202020}-->
	<style type="text/css">		
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
		.my_background {
			background-color:#90C6F3; color:#292928;padding-top:5px;width:100%;
				padding-bottom:5px;padding-left:10px;padding-right:10px;border-radius:3px
		}
		.table_borderless {
			width:100%;
			border-top-style: none;
			border-left-style: none;
			border-right-style: none;
			border-bottom-style: none;
			cellspacing:10px
		}
		h4 {font-family:calibri}
		table th {background-color:#F2F2F2}
		td {vertical-align:middle;font-size:13px}
		a {color:#F8FAFC}
	</style>
	
</head>
<body>
	<?php

        session_start();
	
        if(!isset($_SESSION['user_details'])){
                //echo "<p align='center'>You have to <a href='index.html'>login</a> first<br/>";
                header('Location: index.html');
        }
        else {
                $user_data = json_decode($_SESSION['user_details']);
                $user_name = $user_data->username;
                $user_role = $user_data->roles;
                $user_email= $user_data->email;
						
	?>
	<nav class="navbar navbar-default navbar-fixed-top" 
		style="height:60px;padding-top:10px;padding-bottom:10px;
		background-color:#819FF7;color:#f7f7f7;position:fixed;width:100%" >
		<!--class="navbar navbar-default navbar-fixed-top"
		style="height:60px;padding-top:10px;padding-bottom:10px;background-color:#819FF7;color:#f7f7f7;position:fixed-top"-->
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <!--<span class="sr-only">Toggle navigation</span>
            <span class="icon-bar">ABC</span>
            <span class="icon-bar">XYZ</span>
            <span class="icon-bar"></span>-->
          </button>
          <a href="#" class="navbar-brand">H Circle</a>
          <!--class="navbar-brand"-->
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Settings</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Help</a></li>
          </ul>  
          
        </div>
      </div>
    </nav>
	<div class="container-fluid" ><br/><br/><br/>
		<div class="row"><!--class="row"-->
			<div class="col-sm-3 col-md-2 sidebar">
			<!--<div class="col-md-2"style="background-color:#F2F2F2;border-radius:5px">-->
				<div class="nav nav-header" 
					style="background-color:#DEDFE1;padding-top:10px;padding-bottom:10px;height:100%;
					width:100%">
					<center>
						<div class="col-md-4">
							<img src="img/user.png" class="circular" alt="No profile Image found"/>			
						</div>
					</center>	
				</div>
				<div style="background-color:#2D79D7; color:#f7f7f7;width:100%; padding-left:5px;padding-right:5px;padding-top:10px" id="userID" >
					<?php echo $user_name; ?>
				</div>
				<!--nav nav-sidebar class="nav nav-tabs nav-stacked"-->
				<ul class="nav nav-tabs nav-stacked" style="-moz-box-shadow: 0px 2px 2px rgba(0.3, 0, 0, 0.3);
															-webkit-box-shadow: 0px 2px 2px rgba(0.2, 0.3, 0, 0.3);
															background-color: #569FF8;padding-top:10px;
															width:100%;
														box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);">
					<li><a href="#" data-toggle="modal" data-target="#createorg">Create Organization</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createorgunit">Create Organization Unit</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createrole">	Create Roles</a></li>
					<li><a href="#" data-toggle="modal" data-target="#create_tab_modal"
						onclick='getRoles("role_selector",$("#ou_selector").val());'>Create Tabs</a></li>
					<!--<li><a href="#" data-toggle="modal" data-target="#assocRole2Tab"
						onclick='getRoles("sel_roles",$("#sel_org_unit_role_tab").val());return false;'>Create OU Specific Tabs</a>
					</li>-->
					<li>
						  <a href='#' type="button" data-toggle="dropdown">Users
						  <span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="#" data-toggle="modal" data-target="#createuser" 
								onclick='getRoles("UserRole",$("#OrgUnitList").val());return false;'>Create Users</a></li>
							<li role="presentation" class="divider"></li>
							<li><a href="#" data-toggle="modal" data-target="#displayUsers">Show Users</a></li>
						  </ul>
						
					</li>
					
					<li><a href="#">Create Tabs Strips</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createTemplateDialog">Create Tabs template</a></li>
					<li><a href="#" data-toggle="modal" data-target="#associate_tabs_to_role"
						onclick='getRoles("choose_role",$("#choose_ou").val());
								 getRoles("choose_role2",$("#choose_ou2").val())'>Associate Tabs to Role</a></li>
					<!--<li><a href="#" data-toggle="modal" data-target="#assocTab2Template"
						onclick='getRoles("roleSelect",$("#orgUnitSelect").val());return false;'>Update Tabs</a>
					</li>-->
					<li><a href="#">Edit Profiles</a></li>
					<li><a href="#" data-toggle="modal" data-target="#logoutConfirmation">logout</a></li>
				</ul>
			</div>
			<div class="col-md-10"><!--class="col-md-8"-->	
				<div class="box">
						<div class="heading">Organisation Units
							<!--
							style="max-height: 550px;min-height:300px;overflow: hidden;overflow-y: auto;
										-webkit-align-content: center; align-content: center;padding-top:0px"
							<form class="navbar-form navbar-right">
								<input type="text" class="form-control" placeholder="Search...">
								<button type="button" class="btn btn-default">
								  <span class="glyphicon glyphicon-search"></span>
								</button>
							</form>-->
						</div>	
					<table  class='table table-striped' cellspacing="10" 
						style="max-height:500px;min-height:450px; overflow:hidden; 
										overflow-x:auto;overflow-y:auto;"
						id="showOrgUnits" border="0" style="padding-top:20px">
						<script>
							$(document).ready(function(){
								document.getElementById("showOrgUnits").innerHTML="<center><img src='img/loading_data.gif'/></center>";
								viewOrgUnits("list","showOrgUnits","all");
							});					
						</script>	
					</table>			
					<!--<div class="pull-right">
						<Button type="button" id="viewAllOrgUnitLists" class="btn btn-link">VIEW ALL</Button>
					</div>-->	
				</div>
				<div class="box">	
					<div class="heading">
						Organisation
						<!--
						style="max-height: 550px;min-height:300px;overflow: hidden;overflow-y: auto;
										-webkit-align-content: center; align-content: center;padding-top:0px"
						<form class="navbar-form navbar-right">
							<input type="text" class="form-control" placeholder="Search...">
							<button type="button" class="btn btn-default">
								 <span class="glyphicon glyphicon-search"></span>
							</button>
						</form>-->
					</div>
					<table  class='table table-striped' cellspacing="10" 
						style="max-height:500px;min-height:450px; overflow:hidden; 
										overflow-x:auto;overflow-y:auto;"
						id="showOrgsList" border='0' style="padding-top:20px">
						<script>
							$(document).ready(function(){
								document.getElementById("showOrgsList").innerHTML="<center><img src='img/loading_data.gif'/></center>";
								viewOrgs("list","showOrgsList","all");
							});
						</script>	
					</table>
					<!--<div class="pull-right">
						<Button type="button" id="viewAllOrgLists" class="btn btn-link">VIEW ALL</Button>
					</div>	-->				
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Organization</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" action="createorg.php">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label for="orgname" class="col-sm-4  control-label">Organization Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" name="orgname" id="orgname" placeholder="Organization name">
								</div>
							</div>
							<!--<div class="form-group">
								<label for="display_name" class="col-sm-4  control-label">Display Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" placeholder="Display Name" name="display_name" id="display_name">
								</div>
							</div>-->
						</div>
						<div class="panel-footer clearfix">
							<label id="error1" class="col-sm-offset-2 col-sm-8"></label>
							<div class="pull-right"><button type="submit" class="btn btn-default" id="submit">Create </button></div>
						</div>
					</div>
				</form>
			</div>
		</div>
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
				<div class="panel panel-default">
					<div class="panel-body">
						<form class="form-horizontal" method="post">
							<div class="form-group">
								<label for="orgunit" class="col-sm-6  control-label">Organization Unit Name</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" value="" id="orgunit" name="orgunit" placeholder="Organization name" required>
								</div>
							</div>
							<!--<div class="form-group">
								<label for="displaynameunit" class="col-sm-4  control-label">Display Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" id="displaynameunit"  placeholder="Display Name" required>
								</div>
							</div>-->
							<div class="form-group">
								<label class="col-sm-6  control-label">Organization</label>
								<div class="col-sm-6">
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
								
								<div class="col-sm-4"></div>
							</div>
						</form>
					</div>
					<div class="panel-footer clearfix">
						<label id="error2" class="col-sm-offset-2 col-sm-8"></label>
						<div class="pull-right">
							<button type="submit" class="btn btn-default" id="createorgunitbtn">Create </button>
						</div>
					</div>
				</div>
			</div>
			
			
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
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-4  control-label" for="ousel">Organization Unit</label>
								<div class="col-sm-4">
									<select class="form-control" id="ousel" style="float:center">
										<script type="text/JavaScript">
											$(document).ready(function(){
												viewOrgUnits("dropdown","ousel","all");
											});
										</script>
									</select>
								</div>
								<div class="col-sm-4">
									<button type="submit" class="btn btn-default" id="disp_role" style="float:right">Show Existing Roles </button>
									<script type="text/JavaScript">
										$(document).ready(function(){
											$('#disp_role').click(function(){
												displayRoles("role_lists",$("#ousel").val());
												return false;
											});
											$('#ousel').change(function(){
												displayRoles("role_lists",$("#ousel").val());
												$('#rolaname').val(" ");
												//$("#rolaname").attr("placeholder", "Type Role Name").placeholder();
												$("#rolaname").attr("placeholder", "Type Role Name");
												$('#error3').html(" ");
												return false;
											});
										});
									</script>
								</div>
								
							</div>
						</div>
					</div>
					
					<div id="role_lists">
						
					</div>
					<h3 align="center"> 
					<a href="#" data-toggle="collapse" class='btn btn-link' data-target="#create_role_collapsible">
					<span class="glyphicon glyphicon-plus"></span> Click here to create a new role</a></h3>
					
					<div id="create_role_collapsible" class="collapse">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h1 class="panel-title">Role details:</h1>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label for="rolaname" class="col-sm-4  control-label">Role Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="rolaname" id="rolaname" placeholder="Role name">
									</div>
								</div>
							
								<div class="form-group">
									<label for="roletype" class="col-sm-4  control-label">Role Type</label>
									<div class="col-sm-8">
										<select class="form-control" name="role_type" id="roletype">
											<option>Doctor</option>
											<option>Nurse</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4  control-label">OU Specific</label>
									<div class="col-sm-4">
										<label class="radio-inline"><input type="radio" name="optradio" id="access_yes" checked>Yes</label>
										<label class="radio-inline"><input type="radio" name="optradio" id="access_no">No</label>
									</div>
								</div>
							</div>
							<div class="panel-footer clearfix">
								<div id="error3" class="col-sm-offset-2 col-sm-8"></div>
								<div class="pull-right">
									<button type="submit" class="btn btn-default" id="btnrole">Create </button>
								</div>
							</div>
						</div>
					</div>
				</form>
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
					<div class="panel panel-info">
						<div class="panel-body">
							<div class="form-group">
								<label for="displayname" class="col-sm-4  control-label">Display Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"  placeholder="User full name" id="user_displayname">
								</div>
							</div>
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
								<label for="OrgUnitList" class="col-sm-4  control-label">Organization Unit</label>
								<div class="col-sm-8">
									<select class="form-control" id="OrgUnitList">
										<script type="text/JavaScript">
											$(document).ready(function(){
												viewOrgUnits("dropdown","OrgUnitList","all");
												$("#OrgUnitList").change(function(){
													getRoles("UserRole",$("#OrgUnitList").val());
												});
											});
										</script>
									</select>
								</div>
							</div> 

							<div class="form-group">
								<label for="UserRole" class="col-sm-4  control-label">Role</label>
								<div class="col-sm-8">
									<select class="form-control " id="UserRole">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4  control-label">Has access across all other OU</label>
								<div class="col-sm-8">
									<label class="radio-inline"><input type="radio" name="optradio" id="universal_access_yes" checked>Yes</label>
									<label class="radio-inline"><input type="radio" name="optradio" id="universal_access_no">No</label>
								</div>
							</div>
						</div>
						<div class="panel-footer clearfix">
							<center><label id="error4" class="col-sm-offset-2 col-sm-8"></label></center>
							<div class="pull-right">
									<button type="submit" class="btn btn-default" id="CreateUser">Create </button>
							</div>
						</div>
					</div>
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
					<div class="panel panel-default">
						<div class="panel-body">
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
						</div>
						<div class="panel-footer clearfix">
							<label id="createTemplateResponse"></label>
							<div class="pull-right"><Button type="submit" class="btn btn-default" id="createTemplate">Create</Button></div>
						</div>
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for Creating Tab (a simple design)-->
<div class="modal fade" id="create_tab_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Create Tabs</h4>
			</div>
			<div class="modal-body">
				<div class="panel panel-default clearfix">
				<form class="form-horizontal">
					<div class="panel-body">							
						<div class="form-group">
							<label class="col-sm-4  control-label">Tab Name:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="tab_name"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4  control-label">Choose a Template:</label>
							<div class="col-sm-8">
								<select class="form-control" id="choose_templates" >
									<script type="text/JavaScript">
										$(document).ready(function(){
												setTemplateList("choose_templates");
										});
									</script>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4  control-label">Organisation Unit Specific:</label>
							<div class="col-sm-8">
								<label class="radio-inline"><input type="radio" name="optradio" id="ou_specific_yes" value="Yes"/>Yes</label>
								<label class="radio-inline"><input type="radio" name="optradio" id="ou_specific_no" value="No"/>No</label>
							</div>
						</div>
						<div class="form-group" id="ou_selector_region">
							<label class='col-sm-4  control-label' for='ou_selector'>Select an Organisation Unit:</label>
							<div class='col-sm-8'><select id='ou_selector' class='form-control'></select></div>
						</div>
						<div class="form-group" id="role_selector_region">
							<label class='col-sm-4 control-label'>Select a Role:</label>
							<div class='col-sm-8'><select id='role_selector' class='form-control'></select></div>
						</div>
					</div>	
					<div class="panel-footer clearfix">
						<div class="pull-right"><Button type="submit" class="btn btn-default" id="createTab">
							Create</Button></div>
					<span id="createTabResponse"></span></div>
					
				</form>
				</div>	
			</div>	
		</div>
	</div>
</div>
<script type="text/JavaScript">
	$(document).ready(function(){
		//If the tab to be created is OU specific
		viewOrgUnits("dropdown","ou_selector","all");
			
		$("#ou_selector").change(function(){
			getRoles("role_selector",$("#ou_selector").val());
		});
				
		//javascript for creating tab
		$('#createTab').click(function(){
			document.getElementById("createTabResponse").innerHTML="<center>Wait please....</center>";
			document.getElementById("createTabResponse").style.color="black";
			var tab_name = $("#tab_name").val();
			var template_name = $("#choose_templates").val();
			var ou_specific = document.getElementById("ou_specific_yes").checked;
			var post_data;//data to be posted
			
			//alert("org_unit="+org_unit+"&role_name="+role_name+"&tab_name="+tab_name+"&template_name="+template_name);
			if(tab_name.length==0){
				document.getElementById("createTabResponse").innerHTML="<center>Give a Tab name</center>";
				document.getElementById("createTabResponse").style.color="red";
				return false;
			}
			
			var ou_name = $("#ou_selector").val();
			var role_name = $("#role_selector").val();
			if(role_name==null || role_name.length==0){
				document.getElementById("createTabResponse").innerHTML="<center>Select a role.</center>";
				document.getElementById("createTabResponse").style.color="red";
				//alert("Select a role.");
				return false;
			}
			else post_data = {"tab_name":tab_name,"template_name":template_name,"ou_specific":ou_specific,
						"ou_name":ou_name,"role_name":role_name};
						
			if(document.getElementById("ou_specific_yes").checked==true || document.getElementById("ou_specific_no").checked==true){
				
				$.ajax({
						type: "POST",
						url: "createTab.php",
						data: post_data,
						success: function(resp){
							//alert("Response: "+resp);
							var resp_arr = JSON.parse(resp);
							if(resp_arr.status==true){
								getTabs("list_of_tabs");
								document.getElementById("createTabResponse").innerHTML="<center>"+resp_arr.message+"</center>";
								document.getElementById("createTabResponse").style.color="black";
							}
							else{
								document.getElementById("createTabResponse").innerHTML="<center>"+resp_arr.message+"</center>";
								document.getElementById("createTabResponse").style.color="red";
							}
						}
				});
			}else{
				document.getElementById("createTabResponse").innerHTML="<center>You have to select whether the tab should be OU specific or not.</center>";
				document.getElementById("createTabResponse").style.color="red";
			}
			return false;
		});
	});
</script>


<!--popup dialog header for editing role -->
<div class='hide' id="edit_role_header">
	Edit role here:
	<div class="pull-right">
		<button type="button" class="close" id="close_edit_role">
			<span aria-hidden="true">&times;</span>
			<script type="text/JavaScript">
				$(document).ready({
					//$("#close_edit_role").popover("hide");
					$("#close_edit_role").click(function(){
							alert("Hi");
					});
				});
			</script>
		</button>
	</div>
</div>
<!-- Modal for Associating Tabs to role (a simple design)-->
<div class="modal fade" id="associate_tabs_to_role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="width:90%; min-height:50%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="myModalLabel">Associate Tabs to Roles</h3>
			</div>
			<div class="modal-body">
				<div class="panel panel-default">
					<form class="form-horizontal">
						<div class="panel-body">	
							<div class="form-group">
								<div class="col-sm-6">
									<table width="100%">
										<tr>
											<td>
												<label class="control-label" for="choose_ou">Organisation Unit:</label>
											</td>
											<td>
												<select class="form-control" id="choose_ou" >
													<script type="text/JavaScript">
														$(document).ready(function(){
														viewOrgUnits("dropdown","choose_ou","all");
														//getAssociatedTabs("associated_tabs");
															$("#choose_ou").change(function(){
																	getRoles("choose_role",$("#choose_ou").val());
															});
														});
													</script>
												</select>
											</td>
										</tr>
										<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
										<tr>
											<td>
												<label class="control-label" for="choose_role">Select Role:</label>
											</td>
											<td>
												<select class="form-control" id="choose_role" >
													<script type="text/JavaScript">
														$(document).ready(function(){
															//getRoles("choose_role",$("#choose_ou").val());
															$("#choose_role").change(function(){
																getAssociatedTabs("associated_tabs");
															});
														});
													</script>
												</select>
											</td>
										</tr>
									</table>
									<br/>
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<table width="100%">
												<tr>
													<td><h1 class="panel-title">Associated Tabs</h1></td>
													<td align="right">
														<Button class="btn btn-default" id="refresh_ass_tab">
															<span class="glyphicon glyphicon-refresh"></span>
														</Button>
													</td>
												</tr>
											</table>			
										</div>
										
										<div style="max-height: 350px;min-height:350px;overflow: hidden;overflow-y: auto;
										-webkit-align-content: center; align-content: center;">
											<table class="table table-striped" id="associated_tabs">
											
											</table>
										</div>
										<script type="text/JavaScript">
											$(document).ready(function(){
												//getAssociatedTabs("associated_tabs");
												$("#associated_tabs").html("<h1 align='center'>Click Refresh button "+
												"<br/><Button class='btn btn-primary btn-lg btn-round' type='button' "+
												"style='height:50px;width:50px;border-radius:50%'"+
												"onclick='getAssociatedTabs(\""+"associated_tabs"+"\");'>"+
												"<span class='glyphicon glyphicon-refresh'></span></Button><br/>"+
												" to display all the associated tabs</h1>");
												$("#associated_tabs").css('color','#A4A4A4');
												$("#refresh_ass_tab").click(function(){
													getAssociatedTabs("associated_tabs");
													return false;
												});
											});
										</script>
									</div>
								</div>
								<div class="col-sm-6">
									<table width="100%">
										<tr>
											<td>
												<label class="control-label" for="choose_ou2">Organisation Unit:</label>
											</td>
											<td>
												<select class="form-control" id="choose_ou2" >
													<script type="text/JavaScript">
														$(document).ready(function(){
														viewOrgUnits("dropdown","choose_ou2","all");
														//getAssociatedTabs("associated_tabs");
															$("#choose_ou2").change(function(){
																getRoles("choose_role2",$("#choose_ou2").val());
															});
														});
													</script>
												</select>
											</td>
										</tr>
										<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
										<tr>
											<td>
												<label class="control-label" for="choose_role2">Select Role:</label>
											</td>
											<td>
												<select class="form-control" id="choose_role2" >
													<script type="text/JavaScript">
														$(document).ready(function(){
															$("#choose_role2").change(function(){
																getTabs("list_of_tabs");
															});
														});
													</script>
												</select>
											</td>
										</tr>
									</table>
									<br/>
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<table width="100%">
											<tr>
												<td><h1 class="panel-title">List of Tabs</h1></td>
												<td align="right">
													<div class="pull-right">
														<Button class="btn btn-default" id="refresh_tab_list">
														<span class="glyphicon glyphicon-refresh"></span></Button>
													</div>
												</td>
											</tr>
											</table>		
										</div>
										<div style="max-height:350px;min-height:350px; overflow:hidden; 
										overflow-x:auto;overflow-y:auto;">
											<table class="table table-striped" id="list_of_tabs">
											<script type="text/JavaScript">
												$(document).ready(function(){
													//getTabs("list_of_tabs");
													$("#list_of_tabs").html("<h1 align='center'>Click Refresh button "+
													"<br/><Button class='btn btn-primary btn-lg btn-round' type='button' "+
													"style='height:50px;width:50px;border-radius:50%'"+
													"onclick='getTabs(\""+"list_of_tabs"+"\");'>"+
													"<span class='glyphicon glyphicon-refresh'></span></Button><br/>"+
													" to display all the list of tabs</h1>");
													$("#list_of_tabs").css('color','#A4A4A4');
													$("#refresh_tab_list").click(function(){
														getTabs("list_of_tabs");
														return false;
													});
												});
											</script>
											</table>
										</div>
									</div>
								</div>									
							</div>
						</div>
					</form>		
				</div>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for displaying Users-->
<div class="modal fade" id="displayUsers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="width:90%;min-height:50%;overflow-x:auto">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">List of Users Created:
					<div class="pull-right">
						<form method="GET">
						<table width="100%">
							<tr>
								<td>Find a user: &nbsp;</td>
								<td>
									<input type="text" class="form-control" id="search_user" onkeyup="find()" 
										placeholder="Type a Username Here"/>
								</td>
								<td>
									<button type="submit" class="btn btn-default" id="findUser">
									<span class="glyphicon glyphicon-search"></span>
									</button>
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
								<td>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
								</td>
							</tr>
						</table>
						</form>
					</div>
				</h4>
			</div>
			<div class="modal-body">
						<script type="text/JavaScript">
							function find(){
								$("#user_display_content").html("<center><p>Wait Please...</p></center>");
								var user_name = $("#search_user").val();
								if(user_name.length==0)
								{
									$("#user_display_content").html("<center><p>Type a username</p></center>");
								}
								else findUsers("user_display_content",user_name);
							}
							$(document).ready(function(){
								getAllUsers("user_display_content");
								$("#findUser").click(function(){
									$("#user_display_content").html("<center><p>Wait Please...</p></center>");
									find();
									return false;
								});
								
								$("#view_all_users").click(function(){
									$("#user_display_content").html("<center><p>Wait Please...</p></center>");
									getAllUsers("user_display_content");
									return false;
								});
							});
						</script>
					<div id="user_display_content" style="max-width:100%; min-height:50%; overflow:hidden;overflow-x:auto">
								Content goes here....
					</div>	
			</div>
			<div class='modal-footer'>
				<div class="pull-right"><Button class="btn btn-link" id="view_all_users">VIEW ALL</Button></div>
			</div>
		</div>
	</div>
</div>
<!-- Modal for logout -->
<div class="modal fade" id="logoutConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<!--<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Logout Confirmation</h4>
			</div>-->
			<div class="modal-body">
				<div class="alert alert-danger">
				  <center><strong>Logout! &nbsp;</strong> Are you sure?</center>
				</div>
				<center>
					<button type="button" style='width:45%' class="btn btn-success" data-dismiss="modal" aria-label="Close">No</button>
					&nbsp;&nbsp;
					<a href="logout.php"  style='width:45%' class="btn btn-warning" style="width:20%" id="YesLogout">Yes</a>
				</center>
			</div>	
		</div>
	</div>
</div>

<?php } ?>
</body>
</html>
