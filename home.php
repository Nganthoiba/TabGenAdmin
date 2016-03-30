<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="homepage.js"></script>
	<!--ul.listShow li:hover {background-color:#F0F0F0;cursor:pointer;color:#202020}-->
	<style type="text/css">
		.listShow {list-style-type:none; max-width:70%}
		
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
	<div class="container-fluid"><br><br>
		<div><!--class="row"-->
			<div class="col-md-2" style="height:100%">
				<div class="col-md-12" >
					<div class="col-md-2" style="padding-bottom:10px;padding-top:10px"><img src="img/user.png" class="circular" alt="No profile Image found"/>
						<p id="userID"><?php echo $user_name; ?></p>
						<p><?php echo $user_role ?></p>
					</div>
				</div>
				
				<ul class="nav nav-tabs nav-stacked" style="background-color:#F2F2F2;border-radius:5px;padding-top:10px">
					<a href="#" data-toggle="modal" data-target="#createorg"><li>Create Organization</li></a>
					<a href="#" data-toggle="modal" data-target="#createorgunit"><li>Create Organization Unit</li></a>
					<a href="#" data-toggle="modal" data-target="#createrole">	<li>Create Roles</li></a>
					<a href="#" data-toggle="modal" data-target="#createuser" 
						onclick='getRoles("UserRole",$("#OrgUnitList").val());return false;'>	<li>Create Users</li></a>
					<li>Create Tabs Strips</li>
					<a href="#" data-toggle="modal" data-target="#createTemplateDialog"><li>Create Tabs template</li></a>
					<a href="#" data-toggle="modal" data-target="#assocRole2Tab"
						onclick='getRoles("sel_roles",$("#sel_org_unit_role_tab").val());return false;'><li>Associate Role to Tab</li></a>
					<a href="#" data-toggle="modal" data-target="#assocTab2Template"
						onclick='getRoles("roleSelect",$("#orgUnitSelect").val());return false;'><li>Associate Tab to Template</li></a>
					<li>Edit Profiles</li>
					<a href="#" data-toggle="modal" data-target="#logoutConfirmation"><li>logout</li></a>
				</ul>
			</div>
			<div class="col-md-8"><!--class="col-md-8"-->	
				<div class="col-md-12" style="padding-top:0px">
					<h3>Organisation Units</h3>
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
					<h3>Organisation</h3>
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
						<label class="col-sm-4  control-label">Can access other OU</label>
						<div class="col-sm-8">
							<label class="radio-inline"><input type="radio" name="optradio" id="access_yes" checked>Yes</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="access_no">No</label>
						</div>
					</div>-->
	
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
				<!--<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#role_specific_tab_layout">Create Role specific Tab</a></li>
					<li><a data-toggle="tab" href="#global_tab_layout">Create Global Tab</a></li>
				</ul>
				<div class="tab-content">				
					<div id="global_tab_layout" class="tab-pane fade">
						<form class="form-horizontal">
						<p>This tab is globally visible to all users in an organisation unit irrespective of their roles.<p>
							<table border="0" align="center" class="table table-hover" width="100%">
								<tr>
									<td>
										<label>Organisation Unit</label>
										<select class="form-control" id="sel_org_unit_global_tab">
											<script type="text/JavaScript">
												$(document).ready(function(){
													viewOrgUnits("dropdown","sel_org_unit_global_tab","all");/*displaying dropdown list of organisation unit*/
												});
											</script>
										</select>
									</td>
									<td><label>Number of Tabs</label> 
										<select class="form-control" id="no_of_global_tabs">
											<option>1</option><option>2</option><option>3</option><option>4</option>
										</select>
									</td>
									<td align="right">
										<label><br/></label><br/><Button class="btn btn-default" id="saveGlobalTab" style="width:60px">Save</Button>
									</td>
								</tr>
								<tr>
									<td colspan='3'><span id="saveGlobalTabResponse"></span></td>		
								</tr>
							</table>
						</form>
					</div>
					<div id="role_specific_tab_layout" class="tab-pane fade in active">
							
					</div>
				</div>-->
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
</div><?php } ?>
</body>
</html>
