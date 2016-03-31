<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
	<!--<link rel="stylesheet" type="text/css" href="css/main.css">-->
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/npm.js"></script>
	<script src="homepage.js"></script>
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
			background-color:#F2F2F2; color:#292928;padding-top:5px;width:100%;
				padding-bottom:5px;padding-left:10px;padding-right:10px;border-radius:3px
		}
		.my_table {width:100%}
		h4 {font-family:calibri}
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
						
	?>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <!--<span class="sr-only">Toggle navigation</span>
            <span class="icon-bar">ABC</span>
            <span class="icon-bar">XYZ</span>
            <span class="icon-bar"></span>-->
          </button>
          <a class="navbar-brand" href="#">H Circle</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Help</a></li>
          </ul>  
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
			<button type="button" class="btn btn-default">
			  <span class="glyphicon glyphicon-search"></span> Search
			</button>
          </form>
        </div>
      </div>
    </nav>
	<div class="container-fluid" ><br><br>
		<div class="row"><!--class="row"-->
			<div class="col-sm-3 col-md-2 sidebar" >
			<!--<div class="col-md-2"style="background-color:#F2F2F2;border-radius:5px">-->
				<div class="nav nav-sidebar" style="background-color:#F2F2F2;border-radius:0px">
					<div class="col-md-2"><img src="img/user.png" class="circular" alt="No profile Image found"/>
						<p id="userID"><?php echo $user_name; ?></p>
						<p><?php echo $user_role ?></p>
					</div>
				</div>
				<!--class="nav nav-tabs nav-stacked"-->
				<ul class="nav nav-sidebar" style="background-color:#F2F2F2;border-radius:0px">
					<li><a href="#" data-toggle="modal" data-target="#createorg">Create Organization</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createorgunit">Create Organization Unit</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createrole">	Create Roles</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createuser" 
						onclick='getRoles("UserRole",$("#OrgUnitList").val());return false;'>	Create Users</a></li>
					<li><a href="#">Create Tabs Strips</a></li>
					<li><a href="#" data-toggle="modal" data-target="#createTemplateDialog">Create Tabs template</a></li>
					<li><a href="#" data-toggle="modal" data-target="#assocRole2Tab"
						onclick='getRoles("sel_roles",$("#sel_org_unit_role_tab").val());return false;'>Associate Role to Tab</a></li>
					<li><a href="#" data-toggle="modal" data-target="#assocTab2Template"
						onclick='getRoles("roleSelect",$("#orgUnitSelect").val());return false;'>Associate Tab to Template</a></li>
					<li><a href="#">Edit Profiles</a></li>
					<li><a href="#" data-toggle="modal" data-target="#logoutConfirmation">logout</a></li>
				</ul>
			</div>
			<div class="col-md-8"><!--class="col-md-8"-->	
				<div class="col-md-12" style="padding-top:0px">
					<h3 class="page-header">Organisation Units</h3>
					<br/>	
					<div class="container">
					<table  class='table' id="showOrgUnits" style="max-width:70%">
							<script>
								$(document).ready(function(){
									document.getElementById("showOrgUnits").innerHTML="<center><img src='img/loading_data.gif'/></center>";
									viewOrgUnits("list","showOrgUnits","few");
								});					
							</script>	
					</table>
					</div>
					<Button type="button" id="viewAllOrgUnitLists" style="float:right" class="btn btn-success">VIEW ALL</Button>
				</div>
				<div class="col-md-12">	<br/><br/>
					<h3 class="page-header">Organisation</h3>
					<br/>
					<div class="container">
						<table  class='table' id="showOrgsList" style="max-width:70%">
							<script>
								$(document).ready(function(){
									document.getElementById("showOrgsList").innerHTML="<center><img src='img/loading_data.gif'/></center>";
									viewOrgs("list","showOrgsList","few");
								});
							</script>	
						</table>
					</div>
					<Button type="button" id="viewAllOrgLists" style="float:right" class="btn btn-success">VIEW ALL</Button>
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
						<label for="orgname" class="col-sm-4  control-label">Organization Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" value="" name="orgname" id="orgname" placeholder="Organization name">
						</div>
					</div>
					<div class="form-group">
                        <label for="display_name" class="col-sm-4  control-label">Display Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" placeholder="Display Name" name="display_name" id="display_name">
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
				<form class="form-horizontal" method="post">
					<div class="form-group">
						<label for="orgunit" class="col-sm-4  control-label">Organization Unit Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" value="" id="orgunit" name="orgunit" placeholder="Organization name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="displaynameunit" class="col-sm-4  control-label">Display Name</label>
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
						<label class="col-sm-4  control-label" for="ousel">Organization Unit</label>
						<div class="col-sm-4">
							<select class="form-control" id="ousel">
								<script type="text/JavaScript">
									$(document).ready(function(){
										viewOrgUnits("dropdown","ousel","all");
									});
								</script>
							</select>
						</div>
						<div class="col-sm-4">
							<button type="submit" class="btn btn-default" id="disp_role">Display Existing Roles </button>
							<script type="text/JavaScript">
								$(document).ready(function(){
									$('#disp_role').click(function(){
										displayRoles("role_lists",$("#ousel").val());
										return false;
									});
									$('#ousel').change(function(){
										displayRoles("role_lists",$("#ousel").val());
										return false;
									});
								});
							</script>
						</div>
					</div>
					<div class="row" id="role_lists">
						<!--
						<div class="col-sm-4">
							<h3>Column 2</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
							<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
						</div>
						-->
					</div>
					<div class="form-group">
						<div class="col-sm-4">
							<a href="#" data-toggle="collapse" data-target="#create_role_collapsible">
								+ Create </a>a new one here
						</div>
					</div>
					<div id="create_role_collapsible" class="collapse">
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
						<div class="modal-footer">
							<div id="error3" class="col-sm-offset-2 col-sm-8"></div>
							<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
							<button type="submit" class="btn btn-default" id="btnrole">Create </button>
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
										$("#OrgUnitList").change(function(){
											getRoles("UserRole",$("#OrgUnitList").val());
										});
									});
								</script>
							</select>
						</div>
					</div> 

					<div class="form-group">
						<label class="col-sm-4  control-label">Role</label>
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

<!-- Modal for Associating Role to Tab 
Tabs are created in this section-->
<div class="modal fade" id="assocRole2Tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Associate Role to Tab</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">	
					<table border="0" align="center" class="table table-hover">
						<tr>
							<td>
								<label>Organisation Unit</label>
								<select class="form-control" id="sel_org_unit_role_tab">
									<script type="text/JavaScript">
										$(document).ready(function(){
											viewOrgUnits("dropdown","sel_org_unit_role_tab","all");/*displaying dropdown list of organisation unit*/
										});
									</script>
								</select>
							</td>
							<td><label>Roles</label>
								<select class="form-control" id="sel_roles">
								</select>	
							</td>
							<td><label>Number of Tabs</label>
								<select class="form-control" id="no_of_tabs" >
									<option>1</option><option>2</option><option>3</option><option>4</option>
								</select>
							</td>								
						</tr>
						<tr>
							<td colspan="2"><span id="saveAsscRole2TabResponse"></span></td>
							<td align="right">
								<Button class="btn btn-default" id="saveAsscRole2Tab" style="width:60px">Save</Button>
							</td>
						</tr>
					</table>
				</form>
				
			</div>	
		</div>
	</div>
</div>

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
					<center><table class="table table-hover">
					<tr><td>Organisation Unit: <select class="form-control" id="orgUnitSelect">
													<script type="text/JavaScript">
														$(document).ready(function(){
															/*displaying dropdown list of organisation unit*/
															viewOrgUnits("dropdown","orgUnitSelect","all");
														});
													</script>
												</select></td>
						<td>Role: <select class="form-control" id="roleSelect"></select>
						</td>
						<td>
							<br/><Button type="submit" class="btn btn-default" id="getTabsTemplate">Get Tabs and Templates</Button>
						</td>
					</tr>
					</table></center>
				</form>
				<form><div id="tabs_template_result"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal for logout -->
<div class="modal fade" id="logoutConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
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
</div><?php } ?>
</body>
</html>
