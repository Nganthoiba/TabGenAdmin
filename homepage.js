/*Javascript code to associate role to a to a tab
Creating Tabs here*/
$(document).ready(function(){	
	//for creating local tabs
	$("#saveAsscRole2Tab").click(function(){
		$("#saveAsscRole2TabResponse").html("<img src='img/loading.gif'/> Wait Please...");
		var orgunit = $("#sel_org_unit_role_tab").val();
		var role = $("#sel_roles").val();
		var tabs = parseInt($("#no_of_tabs").val()); 
		$.ajax({
				type:"POST",
				url:"createTabs.php",
				data:"role_name="+role+"&no_of_tabs="+tabs+"&orgunit="+orgunit,
				success:function(s){
					$("#saveAsscRole2TabResponse").html(s);		
				}
			});
		return false;
	});	
	//for creating global tabs
	$("#saveGlobalTab").click(function(){
		document.getElementById("saveGlobalTabResponse").innerHTML="<p>Under development...</p>";
		return false;
	});
});

/*JavaScript function for setting template to a layout by giving Ids*/
function setTemplateList(id){	
	$.ajax({
		url: "TemplateList.php",
		success: function(templates_arr) {
			if(templates_arr=="error" || templates_arr=="false"){
				document.getElementById(id).innerHTML=" ";
			}else{
				var layout="";
				var json_arr = jQuery.parseJSON(templates_arr);
				for(var i=0;i<json_arr.length;i++){
					layout+="<option>"+json_arr[i].name+"</option>";
				}
				document.getElementById(id).innerHTML=layout;
			}
		},
		error: function( xhr, status, errorThrown ) {
			templates_arr=null;
		}
	});
}

/*JavaScript function for getting Tabs and corresponding Templates assigned for respectives roles of a particular organisation*/
function setTabTemplateLayout(){
	document.getElementById("tabs_template_result").innerHTML="<center><img src='img/loading_data.gif'/></center>";
	//getRoles("roleSelect",$("#orgUnitSelect").val());//getRoles(id,orgunit)
	var orgunit = $("#orgUnitSelect").val();
	var user_role = $("#roleSelect").val();
	$.ajax({
		type: "GET",
		url: "getTabsTemplateAssociation.php",
		data: "orgunit="+orgunit+"&role="+user_role,
		success: function(data){
			if(data.trim()=="false"){//the server returns false when no record found
				document.getElementById("tabs_template_result").innerHTML="<center><p style='color:red'>No record found!</P></center>";
			} 
			else
			{
				/*javascript function for parsing json data and displaying layout for Tab Template Association Update*/
				arr = jQuery.parseJSON(data);// JSON.parse(data)
				var layout="<div class='panel panel-default'><table class='table' align='center'>"+
				"<tr><th style='background-color:#90C6F3; color:#FFFFFF'></th>"+
					"<th style='background-color:#90C6F3; color:#FFFFFF'>Tabs</th>"+
					"<th style='background-color:#90C6F3; color:#FFFFFF'>Tab Templates</th>"+
					"<th style='background-color:#90C6F3; color:#FFFFFF'></th>"+
					"<th style='background-color:#90C6F3; color:#FFFFFF'></th></tr>";
				var role_name="<td></td>";
				/*getting list of templates created by the admin*/
				$.ajax({
					url: "TemplateList.php",
					success: function(list){
						var templateList=" ";
						if(list!="error" || list!="false"){
							var json_arr = jQuery.parseJSON(list);//parsing template json array
							for(var i=0;i<json_arr.length;i++){
								templateList+="<option>"+json_arr[i].name+"</option>";
							}
							for(var i=0;i<arr.length;i++){	
								if(i==0){
									role_name="<td>"+arr[i].RoleName+"</td>";
								}
								else{
									if(arr[i].RoleName!=arr[i-1].RoleName)
										role_name="<td>"+arr[i].RoleName+"</td>";
									else
										role_name="<td></td>";
								}
								prev_tab_name[i]=(arr[i].Tab_Name);
								layout+="<tr>"+role_name+"<td><input type='text' id='tab_name"+i+"' class='form-control' value='"+arr[i].Tab_Name+"'/></td>"+
										"<td><select class='form-control'  onchange='clear();'id='template_name"+i+"'><option>"+arr[i].Template_Name+
									"</option>"+templateList+"</select></td>"+
										"<td><Button class='btn btn-info'"+
											" onclick='updateTemplate(\""+i+"\",\""+arr[i].tab_id+
											"\",\""+arr[i].OrganisationUnit+
											"\"); return false;'>Update</Button></td>"+
										"<td><span id='update_status"+i+"' style='min-width:30px'></span></td></tr>";
										//,\""+prev_tab_name[i]+"\"
							}
							layout+="<tr><td align='center' colspan='5'>"+
											"<Button type='submit' class='btn btn-info' id='updateAll'>Update All</Button>"+
										"</td></tr></table></div>";
							document.getElementById("tabs_template_result").innerHTML="<center>"+layout+"</center>";
							
							$("#updateAll").click(function(){
								/*javascrip code for updating tab templates*/
								var j;
								for(j=0;j<arr.length;j++){
									var tab_id = arr[j].tab_id;
									var org_unit=arr[j].OrganisationUnit;
									updateTemplate(j,tab_id,org_unit);//updating template
								}
								return false;
							});
						}
					}
				});									
			}
		},
		error: function( xhr, status, errorThrown ) {
			document.getElementById("tabs_template_result").innerHTML="<center><p>Sorry, there was a problem! Try again later</P></center>";
		}
	});	
}
//function for updating template
function updateTemplate(i,tab_id,org_unit){
	var template_name = $("#template_name"+i).val();
	var tabname = $("#tab_name"+i).val();
	var previous_tab = prev_tab_name[i];
	//alert("Tab Id: "+tab_id+" Tab Name: "+tabname+"Template Name: "+template_name);
	//alert("Previous Tab Name: "+previous_tab);
	document.getElementById("update_status"+i).innerHTML="<img src='img/update_icon.gif'></img>";
	$.ajax({
		type: "POST",
		url: "updateTabs.php",
		data: "tab_id="+tab_id+"&tab_name="+tabname+"&template_name="+template_name+"&index="+i+"&ou_name="+org_unit+"&previous_tab="+previous_tab,
		success: function(result){
			var result_data = JSON.parse(result);
			if(result_data.state==true){
				document.getElementById("update_status"+result_data.index).innerHTML="<img src='img/positive.png'/>";
				arr[result_data.index].Tab_Name=tabname;
				prev_tab_name[result_data.index]=tabname;
				//alert("Index: "+result_data.index+" Last updated tab name: "+prev_tab_name[result_data.index]);
			}else
				document.getElementById("update_status"+result_data.index).innerHTML=result_data.response;
		}
	});
}
/*Automatic display for tab to template association when any of the folling event occurs*/
$(document).ready(function (){
	$('#getTabsTemplate').click(function() {
		setTabTemplateLayout();
		return false;
	});
	/*$('#orgUnitSelect').change(function(){
		getRoles("roleSelect",$("orgUnitSelect").val());
		//setTabTemplateLayout();	
		return false;
	});*/
	$('#roleSelect').change(function(){
		setTabTemplateLayout();
		return false;
	});	
	$("#sel_org_unit_role_tab").change(function(){
		getRoles("sel_roles",$("#sel_org_unit_role_tab").val());
	});
});
$(document).ready(function(){
		
		$("#orgUnitSelect").change(function(){
			getRoles("roleSelect",$("#orgUnitSelect").val());
		});
});


/* script for creating organisation*/
            $(document).ready(function (){
                $('#submit').click(function() {
                    var orgname=$("#orgname").val();
					var display_name =$("#display_name").val();
					$("#error1").css('color', 'black');
                    $("#error1").html("<img src='img/loading.gif'/> Wait a moment please...");
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
			
/*JavaScript for creating organisation unit*/
/*Validating OU name*/
function validate_ou_name(ou_name)
{
	/*var regexp1=new RegExp("[^a-z]");
	if(regexp1.test(ou_name))
	{
		document.getElementById("error2").innerHTML="<font color='red'>Only alphabets from a to z are allowed, no numbers,"+
		" no capital latters, no whitespaces</font>";
		return false;
	}
	else */
	return true;
}
$(document).ready(function (){
    $('#createorgunitbtn').click(function() {
				//alert('hh');	
            var orgunit=$("#orgunit").val();
			if(validate_ou_name(orgunit)==true){
				$("#error2").html("<div><img src='img/loading.gif'/></div> Wait Please...");
				$("#error2").css('color', 'black');
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
									viewOrgUnits("dropdown","sel_org_unit_global_tab","all");//displaying for creating global tab
									viewOrgUnits("dropdown","sel_org_unit_role_tab","all");//displaying for creating role tab
									viewOrgUnits("dropdown","orgUnitSelect","all");//displaying oOU list in associate tab to template layout
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
			}		
			return false;
    });
	$('#createorgunit').on('hidden.bs.modal', function () {
		$("#error2").text("");
	});
});
		
/* Javascript for creating roles*/
    $(document).ready(function (){
		$('#createrole').on('hidden.bs.modal', function () {
			$("#error3").text(" ");
		});
        $('#btnrole').click(function() {
			var rolaname=$("#rolaname").val();
			var ousel =$("#ousel").val();
			//var access =document.getElementById("access_yes").checked;
			//var access =$("#access_yes").val();
			var role_type=$("#roletype").val();
			if(rolaname.trim().length==0){
				$("#error3").css('color','red');
				$("#error3").html("<center>Please fill up role name.</center>");
				return false;
			}
			$("#error3").html("<center><img src='img/loading.gif'/></center>");
			$("#error3").css('color','black');
			$.ajax({
                type: "POST",
                url: "createrole.php",
                data: "rolaname="+rolaname+"&ousel="+ousel+"&role_type="+role_type,    
                success: function(e){ 
					if(e.trim()=="true")
                    {
						$("#error3").css('color','green');
						$("#error3").html("<center>Role Created</center>");
						viewOrgUnits("dropdown","OrgUnitList","all");/*this will display drop down list of 
						organisation units at the popup dialog for creating users*/
						getRoles("sel_roles",$("#sel_org_unit_role_tab").val());//to display role in Associate Role to Tab
						getRoles("roleSelect",$("#orgUnitSelect").val());//to display role at Tab to TabTemplate
                        getRoles("UserRole",$("#OrgUnitList").val()); //to display role in creating user 
						displayRoles("role_lists",$("#ousel").val());
                    }else if(e.trim()=="false"){
						$("#error3").css('color','red');
						$("#error3").html("<center>Oops Some Goes Wrong Please Try Agian</center>");
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
	
/*JavaScript for creating users*/
	$(document).ready(function(){
		$('#CreateUser').click(function(){
			var username = $("#username").val();
			var password = $("#password").val();
			var conf_pwd = $("#conf_pwd").val();
			var email = $("#email").val();
			var org_unit = $("#OrgUnitList").val();
			var user_role = $("#UserRole").val();
			var is_universal = document.getElementById("universal_access_yes").checked;//if the user has access across all other OU
			//var type = true;
			if(username.length==0){
				document.getElementById("error4").innerHTML="Username is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(password.length==0){
				document.getElementById("error4").innerHTML="Password is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(conf_pwd.length==0){
				document.getElementById("error4").innerHTML="Confirm Password is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(conf_pwd!=password){
				document.getElementById("error4").innerHTML="Confirm Password does not match with the password";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(email.length==0){
				document.getElementById("error4").innerHTML="Email is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else{
				document.getElementById("error4").innerHTML="<div><img src='img/loading.gif'/></div> Wait Please....";
				document.getElementById("error4").style.color="green";
				$.ajax({
					type: "POST",
					url: "createUsers.php",
					data:"username="+username+"&password="+password+"&conf_pwd="+conf_pwd+
							"&email="+email+"&org_unit="+org_unit+"&Role="+user_role+"&type="+is_universal,    
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
	
/*JavaScript for finding organisation */		
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
/*JavaScript for viewing list of organisation unit*/	
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
				//data: "method="+method+"&viewType="+type,
				success: function(data){
					var view=" ";
					var limit=0;				
					if(data.trim()=="error"){
						view=" ";
						document.getElementById(id).innerHTML="<tr><td>We are very sorry that an error occurs, please try again after sometime</td></tr>";
					}
					else{
						var json_arr = JSON.parse(data);
						if(type=="all")
							limit=json_arr.length;
						else {
							limit=(json_arr.length>4?4:json_arr.length);
						}
						if(method=="list"){
							for(var i=0;i<limit;i++){
								view+='<tr><td>'+json_arr[i].organisation_unit+'</td><td align="right">'+
								'<Button type="button" class="btn btn-default"'+
								' onclick="setDelAction4OrgUnit(\''+json_arr[i].id+'\',\''+json_arr[i].organisation_unit+'\')" id="del_org_unit'+i+'">'+
								'<span class="glyphicon glyphicon-trash"></span></Button></td></tr>';
							}
						}
						else if(method=="dropdown"){
							for(var i=0;i<limit;i++){
								view+="<option>"+json_arr[i].organisation_unit+"</option>";
							}
						}
					}
					document.getElementById(id).innerHTML=view;
				},
				error: function(x,y,z){
					if(method=="list"){
						document.getElementById(id).innerHTML="<tr><td>We are very sorry that an error occurs, please try again after sometime</td></tr>";
					}
				}
			});
			return false;
	}
	$(document).ready(function(){
		$("#viewAllOrgUnitLists").click(function(){
			document.getElementById("showOrgUnits").innerHTML="<center><img src='img/loading_data.gif'/></center>";
			viewOrgUnits("list","showOrgUnits","all");
		});
	});
	function setDelAction4OrgUnit(id,ou_name){
		var confirm_val = confirm("Are you sure to delete "+ou_name+"?");
		if(confirm_val==true)
		$.ajax({
			type: "POST",
			url: "delete_ou.php",
			data: "org_unit_id="+id,
			success: function(resp){
				if(resp.trim()=="false"){
					alert("Unable to delete Organisation Unit");
				}
				else{
					//viewOrgUnits("list","showOrgUnits","all");
					window.location.reload(true);
				}
			},
			error: function(x,y,z){
				alert("Oops! an error occur.");
			}
		});
	}

/*JavaScript for viewing list of organisations*/	
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
				//data: "method="+method+"&viewType="+type,
				success: function(data){
					var view=" ";
					var limit=0;				
					if(data.trim()=="error"){
						view=" ";
						document.getElementById(id).innerHTML="<tr><td>We are very sorry that an error occurs, please try again after sometime.</td></tr>";
					}
					else{
						var json_arr = JSON.parse(data);
						if(type=="all")
							limit=json_arr.length;
						else{ 
							limit=json_arr.length>4?4:json_arr.length;
						}
						if(method=="list"){
							for(var i=0;i<limit;i++){
								view+="<tr><td>"+json_arr[i].name+"</td><td align='right'>"+
								"<Button type='button' class='btn btn-default'"+
								"onclick='setDelAction4Org(\""+json_arr[i].id+"\",\""+json_arr[i].name+"\"); return false;' id='del_org"+i+"'>"+
								"<span class='glyphicon glyphicon-trash'></span></Button></td></tr>";
							}
						}
						else if(method=="dropdown"){
							for(var i=0;i<limit;i++){
								view+="<option>"+json_arr[i].name+"</option>";
							}
						}
					}
					document.getElementById(id).innerHTML=view;
				},
				error: function(x,y,z){
					if(method=="list"){
						document.getElementById(id).innerHTML="<tr><td>We are very sorry that an error occurs, please try again after sometime</td></tr>";
					}
				}
			});
			return false;
	}
	$(document).ready(function(){
		$("#viewAllOrgLists").click(function(){
			document.getElementById("showOrgsList").innerHTML="<center><img src='img/loading_data.gif'/></center>";
			viewOrgs("list","showOrgsList","all");
		});
	});
	function setDelAction4Org(id,org_name){
		var confirm_val = confirm("Are you sure to delete "+org_name+"?");
		if(confirm_val==true)
		$.ajax({
			type: "POST",
			url: "delete_org.php",
			data: "org_id="+id,
			success: function(resp){
				if(resp.trim()=="false"){
					alert("Unable to delete Organisation Unit");
				}
				else{
					viewOrgs("list","showOrgsList","all");
					viewOrgUnits("list","showOrgUnits","all");
					window.reload();
				}
			},
			error: function(x,y,z){
				alert("Oops! an error occur.");
			}
		});
	}
	
/*Javascript for creating templates*/
$(document).ready(function(){
		$("#createTemplate").click(function(){
			$("#createTemplateResponse").html("<img src='img/loading_data.gif'/>");
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
					document.getElementById(id).innerHTML=document.getElementById(id).value+e;	
				}
			});
			return false;
	}
	
	function setTemplates(existingData,method,id,type){
			$.ajax({
				type:"GET",
				url: "TemplateList.php",
				data: "method="+method+"&viewType="+type,
				success: function(e){
					document.getElementById(id).innerHTML=existingData+e;	
				}
			});
			return false;
	}
	/*Javascript function to set list of role in combo box*/
	function getRoles(id,orgunit){
			$.ajax({
				type:"GET",
				url: "getRoles.php",
				data: "org_unit="+orgunit,
				success: function(data){
					if(data.trim()=="false"){
						document.getElementById(id).innerHTML="<option></option>";
					}
					else{
						var arr = JSON.parse(data);
						var roleList=" ";
						for(var i=0;i<arr.length;i++){
							roleList+="<option>"+arr[i].RoleName+"</option>";
						}
						document.getElementById(id).innerHTML=roleList;
					}
				},
				error: function(x,y,z){
					document.getElementById(id).innerHTML="<option></option>";
					//alert("Error in connecting server. Try again later.");
				}
			});
			return false;
	}
	/*Javascript function to set list of role in div*/
	function displayRoles(id,orgunit){
			document.getElementById(id).innerHTML="<center><img src='img/loading.gif'/></center>";
			$.ajax({
				type:"GET",
				url: "getRoles.php",
				data: "org_unit="+orgunit,
				success: function(data){
					if(data.trim()=="false"){
						document.getElementById(id).innerHTML="<center>No role exists. You can create a new role by clicking the link below.</center>";
						document.getElementById(id).style.color="red";
					}
					else{
						document.getElementById(id).style.color="black";
						var arr = JSON.parse(data);
						var roleList="<div class='panel panel-info'>"+
							"<div class='panel-heading'><h1 class='panel-title'>List of existing roles:</h1></div>"+
							"<table border='0' class='table table-bordered'>"+
							"<tr><th style='background-color:#90C6F3;color:#FFFFFF'>Role Name</th>"+
							"<th style='background-color:#90C6F3;color:#FFFFFF'>Role Type</th></tr>";
						var i=0;
						for(i=0;i<arr.length;i++){
							roleList+="<tr><td>"+arr[i].RoleName+"</td><td>"+arr[i].RoleType+"</td></tr>";
							//roleList+="<div class='col-sm-4'><p>Name: "+arr[i].RoleName+"<br/>Type: "+arr[i].RoleType+"</p></div>";
						}
						roleList+="</table></div>";
						if(i>0)
							document.getElementById(id).innerHTML=roleList;
						else{
							document.getElementById(id).innerHTML="<center>No role has been created so far.</center>";
						}
					}
				},
				error: function(x,y,z){
					document.getElementById(id).innerHTML="<center>An unknown problem occurs! Try again later, please.</center>";
					document.getElementById(id).style.color="red";
				}
			});
			return false;
	}
	function getTabs(id){
		document.getElementById(id).innerHTML="<p><h1 align='center'>Wait please...</h1></p>";
		document.getElementById(id).style.color="black";
		$.ajax({
			url: "getTabs.php",
			success: function(resp){
				if(resp.trim()=="false"){
					document.getElementById(id).innerHTML="<h1>Unable to connect database<h1>";
				}
				else if(resp.trim()=="sesssion_expired"){
					document.getElementById(id).innerHTML="<h1>Oops! Session expired, Please Login again.</h1>";
				}
				else{
					var json_arr = JSON.parse(resp);
					var layout=" ";
					for(var i=0;i<json_arr.length;i++){
						var btn_class;
						if(json_arr[i].RoleId == null)
							btn_class="btn btn-warning";	
						else
							btn_class="btn btn-success";
						prev_tab_name[i] = 	json_arr[i].Name;
						
						layout+= "<tr><td>"+
						"<Button class='"+btn_class+"' onclick='associate(\""+json_arr[i].Id+"\");return false;'>"+
						"<span class='glyphicon glyphicon-chevron-left'></span></Button></td>"+
						"<td style='overflow: hidden;overflow-x: auto;max-width:120px'>"+
							"<div id='tabname"+i+"'>"+json_arr[i].Name+"</div>"+
						"</td>"+
						"<td align='right'>"+
							"<a href='#' data-toggle='popover"+i+"' type='button' id='edit_tab"+i+"'>Edit</a>"+			  		
							"<div class='container' style='width:2px'><div style='width:200px' class='hide' id='popover-content"+i+"'>"+
								"<form class='form-horizontal' role='form'>"+
									"<div>"+
										"<table>"+
											"<tr>"+
												"<td>"+
													"<input type='text' value='"+json_arr[i].Name+"'"+
														"id='updated_tab_name"+i+"' name='tab_name"+i+"' class='form-control'/>"+
												"</td>"+
												"<td>"+
													"<button type='button' class='btn btn-info'"+ 
														"onclick='updateTab(\""+i+"\",\""+json_arr[i].Id+
															"\",\""+json_arr[i].Template_Name+"\")'"+
														"id='saveTabName"+i+"'"+
														"style='float:right'>Save</button>"+
												"</td>"+
											"</tr>"+
											"<tr><td colspan='2'><span id='upadate_tab_resp"+i+"'></span></td></tr>"+
										"</table>"+
									"</div>"+
								"</form>"+
							"</div>"+
						"</td>"+
						"<td align='right'><a href='#' onclick='deleteTab(\""+json_arr[i].Id+"\")'>Delete</a></td>"+"</tr>";
						/*
						 * 
						"<td>"+
							"<div>"+json_arr[i].Template_Name+"</div>"+
						"</td>"+
						 * */
					}
					document.getElementById(id).innerHTML=layout;
					
					for(var i=0;i<json_arr.length;i++){
						$("[data-toggle='popover"+i+"']").popover({
							html: true,
							title: "Edit tab name here:",
							placement: "left", 
							content: getPopupContent(i)	
						});
					}
					
					/*
					 * $("[data-toggle='popover']").popover({
							html: true,
							title: "Update tab here",
							placement: "left", 
							content: function() {
								return $('#popover-content'+i).html();
							}		
						});
					 * */
				}
			}
		});
	}
	//getting popup content according to the index
	function getPopupContent(index){
		return $("#popover-content"+index).html();
	}
	//function to update a tab name
	function updateTab(i,tab_id,template_name){
		//alert("Tab index:"+i+" Tab Id: "+tab_id+" Template Name: "+template_name);
		document.getElementById("upadate_tab_resp"+i).innerHTML="<br/><p>Wait please...</p>";
		document.getElementById("upadate_tab_resp"+i).style.color="red";
		var new_tab_name = $("#updated_tab_name"+i).val();
		var old_tab_name = prev_tab_name[i];
		//alert(old_tab_name);
		//alert("New tab: "+tab_name);
		$.ajax({
			type: "POST",
			url: "updateTab.php",
			data: {"tab_id":tab_id,"old_tab_name":old_tab_name,"new_tab_name":new_tab_name,"template_name":template_name},
			success: function(resp){
				//alert(resp);
				var resp_arr = JSON.parse(resp);
				if(resp_arr.status==true){
					//prev_tab_name[i]=new_tab_name;
					getTabs("list_of_tabs");//refreshing the tab list
					getAssociatedTabs("associated_tabs");
				}
				else{
					alert(resp_arr.message);
					//document.getElementById("upadate_tab_resp"+i).innerHTML="<br/><p>"+resp_arr.message+"</p>";
					document.getElementById("upadate_tab_resp"+i).style.color="red";
				}
			},
			error: function(x,y,z){
				alert("Something goes wrong. Please try again later..");
			}
		});
		return false;
	}
	
	function associate(tab_id){
		
		var ou_name = $("#choose_ou").val();
		var role_name = $("#choose_role").val();
		if(ou_name.length==0){
			alert("Choose an OU");
			return;
		}
		if(role_name.length==0){
			alert("Choose a role");
			return;
		}
		//alert("Tab Id: "+tab_id);
		$.ajax({
			type: "POST",
			url: "associateTab_to_Role.php",
			data: {"ou_name":ou_name,"role_name":role_name,"tab_id":tab_id},
			success: function(resp){
				//alert(resp);
				var resp_json = JSON.parse(resp);
				if(resp_json.status==true){
					getAssociatedTabs("associated_tabs");
				}
				else{
					alert(resp_json.message);
				}
			},
			error: function(x,y,z){
				alert("Something goes wrong. Please try again later..");
			}
			
		});
	}
	
	function getAssociatedTabs(id){
		var ou_name = $("#choose_ou").val();
		var role_name = $("#choose_role").val(); 
		document.getElementById(id).innerHTML="<p><h1 align='center'>Wait please...</h1></p>";
		document.getElementById(id).style.color="#A4A4A4";
		$.ajax({
			type: "GET",
			url: "getAssociatedTabs.php",
			data: "ou_name="+ou_name+"&role_name="+role_name,
			success: function(resp){
				//alert(resp);
				if(resp=="problem"){
					alert(resp);
					document.getElementById(id).innerHTML="Something Goes Wrong!";
				}else if(resp.trim()=="null"){
					document.getElementById(id).innerHTML="<h1 align='center'>No Record Found!</h1>";
					document.getElementById(id).style.color="#FE642E";
				}else {
					var resp_array = JSON.parse(resp);
					var layout=" ";
					//alert("Length of Array: "+resp_array.length);
					for(var i=0;i<resp_array.length;i++){
						layout+="<tr><td><div class='col-sm-8'>"+
									resp_array[i].Name+"</div></td>"+
									"<td align='right'><Button class='btn btn-danger' "+
									"onclick='deleteAssociatedTab(\""+resp_array[i].Id+"\");"+
									"return false;'>"+
									"<span class='glyphicon glyphicon-minus'></span></Button></td></tr>";	
					}
					document.getElementById(id).innerHTML=layout;
				}
			}
		});
	}
	
	function deleteAssociatedTab(tab_id){
		//alert(tab_id);
		var confirmation = confirm("Are you sure to drop this tab?");
		if(confirmation){
			var ou_name = $("#choose_ou").val();
			var role_name = $("#choose_role").val();
			$.ajax({
					type: "POST",
					url: "deleteAssociatedTab.php",
					data: {"tab_id":tab_id,"ou_name":ou_name,"role_name":role_name},
					success: function(response){
						var resp_arr = JSON.parse(response);
						if(resp_arr.status==true){
							getAssociatedTabs("associated_tabs");
						}
						else {
							alert(resp_arr.message);
						}
					}
			});
		}
	}
	
	function deleteTab(tab_id){
		var confirm_var = confirm("Are you sure to delete this tab?");
		if(confirm_var){
			$.ajax({
				type: "POST",
				url: "delete_a_tab.php",
				data: {"tab_id":tab_id},
				success: function(resp){
					var resp_arr = JSON.parse(resp);
					//alert(resp);
					if(resp_arr.status==true){
						getTabs("list_of_tabs");//refreshing the tab list
						getAssociatedTabs("associated_tabs");
					}
					else{
						alert(resp_arr.message);
					}
				},
				error: function(x,y,z){
					alert("Something goes wrong...");
				}
			});
		}
		return false;
	}
	

	
	
	

