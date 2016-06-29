<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
	<!--<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">-->
	
	
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/simple-sidebar.css">
	<link rel="stylesheet" type="text/css" href="css/my_custom_style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		 var role_list;
		 var js_session = sessionStorage.getItem('user_details');
		if(js_session==null){
			window.location.assign("index.html");
		}
		var user_session = JSON.parse(js_session);
		 
		
		
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
		});
		*/
		$(document).ready(function(){
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		});
		function getSession(){
			document.getElementById("user_detail_section").innerHTML=user_session.username;
			setInterval(
				function(){
						//alert("Hello"); 
						$.ajax({
						url: "getUserSession.php",
						type: "GET",
						success:function(data){
							if(data.trim()=="null"){
								//user_session=null;
								window.location.assign("index.html");
							}
							else{
								//user_session=JSON.parse(data);
							}
							//alert("Token: "+user_session.token+" User id: "+user_session.id);
						},
						error:function(error_data,y,z){
							//user_session=null;
							//alert(error_data+" "+y+" "+z);
						}
					});
				}, 30000);	
		}
	</script>
	<!--ul.listShow li:hover {background-color:#F0F0F0;cursor:pointer;color:#202020}-->
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
<body onload="getSession()">
	<?php
        session_start();
	
        if(!isset($_SESSION['user_details']) && !isset($_COOKIE['user_details'])){
                //echo "<p align='center'>You have to <a href='index.html'>login</a> first<br/>";
                header('Location: index.html');
        }
        else {
			$user_data = null;
			if(isset($_SESSION['user_details'])){
                $user_data = json_decode($_SESSION['user_details']);
			}
            else if(isset($_COOKIE['user_details'])){
				$user_data = json_decode($_COOKIE['user_details']);
			}
			if($user_data!=null){
				$user_role = $user_data->roles;
				$user_email= $user_data->email;
				$user_name = $user_data->username;
						
	?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#menu-toggle" id="menu-toggle">
			  <span class="glyphicon glyphicon-align-justify"></span>
		  </a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav">
			  <!--
			<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
			<li><a href="#">Link</a></li>
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#">Separated link</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#">One more separated link</a></li>
			  </ul>
			</li>-->
		  </ul>
		  <!--<form class="navbar-form navbar-left" role="search">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Search">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		  </form>
		  <ul class="nav navbar-nav">
				  <li class="active"><a href="#">Home</a></li>
				  <li><a href="#">Page 1</a></li>
				  <li><a href="#">Page 2</a></li> 
		  </ul>-->
		  <ul class="nav navbar-nav navbar-right">
			<li>
				<a href="#">
					  <span class="glyphicon glyphicon-user"></span>
					   Edit Profiles
				</a>
			</li>
			<li>
				<a href="#" data-toggle="modal" data-target="#logoutConfirmation">
					  <span class="glyphicon glyphicon-log-out"></span>&nbsp;logout</a>
			</li>
			<!--<li><a href="#">Link</a></li>
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#">Separated link</a></li>
				
			  </ul>
			</li>-->
		  </ul>
		</div>
	  </div>
	</nav>
	<div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
			<center>
				<div style="padding-top:10px"><img src="img/user.png" class="circular" alt="No profile Image found"/></div>
			</center>
			
            <ul class="sidebar-nav">
				<br/><br/><br/>
				<li class="sidebar-brand">
				</li>
				<li class="sidebar-brand">
					<div style="color:#f7f7f7;background-color:#5061DC;width:100%; 
						padding-left:5px;padding-right:5px;" id="user_detail_section">
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
						Create a tab</a><!--getRoles("role_selector",$("#ou_selector").val(),"createTabResponse");-->
				</li>
					
				<li>
					<a href="#" data-toggle="modal" data-target="#display_tab_layout">Show tabs</a>
				</li>
				<li>
					<a href="#" data-toggle="modal" data-target="#create_tabstrip_modal"
						onclick='refresh_all_entries();return false;'>Create Tabstrip</a>
				</li>
				<li><a href="#" data-toggle="modal" data-target="#">Add Tabs to Tabstrip</a></li>
				<li><a href="#" data-toggle="modal" data-target="#createuser" 
								onclick='getRoles("UserRole",$("#OrgUnitList").val(),"error4");refresh_all_entries();return false;'>
									Create a user</a>
				</li>
								
				<li><a href="#" data-toggle="modal" data-target="#displayUsers">Show users</a></li>
					
					<!--<li><a href="#">Create Tabs Strips</a></li>-->
				<li><a href="#" data-toggle="modal" data-target="#createTemplateDialog">Create Tabs template</a></li>
				<li><a href="#" data-toggle="modal" data-target="#associate_tabs_to_role">
					Associate Tabs to Role</a></li>
					<!--
					
					onclick='viewOrgListWithOUsRoles("org_lists","choose_ou","choose_role","associated_tabs")'
					onclick='getRoles("choose_role",$("#choose_ou").val(),"role_result");
						getAssociatedTabs("associated_tabs");'
					-->
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<div class="container-fluid">
            <div class="box">	
				<div class="heading">
							Organisations
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
				<div>
					<table  class='table' cellspacing="10" 
							id="showOrgsList" border='0' style="padding-top:20px">
							<script>
								$(document).ready(function(){									
									document.getElementById("showOrgsList").innerHTML="<br/><br/><center><img src='img/loading_data.gif'/></center>";
									viewOrgs("list","showOrgsList","all");
								});
							</script>	
					</table>
				</div>
					<!--<div class="pull-right">
						<Button type="button" id="viewAllOrgLists" class="btn btn-link">VIEW ALL</Button>
					</div>	-->	
					<div> &nbsp; </div>			
			</div>
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
				<div>		
					<table  class='table' cellspacing="10" 
							
						id="showOrgUnits" border="0" style="padding-top:20px">
						<script>
								$(document).ready(function(){
									document.getElementById("showOrgUnits").innerHTML="<br/><br/><center><img src='img/loading_data.gif'/></center>";
									viewOrgUnits("list","showOrgUnits","all");
								});					
						</script>	
					</table>
				</div>			
					<!--<div class="pull-right">
						<Button type="button" id="viewAllOrgUnitLists" class="btn btn-link">VIEW ALL</Button>
					</div>-->
				<div> &nbsp; </div>	
			</div>
			</div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

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
							<!--<div class="form-group">
								<label for="display_name" class="col-sm-4  control-label">Display Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" placeholder="Display Name" name="display_name" id="display_name">
								</div>
							</div>-->
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
					<!--<h3 align="center"> 
					<a href="#" data-toggle="collapse" class='btn btn-link' data-target="#create_role_collapsible">
					<span class="glyphicon glyphicon-plus"></span> Click here to create a new role</a></h3>-->
					
					<!--<div id="create_role_collapsible" class="collapse">-->
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
													viewOrgs("dropdown","select_org_for_role","all");
													
													$("#select_org_for_role").change(function(){
														var org = $("#select_org_for_role").val();
														getOUlists(org,"select_ou_4_role");
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
											onclick="disp_ou_selector();" id="access_yes">Yes</label>
										<label class="radio-inline"><input type="radio" name="optradio" 
											onclick="hide_ou_selector();" id="access_no">No</label>
										<script type="text/JavaScript">
											function disp_ou_selector(){
												document.getElementById("ou_section_for_creating_role").innerHTML=""+
												"<label class='col-sm-4 control-label'>Select an OU:</label>"+
												"<div class='col-sm-8'>"+
													"<select class='form-control' id='select_ou_4_role'></select>"+
												"</div>";
												var org = $("#select_org_for_role").val();
												getOUlists(org,"select_ou_4_role");
											}
											function hide_ou_selector(){
												document.getElementById("ou_section_for_creating_role").innerHTML=" ";
											}	
										</script>
									</div>
								</div>
								<div class="form-group" id="ou_section_for_creating_role">
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
					<div class="panel panel-info">
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
									<button type="submit" class="btn btn-info" id="CreateUser">Create </button>
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
									id="ou_specific_yes" checked>Yes</label><!--onclick="disp_ou_role_selector_region();" -->
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="ou_specific_no">No</label><!--onclick="hide_ou_role_selector_region();"-->
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
								/*var org = $("#choose_org").val();
								getOUlists(org,"ou_selector");*/
								
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
											//viewOrgs("dropdown","choose_org_tabstrip","all");
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
									id="tabstrip_ou_specific_yes" checked>Yes</label><!--onclick="disp_ou_role_selector_region();" -->
								<label class="radio-inline"><input type="radio" name="optradio" 
									id="tabstrip_ou_specific_no">No</label><!--onclick="hide_ou_role_selector_region();"-->
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
												//viewOrgs("dropdown","org_lists","all");
												viewOrgListWithOUsRoles("org_lists","choose_ou","choose_role","associated_tabs");
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
																var orgunit=($("#choose_ou").val()).trim();
																//getRoles("choose_role",orgunit,"associated_tabs");
																$.ajax({
																	type:"GET",
																	url: "getRoles.php",
																	data: "org_unit="+orgunit+"&only_ou_roles=no",
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
															else{
																document.getElementById("choose_ou").innerHTML="<option></option>";
															}
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
												//getAssociatedTabs("associated_tabs");
												$("#choose_ou").change(function(){
													getRoles("choose_role",$("#choose_ou").val(),"associated_tabs");
													validate_and_get_tabs();
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
													<td align="right">
														<Button type="button" class="btn btn-info" id="refresh_ass_tab">REFRESH
															<span class="glyphicon glyphicon-refresh"></span>
														</Button>
													</td>
												</tr>
											</table>		
										</div>
											<div class="header_bg">
												<label class="col-sm-4 control-label" for="choose_role">Select Role:</label>
												<div class="col-sm-6">
													<select id="choose_role" class="form-control">
															<script type="text/JavaScript">
																$(document).ready(function(){
																	//getRoles("choose_role",$("#choose_ou").val());
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
												//getAssociatedTabs("associated_tabs");
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
													//alert("Hi");
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
													<td align="right">
														<div class="pull-right">
															<Button type="button" class="btn btn-info" id="refresh_tab_list">REFRESH
															<span class="glyphicon glyphicon-refresh"></span></Button>
														</div>
													</td>
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
													//getTabs("list_of_tabs");
													$("#list_of_tabs").html("<h3 align='center'>Click Refresh button "+
													"<br/>"+
													" to display all the list of tabs</h3>");
													$("#list_of_tabs").css('color','#A4A4A4');
													$("#refresh_tab_list").click(function(){
														//getTabs("list_of_tabs");
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
								<td><h4>Find a user: &nbsp;</h4></td>
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
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</td>
							</tr>
						</table>
						</form>
					</div>
				</h4>
			</div>
			<div class="modal-body"
				style="max-height:500px;
						min-height:350px; overflow:hidden;
						min-width:120px; 
						overflow-x:auto;overflow-y:auto;">
						<script type="text/JavaScript">
							function find(){
								$("#user_display_content").html("<center><p>Wait Please...</p></center>");
								var user_name = $("#search_user").val();
								if(user_name.length==0)
								{
									$("#user_display_content").html("<center><p>Type a username</p></center>");
								}
								else {
									findUsers("user_display_content",user_name);
								}
								$("#view_all_users_display").html("<Button class='btn btn-default' "+
										"id='view_all_users' onclick='getAll()';>VIEW ALL</Button>");
							}
							$(document).ready(function(){
								getAllUsers("user_display_content");
								$("#findUser").click(function(){
									$("#user_display_content").html("<center><p>Wait Please...</p></center>");
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
	<div class="modal-dialog" style="width:60%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
				<h4 class="modal-title" id="myModalLabel">List of Tabs created:</h4>
			</div>
			<div class="modal-body">
				<table class='table' id="get_all_tabs">
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
					<button type="button" style='width:45%' class="btn btn-default" data-dismiss="modal" aria-label="Close">No</button>
					&nbsp;&nbsp;
					<a href="logout.php"  style='width:45%' class="btn btn-default" style="width:20%" id="YesLogout">Yes</a>
				</center>
			</div>	
		</div>
	</div>
</div>
<?php  //echo get_token();
	}else{
		echo "<p><center>Sorry! NO user session available, try login again.</center></p>";
	}
}

 ?>
 
</body>
</html>
