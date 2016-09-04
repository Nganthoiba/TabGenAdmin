<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">-->
	
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/simple-sidebar.css">
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/npm.js"></script>
	<!-- This is what you need for sweet alert -->
	<script src="dist/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="dist/sweetalert.css">
	<!--.......................-->
	
	<script type="text/JavaScript" src="homepage.js"></script>
	<script type="text/JavaScript">	
		/*
		 * global variables
		 * */
		
		var arr; /*array for tab template association*/
		var prev_tab_name = [];/*Global array for tab name*/
		var templates_arr=""; /*list of templates*/
		var tabs=[];//list of tabs
		//var json_arr;
		var role_list;//list of roles
		var user_session;//user details from server
		
		$(document).ready(function(){
			//getting template lists from server
			setTemplateList("choose_templates");
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		});
		check_session();//checks for user sesion
		/*function to check whether the session is alive or not, which 
		means if user's token is still valid, he/she will be able to do/access tasks. 
		The token will exist or remains valid untill and unless the user voluntarily logs out.*/
		function check_session(){
			var js_session = sessionStorage.getItem('user_details');
			if(js_session=="null" || js_session=="" || js_session==null){
				$.ajax({
					url: "getUserSession.php",
					type: "GET",
					success:function(data){
						if(data.trim()=="null"){
							window.location.assign("index.html");
							removeSession();//removing session
						}
						else{
							sessionStorage.setItem('user_details',data);
							js_session = sessionStorage.getItem('user_details');
						}
					},
					error:function(error_data,y,z){
						window.location.assign("index.html");
					}
				});
			}
			else  user_session = JSON.parse(js_session);	
		}
		//function to remove user details when the user click logout button
		function removeSession(){
			sessionStorage.setItem('user_details', "null");
		}
		
	</script>
	<style type="text/css">		
		
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
	</style>	
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#" id="menu-toggle">
			  <span class="glyphicon glyphicon-align-justify"></span>
		  </a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav">
		  </ul>
		  <ul class="nav navbar-nav navbar-right">
			<li>
				<a href="#" data-toggle="modal" data-target="#logoutConfirmation" >
					  <span class="glyphicon glyphicon-log-out"></span>&nbsp;logout</a>
			</li>
		  </ul>
		</div>
	  </div>
	</nav>
	<div id="wrapper">
        <!-- Sidebar img/user.png-->
        <div id="sidebar-wrapper">
			<center>
				<div style="padding-top:10px" id="profile_image">
					<script type="text/Javascript">
						set_profile(user_session.id,"profile_image");	
					</script>
				</div>
			</center>
			
            <ul class="sidebar-nav" style='height:100%;overflow:hidden;overflow-y:auto'>
				<br/>
				<li class="sidebar-brand">
				</li>
				<li class="sidebar-brand">
				</li>
				<li class="sidebar-brand">
					<div style="color:#f7f7f7;background-color:#5061DC;width:100%; 
						padding-left:5px;padding-right:5px;" id="user_detail_section">
						<script type="text/javascript">
							$(document).ready(function(){
								$("#user_detail_section").html(user_session.username);
							});
						</script>
					</div>
				</li>
                <li>
					<a href="#" data-toggle="modal" data-target="#createorg" 
						onclick="refresh_all_entries();">Create Organisation</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#createorgunit"
						onclick="refresh_all_entries();">Create Organisation Unit</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#createrole"
						onclick="refresh_all_entries();">Create Roles</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#create_tab_modal"
						onclick='refresh_all_entries();
								getOUandRole("choose_org","ou_selector","role_selector","createTabResponse");'>
						Create a tab</a>
				</li>
					
				<li>
					<a href="#" data-toggle="modal" data-target="#display_tab_layout">Show tabs</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#create_tabstrip_modal"
						onclick='refresh_all_entries();return false;'>Create Tabstrip</a>
				</li>
				<li><a href="#" data-toggle="modal" data-target="#addTabsToTabstrips">Add Tabs to Tabstrip</a></li>
				<li><a href="#" data-toggle="modal" data-target="#createuser" 
								onclick='getRoles("UserRole",$("#OrgUnitList").val(),"error4");refresh_all_entries();return false;'>
									Create a user</a>
				</li>
								
				<li><a href="#" data-toggle="modal" data-target="#displayUsers">Show users</a></li>
				
				<li><a href="#" data-toggle="modal" data-target="#associate_tabs_to_role">
					Associate Tabs to Role</a></li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<div class="container-fluid">
            <div class="box">	
				<div class="headLine">
					Organisations
				</div>
				<div>
					<table  class='table table-striped' cellspacing="20" 
							id="showOrgsList" border='0' style="padding-top:20px">
							<script>
								$(document).ready(function(){									
									document.getElementById("showOrgsList").innerHTML="<br/><br/><center>"+
									"<img src='img/loading.gif'/></center>";
									viewOrgs("list","showOrgsList","all");
								});
							</script>	
					</table>
				</div>	
				<div> &nbsp; </div>			
			</div>
			<div class="box">
				<div class="headLine">Organisation Units
				</div>
				<div>		
					<table  class='table table-striped' cellspacing="20" 
							
						id="showOrgUnits" border="0" style="padding-top:20px">
						<script>
								$(document).ready(function(){
									document.getElementById("showOrgUnits").innerHTML="<br/><br/><center>"+
									"<img src='img/loading.gif'/></center>";
									viewOrgUnits("list","showOrgUnits","all");
								});					
						</script>	
					</table>
				</div>			
				<div> &nbsp; </div>	
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
					<span class="glyphicon glyphicon-remove"></span>
				</button>
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
						</div>
						<div class="panel-footer clearfix">
							<label id="error1" class="col-sm-offset-2 col-sm-8"></label>
							<div class="pull-right"><button type="submit" class="btn btn-info" id="submit">Create </button></div>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
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
							<button type="submit" class="btn btn-info" id="createorgunitbtn">Create</button>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title" id="myModalLabel">Create Role</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					
					<div id="role_lists">
						
					</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									<label for="rolaname" class="col-sm-4  control-label">Role Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="rolaname" id="rolaname" placeholder="Role name">
									</div>
								</div>
							
								<div class="form-group">
									<label for="roletype" class="col-sm-4  control-label">Role Type:</label>
									<div class="col-sm-8">
										<select class="form-control" name="role_type" id="roletype">
											<option>Doctor</option>
											<option>Nurse</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4  control-label">Organisation:</label>
									<div class="col-sm-8">
										<select class="form-control" id="select_org_for_role">
											<script type="text/javaScript">
												$(document).ready(function(){
													//viewOrgs("dropdown","select_org_for_role","all");
													viewOrgListWithOUs("select_org_for_role","select_ou_4_role","error3");
													$("#select_org_for_role").change(function(){
														var org = $("#select_org_for_role").val();
														get_list_of_OUs(org,"select_ou_4_role","error3");
													});
												});
											</script>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4  control-label">OU Specific</label>
									<div class="col-sm-4">
										<label class="radio-inline"><input type="radio" name="optradio" 
											 id="access_yes" checked>Yes</label>
										<label class="radio-inline"><input type="radio" name="optradio" 
											 id="access_no">No</label>
									</div>
								</div>
								<div class="form-group" id="ou_section_for_creating_role">
									<label class='col-sm-4 control-label'>Select an OU:</label>
									<div class='col-sm-8'>
										<select class='form-control' id='select_ou_4_role'></select>
									</div>
								</div>
							</div>
							<div class="panel-footer clearfix">
								<div id="error3" class="col-sm-offset-2 col-sm-8"></div>
								<div class="pull-right">
									<button type="submit" class="btn btn-info" id="btnrole">Create</button>
								</div>
							</div>
						</div>
					<!--</div>-->
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title" id="myModalLabel">Create User</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label for="displayname" class="col-sm-4  control-label">Full Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"  placeholder="Full name" id="user_displayname">
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
								<label for="conf_pwd" class="col-sm-4  control-label">Confirm Password</label>
								<div class="col-sm-8">
									<input type="password" class="form-control"  placeholder="Confirm Password" id="conf_pwd">
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
													getRoles("UserRole",$("#OrgUnitList").val(),"error4");
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
									<label class="radio-inline">
										<input type="radio" name="optradio" id="universal_access_yes" checked>Yes</label>
									<label class="radio-inline">
										<input type="radio" name="optradio" id="universal_access_no">No</label>
								</div>
							</div>
						</div>
						<div class="panel-footer clearfix">
							<center><label id="error4" class="col-sm-offset-2 col-sm-8"></label></center>
							<div class="pull-right">
									<button type="submit" class="btn btn-info" id="CreateUser">Create</button>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
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
							<center><label id="createTemplateResponse"></label></center>
							<div class="pull-right"><Button type="submit" class="btn btn-info" id="createTemplate">Create</Button></div>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
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
								<select class="form-control" id="choose_templates">
									<script type="text/JavaScript">
										$(document).ready(function(){
											setTemplateList("choose_templates");
										});
									</script>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4  control-label">Organisation:</label>
							<div class="col-sm-8">
								<select class="form-control" id="choose_org">
									<script type="text/JavaScript">
										$(document).ready(function(){
											viewOrgs("dropdown","choose_org","all");
											$("#choose_org").change(function(){
												getOUandRole("choose_org","ou_selector","role_selector","createTabResponse");
											});
										});
									</script>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4  control-label">OU Specific:</label>
							<div class="col-sm-8">
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="ou_specific_yes" checked>Yes</label>
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="ou_specific_no">No</label>
							</div>
						</div>
						<script type="text/JavaScript">
							function disp_ou_role_selector_region(){
								document.getElementById("ou_selector_region").innerHTML=""+
													"<label class='col-sm-4 control-label'>Select an OU:</label>"+
													"<div class='col-sm-8'>"+
														"<select class='form-control' id='ou_selector' onchange='setRole();'></select>"+
													"</div>";
								document.getElementById("role_selector_region").innerHTML=""+
													"<label class='col-sm-4 control-label'>Select a Role:</label>"+
													"<div class='col-sm-8'>"+
														"<select class='form-control' id='role_selector'></select>"+
													"</div>";
								
							}
							function hide_ou_role_selector_region(){
								document.getElementById("ou_selector_region").innerHTML=" ";
								document.getElementById("role_selector_region").innerHTML=" ";
							}
							function setRole(){
								var orgunit=($("#ou_selector").val()).trim();
								getRoles("role_selector",orgunit,"createTabResponse");
							}
								
						</script>
						<div class="form-group" id="ou_selector_region">
							<label class='col-sm-4  control-label' for='ou_selector'>Select an OU:</label>
							<div class='col-sm-8'><select id='ou_selector' class='form-control'></select></div>
						</div>
						<div class="form-group" id="role_selector_region">
							<label class='col-sm-4 control-label'>Select a Role:</label>
							<div class='col-sm-8'><select id='role_selector' class='form-control'></select></div>
						</div>
					</div>	
					<div class="panel-footer clearfix">
						<div class="pull-right"><Button type="button" class="btn btn-info" id="createTab"
							>
							Create</Button>
					</div>
					<span id="createTabResponse"></span></div>	
				</form>
				</div>	
			</div>	
		</div>
	</div>
</div>

<!-- Modal for Creating Tabstrips (a simple design)-->
<div class="modal fade" id="create_tabstrip_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title" id="myModalLabel">Create Tabstrips</h4>
			</div>
			<div class="modal-body">
				<div class="panel panel-default clearfix">
				<form class="form-horizontal">
					<div class="panel-body">							
						<div class="form-group">
							<label class="col-sm-4  control-label">Tabstrip Name:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="tabstrip_name" placeholder="Enter Tabstrip Name here"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4  control-label">Organisation:</label>
							<div class="col-sm-8">
								<select class="form-control" id="choose_org_tabstrip">
									<script type="text/JavaScript">
										$(document).ready(function(){
											viewOrgListWithOUsRoles("choose_org_tabstrip","tabstrip_ou_selector",
											"tabstrip_role_selector","createTabstripResponse");
											$("#choose_org_tabstrip").change(function(){
												getOUandRole("choose_org_tabstrip","tabstrip_ou_selector",
												"tabstrip_role_selector","createTabstripResponse");
											});
										});
									</script>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='col-sm-4  control-label' for='tabstrip_ou_selector'>Select an OU:</label>
							<div class='col-sm-8'>
								<select id='tabstrip_ou_selector' class='form-control'>
									<script type="text/JavaScript">
										$(document).ready(function(){
											$("#tabstrip_ou_selector").change(function(){
												var orgunit=($("#tabstrip_ou_selector").val()).trim();
												getRoles("tabstrip_role_selector",orgunit,"createTabstripResponse");
											});
										});
									</script>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='col-sm-4 control-label' for='tabstrip_role_selector'>Select a Role:</label>
							<div class='col-sm-8'><select id='tabstrip_role_selector' class='form-control'></select></div>
						</div>
						<div class="form-group">
							<label class="col-sm-4  control-label">OU Specific:</label>
							<div class="col-sm-8">
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="tabstrip_ou_specific_yes" checked>Yes</label>
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="tabstrip_ou_specific_no">No</label>
							</div>
						</div>
					</div>	
					<div class="panel-footer clearfix">
						<div class="pull-right">
							<Button type="button" class="btn btn-info" 
								id="createTabstrip">Create</Button>
									<script type="text/JavaScript">
										$(document).ready(function(){
											$("#createTabstrip").click(function(){
												createTabstrip();
											});
										});
									</script>
						</div>
						<span id="createTabstripResponse"></span>
					</div>	
				</form>
				</div>	
			</div>	
		</div>
	</div>
</div>

<!-- Modal for Associating Tabs to role (a simple design)-->
<div class="modal fade" id="associate_tabs_to_role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="width:70%; min-height:50%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-remove"></span></button>
				<h4 class="modal-title" id="myModalLabel">Associate Tabs to Roles</h4>
			</div>
			<div class="modal-body">
					<form class="form-horizontal">
							<div class="form-group">
								<label class='col-sm-4 control-label'>Select an Organisation:</label> 
								<div class="col-sm-6">
									<select class="form-control" id="org_lists">
										<script type="text/JavaScript">
											$(document).ready(function(){
												viewOrgListWithOUsRoles("org_lists","choose_ou","choose_role","associated_tabs");
												/*onchange effect of the dropdown Organisation list items*/
												$("#org_lists").change(function(){
													var org_name=($("#org_lists").val()).trim();
													$.ajax({
														type: "GET",
														url: "orgUnitList.php",
														data: {"org_name":org_name},
														success: function(data){
															if(data.trim()!="null"){
																var ou_list = JSON.parse(data);
																var list=" ";
																for(var i=0;i<ou_list.length;i++){
																	list+="<option>"+ou_list[i].OrganisationUnit+"</option>";
																}
																document.getElementById("choose_ou").innerHTML=list;
															}
															else{
																document.getElementById("choose_ou").innerHTML="<option></option>";
															}
															var orgunit=($("#choose_ou").val()).trim();
															$.ajax({
																type:"GET",
																url: "getRoles.php",
																data: "org="+org_name+"&org_unit="+orgunit+"&only_ou_roles=no",
																success: function(data){
																	if(data.trim()=="false"){
																		document.getElementById("choose_role").innerHTML="<option></option>";
																		document.getElementById("associated_tabs").innerHTML="<center>No role exists.</center>";
																		document.getElementById("associated_tabs").style.color="red";
																	}
																	else{
																		document.getElementById("associated_tabs").innerHTML=" ";
																		var arr = JSON.parse(data);
																		role_list = JSON.parse(data);
																		var roleList=" ";
																		var i;
																		var count=0;
																		for(i=0;i<arr.length;i++){
																			roleList+="<option>"+arr[i].RoleName+"</option>";
																			count++;
																		}
																		document.getElementById("choose_role").innerHTML=roleList;
																		validate_and_get_tabs();
																		getAssociatedTabs("associated_tabs");
																	}
																},
																error: function(x,y,z){
																	document.getElementById("choose_role").innerHTML="<option></option>";
																	document.getElementById("associated_tabs").innerHTML="<center>Sorry! Unable to get server.</center>";
																	document.getElementById("associated_tabs").style.color="red";
																}
															});
														}
													});
													return false;
												});
											});
										</script>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="choose_ou">Select an Organisation Unit:</label>
								<div class="col-sm-6">
									<select class="form-control" id="choose_ou" >
										<script type="text/JavaScript">
											$(document).ready(function(){
												viewOrgUnits("dropdown","choose_ou","all");
												
												$("#choose_ou").change(function(){
													
													var org_name=($("#org_lists").val()).trim();
													var orgunit=($("#choose_ou").val()).trim();
													$.ajax({
														type:"GET",
														url: "getRoles.php",
														data: "org="+org_name+"&org_unit="+orgunit+"&only_ou_roles=no",
														success: function(data){
															if(data.trim()=="false"){
																document.getElementById("choose_role").innerHTML="<option></option>";
																document.getElementById("associated_tabs").innerHTML="<center>No role exists.</center>";
																document.getElementById("associated_tabs").style.color="red";
															}
															else{
																document.getElementById("associated_tabs").innerHTML=" ";
																var arr = JSON.parse(data);
																role_list = JSON.parse(data);
																var roleList=" ";
																var i;
																var count=0;
																for(i=0;i<arr.length;i++){
																	roleList+="<option>"+arr[i].RoleName+"</option>";
																	count++;
																}
																document.getElementById("choose_role").innerHTML=roleList;
																validate_and_get_tabs();
																getAssociatedTabs("associated_tabs");
															}
														},
														error: function(x,y,z){
															document.getElementById("choose_role").innerHTML="<option></option>";
															document.getElementById("associated_tabs").innerHTML="<center>Sorry! Unable to get server.</center>";
															document.getElementById("associated_tabs").style.color="red";
														}
													});
												});
											});
										</script>
									</select>
								</div>
							</div>	
							<div class="form-group">
								<div class="col-sm-6">
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<table width="100%">
												<tr>
													<td><h1 class="panel-title">Associated Tabs</h1></td>
													<!--<td align="right">
														<Button type="button" class="btn btn-info" id="refresh_ass_tab">
															RELOAD
															<span class="glyphicon glyphicon-refresh"></span>
														</Button>
													</td>-->
												</tr>
											</table>		
										</div>
											<div class="header_bg">
												<label class="col-sm-4 control-label" for="choose_role">Select Role:</label>
												<div class="col-sm-6">
													<select id="choose_role" class="form-control">
															<script type="text/JavaScript">
																$(document).ready(function(){
																	$("#choose_role").change(function(){
																		getAssociatedTabs("associated_tabs");
																		validate_and_get_tabs();
																	});
																});
															</script>
													</select>
												</div><span id="role_result"></span>
											</div>
										<div style="max-height: 350px;min-height:350px;overflow: hidden;overflow-y: auto;
										-webkit-align-content: center; align-content: center;">
											<table class="table table-striped" id="associated_tabs">
											
											</table>
										</div>
										<script type="text/JavaScript">
											$(document).ready(function(){
												$("#associated_tabs").html("<h3 align='center'>Click Refresh button "+
												"<br/> to display all the associated tabs</h3>");
																								
												$("#associated_tabs").css('color','#A4A4A4');
												$("#refresh_ass_tab").click(function(){
													var ou_name = ($("#choose_ou").val()).trim();
													var role = $("#choose_role").val();
													if(ou_name==null)
													{
														$("#associated_tabs").html("<h4><center>It seems no OU exists for "+
															"the selected Organisation.</center></h4>");
														
													}
													else if(role==null){
														$("#associated_tabs").html("<h4><center>It seems no role exists for the"+
															" selected Organisation Unit.</center></h4>");
													}
													else{
														getAssociatedTabs("associated_tabs");
													}
													return false;
													
												});
											});
										</script>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<table width="100%">
												<tr>
													<td><h1 class="panel-title">Available Tabs</h1></td>
													<!--
													<td align="right">
														<div class="pull-right">
															<Button type="button" class="btn btn-info" 
															id="refresh_tab_list">RELOAD
															<span class="glyphicon glyphicon-refresh"></span></Button>
														</div>
													</td>-->
												</tr>
											</table>		
										</div>
											<div class="header_bg">
													<label class="col-sm-4 control-label">OU Specific:</label>
													<div  class="col-sm-6">
														<label class="radio-inline"><input type="radio" name="ou_specific_tab" 
																	id="ou_specific_tab_yes" checked>Yes</label>
														<label class="radio-inline"><input type="radio" name="ou_specific_tab" 
																	id="ou_specific_tab_no">No</label>
														<script type="text/JavaScript">
															$(document).ready(function(){
																$("#ou_specific_tab_yes").click(function(){
																	validate_and_get_tabs();
																});
																$("#ou_specific_tab_no").click(function(){
																	validate_and_get_tabs();
																});
															});
														</script>
													</div>
											</div>
										<div style="max-height:350px;min-height:350px; overflow:hidden; 
										overflow-x:auto;overflow-y:auto;">
											<table class="table table-striped" id="list_of_tabs">
											<script type="text/JavaScript">
												$(document).ready(function(){
													
													$("#list_of_tabs").html("<h3 align='center'>Click Refresh button "+
													"<br/>"+
													" to display all the list of tabs</h3>");
													$("#list_of_tabs").css('color','#A4A4A4');
													$("#refresh_tab_list").click(function(){	
														validate_and_get_tabs();
													});
												});
											</script>
											</table>
										</div>
									</div>
								</div>									
							</div>
					</form>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for adding tabs to tabstrips -->
<div class="modal fade" id="addTabsToTabstrips" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="addingTabToTabstripLabel">Add Tabs to tabstrips:
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span class="glyphicon glyphicon-remove"></span>
					</button>
				</h3>
			</div>
			<div class="modal-body"
				style="max-height:630px;
						min-height:620px; overflow:hidden;
						min-width:120px;background-color:#ffffff;
						-moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
						-webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
						box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3); 
						overflow-x:auto;overflow-y:auto;">
				<div>
				<form class="form-horizontal">
					<div class="form-group">
						<script type="text/JavaScript">
							viewOrgListWithOUs("myorgselect","myouselect","result_for_add_tab_to_tabstrip");
							getAllTabs("tabs_to_be_added");
							function getOU(){
								var org = document.getElementById("myorgselect").value;
								getOUlists(org,"myouselect");
							}
							function getTabStrips(org_name,ou_name,target_layout_id,resp_layout){
								var ou_specific = document.getElementById("ou_specific_tabstrip_yes").checked;
								
								if(ou_specific==true){
									var ou_trim = ou_name.trim();
									if(ou_trim.length==0 || ou_name==null){
										document.getElementById(resp_layout).innerHTML="<center>It seems no OU exists"+
										" for selected Organisation.</center>";
										document.getElementById(target_layout_id).innerHTML="";
										return false;
									}
									else{
										document.getElementById(resp_layout).innerHTML="<center></center>";
									}
								}
								$.ajax({
									type: "POST",
									url: "getTabStrips.php",
									data: "org_name="+org_name+"&ou_specific="+ou_specific+"&ou_name="+ou_name,
									success: function(resp){
										//alert(resp);
										var json_resp = JSON.parse(resp);
										if(json_resp.status==false){
											document.getElementById(resp_layout).innerHTML="<center>"+json_resp.message+"</center>";
											document.getElementById(target_layout_id).innerHTML="";
										}
										else{
											document.getElementById(resp_layout).innerHTML="<center></center>";
											var output = json_resp.output;
											var layout="";
											for(var i=0;i<output.length;i++){
												var tabstrip_id = output[i].Id;
												
												layout+="<tr><td><button class='btnSubmit' onclick='getUnaddedTab(\""+org_name+
												"\",\""+ou_name+"\",\""+ou_specific+"\",\""+tabstrip_id+"\",\""+
												"tabs_to_be_added"+"\");getAddedTab(\""+org_name+
												"\",\""+ou_name+"\",\""+ou_specific+"\",\""+tabstrip_id+"\",\""+
												"tabs_added"+"\");'>"+output[i].Name+"</button></td></tr>";
											}
											document.getElementById(target_layout_id).innerHTML=layout;
										}
									}
								});
							}
							
							/*effect on tabstrip layout*/
							function onChangeOU(){
								var org = document.getElementById("myorgselect").value;
								var ou = document.getElementById("myouselect").value;
								
								getTabStrips(org,ou,"tabstrip_lists","result_for_add_tab_to_tabstrip");
								document.getElementById("tabs_to_be_added").innerHTML="<div><center></center></div>";
								document.getElementById("tabs_added").innerHTML="<div><center></center></div>";
							}
						</script>
						<label class="col-sm-4 control-label" for="myorgselect">Select an Organisation:</label>
						<div class="col-sm-4">
							<select class="form-control" id="myorgselect" 
								onchange="getOU();">			
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="myouselect">Select an Organisation Unit:</label>
						<div class="col-sm-4">
							<select class="form-control" id="myouselect" onchange="onChangeOU();">
											
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">OU Specific:</label>
						<div class="col-sm-4">
							<label class="radio-inline">	
								<input type="radio" name="ouspecific_yes_no" onclick="onChangeOU();" id="ou_specific_tabstrip_yes" checked/>yes
							</label>
							<label class="radio-inline">	
								<input type="radio" name="ouspecific_yes_no" onclick="onChangeOU();" id="ou_specific_tabstrip_no" />No	
							</label>
						</div>
					</div>
					
				</form>
				<div class="table-responsive">
					<table border="0" class="table" style="background-color:#E6E1E1;border: 1px solid #D8D8D8">
						<tr>
							<th>List of Tabstrips</th>
							<th>List of Tabs added</th>
							<th>List of Tabs not added</th>
						</tr>
						<tr>
							<td align="center">
								<div class="tab_disp_div">
									<div id="result_for_add_tab_to_tabstrip"></div>
									<table class="table table-hover" id="tabstrip_lists"></table>
								</div>
							</td>
							<td align="center">
								<div class="tab_disp_div">
									<table id="tabs_added" class="table table-hover"></table>
								</div>
							</td>
							<td align="center">
								<div class="tab_disp_div">
									<table id="tabs_to_be_added" class="table table-hover"></table>
								</div>
							</td>
						</tr>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal for displaying Users-->
<div class="modal fade" id="displayUsers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="min-height:60%;overflow-x:auto;min-width:70%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
				<h4 class="modal-title" id="myModalLabel">List of Users Created:
				<div class="pull-right">
					<form class="navbar-form navbar-left" method="GET">
						<input type="text" id="search_user" onkeyup="find()" 
									class="form-control" placeholder="Search a user">
						<button type="submit" class="btn btn-default" id="findUser">
							 <span class="glyphicon glyphicon-search"></span> Search 
						</button>	
					</form>	
				</div>	
				</h4>
			</div>
			<div class="modal-body"
				style="max-height:570px;
						min-height:350px; overflow:hidden;
						min-width:120px; 
						overflow-x:auto;overflow-y:auto;">
						<script type="text/JavaScript">
							function find(){
								$("#user_display_content").html("<center><p>Wait Please...</p></center>");
								var user_name = $("#search_user").val();
								if(user_name.length==0)
								{
									$("#user_display_content").html("<center><div class='isa_warning'>Type a username</div></center>");
								}
								else {
									findUsers("user_display_content",user_name);
								}
								$("#view_all_users_display").html("<Button class='btn btn-success' "+
										"id='view_all_users' onclick='getAll()';>VIEW ALL USERS</Button>");
							}
							$(document).ready(function(){
								getAllUsers("user_display_content");
								$("#findUser").click(function(){
									find();
									return false;
								});
							});
							function getAll(){
								$("#user_display_content").html("<center><p>Wait Please...</p></center>");
								getAllUsers("user_display_content");
								$("#view_all_users_display").html(" ");
								return false;
							}
						</script>
					<div id="user_display_content" style="max-width:100%; min-height:50%; overflow:hidden;overflow-x:auto">
						Content goes here....
					</div>	
			</div>
			<div class='modal-footer'>
				<div class="pull-right" id="view_all_users_display"> </div>
			</div>
		</div>
	</div>
</div>

<!-- Modal for displaying tabs -->
<div class="modal fade" id="display_tab_layout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
				<h4 class="modal-title" id="myModalLabel">List of Tabs created:</h4>
			</div>
			<div class="modal-body" style="height:600px;overflow:hidden;overflow-y:auto;">
				<table style='width:100%;' id="get_all_tabs">
					<script type="text/JavaScript">
						$(document).ready(function(){
							getAllTabs("get_all_tabs");
						});
					</script>						
				</table>
			</div>	
		</div>
	</div>
</div>

<!-- Modal for logout -->
<div class="modal fade" id="logoutConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="alert alert-danger">
				  <center><strong>Logout &nbsp;</strong> Are you sure?</center>
				</div>
				<center>
					<script type='text/javascript'>
						$(document).ready(function(){
							$('#token_span').html("<input type='hidden' name='token' "+
							"id='token' value='"+user_session.token+"'/>");
						});
					</script>
					<form name='logout_form' method='POST' action='logout.php'>
						<span id='token_span'></span>
						<button type="button" style='width:45%' class="btn btn-default" data-dismiss="modal" 
						aria-label="Close">NO</button>
						&nbsp;&nbsp;
						<button type="submit" style='width:45%' onclick="removeSession();"
						class="btn btn-default" id="YesLogout">YES</button>
					</form>
				</center>
			</div>	
		</div>
	</div>
</div> 
</body>
</html>
