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
					//var display_name =$("#display_name").val();
					$("#error1").css('color', 'black');
                    $("#error1").html("<img src='img/loading.gif'/> Wait a moment please...");
                    $.ajax({
                        type: "POST",
                        url: "createorg.php",
                        data: "orgname="+orgname,
                    
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
	if(ou_name.trim().length==0){
		document.getElementById("error2").innerHTML="<font color='red'>Don't leave Organisation Unit name blank.</font>";
		return false;
	}
	else return true;
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
							data: "orgnamesel="+orgnamesel+"&orgunit="+orgunit,
						
							success: function(e){  
							//alert(e); 
							//"&displaynameunit="+displaynameunit+     
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
									viewOrgUnits("dropdown","choose_ou","all");//displaying oOU list in associate tab to template layout
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
			var access =document.getElementById("access_yes").checked;
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
                data: "rolaname="+rolaname+"&ousel="+ousel+"&role_type="+role_type+"&ou_specific="+access,    
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
			var user_displayname = $("#user_displayname").val();
			var username = $("#username").val();
			var password = $("#password").val();
			var conf_pwd = $("#conf_pwd").val();
			var email = $("#email").val();
			var org_unit = $("#OrgUnitList").val();
			var user_role = $("#UserRole").val();
			var is_universal = document.getElementById("universal_access_yes").checked;//if the user has access across all other OU
			//var type = true;
			if(user_displayname.length==0){
				document.getElementById("error4").innerHTML="Give the full name";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(user_displayname.length<5){
				document.getElementById("error4").innerHTML="Full name is too short, make it at least 5 characters long.";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(username.length==0){
				document.getElementById("error4").innerHTML="Username is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(username.length<5){
				document.getElementById("error4").innerHTML="Username is too short, make it atleast 5 characters long.";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(hasWhiteSpace(username)){
				document.getElementById("error4").innerHTML="Invalid Username! No blank space is allowed in the middle of the text while entering username.";
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
					data: "user_displayname="+user_displayname+"&username="+username+"&password="+password+"&conf_pwd="+conf_pwd+
							"&email="+email+"&org_unit="+org_unit+"&Role="+user_role+"&type="+is_universal,    
					success: function(e){  
						if(e.trim()=="true")
						{
							$("#error4").css('color','green');
							$("#error4").text("User Created ");
							getAllUsers("user_display_content");//getting all users			
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
/*Javascript function to check for white spaces in a text*/
function hasWhiteSpace(s) {
  return /\s/g.test(s);
}
	
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
							view="<tr><th>Organisation Unit Name</th><th>Organisation Name</th></tr>";
							for(var i=0;i<limit;i++){
								var created_date = new Date(json_arr[i].create_at);
								var updated_date = new Date(json_arr[i].update_at);
								view+='<tr><td>'+json_arr[i].organisation_unit+'</td>'+
								'<td>'+json_arr[i].organisation+'</td>'+
								'</tr>';
								/*
								 * 
								 * 
								'<td>Date: '+created_date.getDate()+'/'+(created_date.getMonth()+1)+'/'+
									created_date.getFullYear()+
									"<br/>Time: "+getHumanReadableTime(created_date)+
								'</td>'+
								'<td align="right">'+
								'<Button type="button" class="btn btn-default"'+
								' onclick="setDelAction4OrgUnit(\''+json_arr[i].id+'\',\''+json_arr[i].organisation_unit+'\')" id="del_org_unit'+i+'">'+
								'<span class="glyphicon glyphicon-trash"></span></Button></td></tr>';
								
								 * 
								'<td>Date: '+updated_date.getDate()+'/'+(updated_date.getMonth()+1)+'/'+
									updated_date.getFullYear()+
									'<br/>Time: '+getHumanReadableTime(updated_date)+
								'</td>'+
								 * */
							}
							if(i==0)
								view="<h3 align='center' style='color:#FE642E'>"+
								"<span class='glyphicon glyphicon-alert' style='height:80px;width:80px;padding-top:40px'></span>"+
								"No Organisation exists, create a new one.</h3></div>";
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
	/*$(document).ready(function(){
		$("#viewAllOrgUnitLists").click(function(){
			document.getElementById("showOrgUnits").innerHTML="<center><img src='img/loading_data.gif'/></center>";
			viewOrgUnits("list","showOrgUnits","all");
		});
	});*/
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
					//alert(resp);
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
							view="<tr><th>Organisation Name</th></tr>";
							for(var i=0;i<limit;i++){
								var created_date = new Date(json_arr[i].create_at);
								var updated_date = new Date(json_arr[i].update_at);
								view+="<tr><td>"+json_arr[i].name+"</td>"+
								'</tr>';
							/*	
							 * '<td>Date: '+created_date.getDate()+'/'+(created_date.getMonth()+1)+'/'+
									created_date.getFullYear()+
									"<br/>Time: "+getHumanReadableTime(created_date)+
								'</td>'+
							 * "<td align='right'>"+
								"<Button type='button' class='btn btn-default'"+
								"onclick='setDelAction4Org(\""+json_arr[i].id+"\",\""+json_arr[i].name+"\"); return false;' id='del_org"+i+"'>"+
								"<span class='glyphicon glyphicon-trash'></span></Button></td></tr>";
							
							 * 
								'<td>Date: '+updated_date.getDate()+'/'+(updated_date.getMonth()+1)+'/'+
									updated_date.getFullYear()+
									"<br/>Time: "+getHumanReadableTime(updated_date)+
								'</td>'+
							 * */
							}
							if(i==0)
								view="<h3 align='center' style='color:#FE642E'>"+
								"<span class='glyphicon glyphicon-alert' style='height:80px;width:80px;padding-top:40px;color:#FE642E'></span>"+
								"No Organisation exists, create a new one.</h3></div>";
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
	/*$(document).ready(function(){
		$("#viewAllOrgLists").click(function(){
			document.getElementById("showOrgsList").innerHTML="<center><img src='img/loading_data.gif'/></center>";
			viewOrgs("list","showOrgsList","all");
		});
	});*/
	function setDelAction4Org(id,org_name){
		var confirm_val = confirm("Are you sure to delete "+org_name+"?");
		if(confirm_val==true)
		$.ajax({
			type: "POST",
			url: "delete_org.php",
			data: "org_id="+id,
			success: function(resp){
				var resp_json = JSON.parse(resp);
				if(resp_json.status==false){
					alert(resp_json.message);
				}
				else{
					//viewOrgs("list","showOrgsList","all");
					//viewOrgUnits("list","showOrgUnits","all");
					window.location.reload(true);
				}
			},
			error: function(x,y,z){
				alert("Oops! an error occur."+x+" "+y+" "+z);
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
						var roleList="<div class='panel panel-default'>"+
							"<div class='panel-heading'><h1 class='panel-title'>List of existing roles:</h1></div>"+
							"<table border='0' style='max-height:160px;overflow: hidden;overflow-y: auto;' "+
									"class='table table-bordered'>"+
							"<tr>"+
								"<th>Role Name</th>"+
								"<th>Role Type</th>"+
								"<th>OU Specific</th>"+
							"</tr>";
						var i=0;
						for(i=0;i<arr.length;i++){
							var ou_specific=" ";
							if(arr[i].UniversalRole.trim()=="true"){
								ou_specific="Yes";
							}
							else{
								ou_specific="No";
							}
							roleList+="<tr><td>"+arr[i].RoleName+
								"</td>"+
								"<td>"+arr[i].RoleType+"</td>"+
								"<td>"+ou_specific+"</td>"+
							"</tr>";
							//roleList+="<div class='col-sm-4'><p>Name: "+arr[i].RoleName+"<br/>Type: "+arr[i].RoleType+"</p></div>";
						}
						roleList+="</table></div>";
						document.getElementById(id).innerHTML=roleList;
						/*
						 "<div class='pull-right'>"+
								"<Button type='button' data-toggle='editRolePopup"+i+"' id='editRole"+i+"' class='btn btn-link'>"+
									"<span class='glyphicon glyphicon-pencil'></span>"+
								"</Button>"+
									"<div class='container' style='width:2px'>"+
										"<div class='hide' id='edit_role_popover"+i+"'>"+
											"<form class='form-horizontal' role='form'>"+	
												"<label for='rolename"+i+"'>Role Name</label>"+
												"<input type='text' class='form-control'"+
																	" id='rolename"+i+"' value='"+arr[i].RoleName+"'/>"+
												"<div class='pull-right' style='padding:10px'>"+
													"<button type='button' class='btn btn-info'"+
													" id='save_role"+i+"'>Save</button></div><br/>"+
												"<center><span id='updateRoleResp"+i+"'></span></center>"+
											"</form>"+	
										"</div>"+
									"</div></div>"+
						 * 
						 * for(var j=0;j<arr.length;j++){
							$("#editRole"+j).popover({
								html: true,
								title: "Edit Role here",
								placement: "left", 
								content: getEditRolePopupContent(j)
							});
						}*/
					}
				},
				error: function(x,y,z){
					document.getElementById(id).innerHTML="<center>An unknown problem occurs! Try again later, please.</center>";
					document.getElementById(id).style.color="red";
				}
			});
			return false;
	}
	
	function getEditRoleHeader(){
		return $("#edit_role_header").html();
	}
	
	function getEditRolePopupContent(index){
		return $("#edit_role_popover"+index).html();
	}
	
	function getTabs(id){
		document.getElementById(id).innerHTML="<p><h1 align='center'>Wait please...</h1></p>";
		document.getElementById(id).style.color="#A4A4A4";
		
		var ou = document.getElementById("choose_ou2").value;
		var role_name = document.getElementById("choose_role2").value;
		//alert(ou);
		$.ajax({
			url: "getTabs.php",
			type: "GET",
			data: {"ou":ou,"role_name":role_name},
			success: function(resp){
				if(resp.trim()=="false"){
					document.getElementById(id).innerHTML="<h1>Unable to connect database<h1>";
				}
				else if(resp.trim()=="sesssion_expired!"){
					document.getElementById(id).innerHTML="<h1>Oops! Session expired, Please Login again.</h1>";
				}
				else if(resp.trim()=="null"){
					document.getElementById(id).innerHTML="<br/><div>"+
					"<h1 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
					"<br/>No tab exists for this role</h1></div>";
					document.getElementById(id).style.color="#FE642E";
				}
				else{
					var json_arr = JSON.parse(resp);
					var layout=" ";
					var popup_content_form=" ";
					var i;
					for(i=0;i<json_arr.length;i++){
						var btn_class;
						var OU = json_arr[i].OU;
						var RoleName = json_arr[i].RoleName;
						var ou_specific=" ";
						if(parseInt(json_arr[i].OU_Specific) == 0){
							btn_class="btn btn-warning";
							ou_specific="No";
						}	
						else{
							btn_class="btn btn-success";
							ou_specific="Yes";
						}
						prev_tab_name[i] = 	json_arr[i].Name;
						if(json_arr[i].Template_Name=="Chat Template"){
							popup_content_form="<form class='form-horizontal' role='form'"+
								"style='max-width:300px;min-width:250px'>"+
									"<div>"+
											"<label>Tab Name</label>"+
											"<input type='text' value='"+json_arr[i].Name+"'"+
											"id='updated_tab_name"+i+"' name='tab_name"+i+"' class='form-control'/>"+
													
									"</div>"+
									"<div><br/>"+
											"<button type='button' class='btn btn-info'"+ 
														"onclick='updateTab(\""+i+"\",\""+json_arr[i].Id+
															"\",\""+json_arr[i].Template_Name+"\")'"+
														"id='saveTabName"+i+"'"+
														"style='float:right'>Save</button>"+
									"</div>"+
									"<div style='height:50px'>"+
										"<br/><span id='upadate_tab_resp"+i+"'></span>"+
									"</div>"+
								"</form>";
						}
						else{
							popup_content_form="<form class='form-horizontal' role='form'>"+
								"<div>"+
									"<label>Tab Name</label>"+
										"<input type='text' value='"+json_arr[i].Name+"'"+
											"id='updated_tab_name"+i+"' name='tab_name"+i+"' class='form-control'/>"+
								"</div>"+
								"<div>"+
									"<label>Contents</label>"+
									"<textarea rows='4' cols='50' class='form-control'>"+
										"Paste your html contents here"+
									"</textarea>"+
								"</div>"+
								"<div><br/>"+
									"<button type='button' class='btn btn-info'"+ 
											"onclick='updateTab(\""+i+"\",\""+json_arr[i].Id+
															"\",\""+json_arr[i].Template_Name+"\")'"+
											"id='saveTabName"+i+"'"+
											"style='float:right'>Save</button>"+
								"</div>"+
								"<div style='height:50px'>"+
									"<br/><span id='upadate_tab_resp"+i+"'></span>"+
								"</div>"+
							"</form>";
						}
						layout+= "<tr><td>"+
						"<Button style='width: 40px;height: 40px;border-radius: 50%;' "+
						"class='"+btn_class+"' onclick='associate(\""+json_arr[i].Id+"\");return false;'>"+
						"<span class='glyphicon glyphicon-chevron-left'></span></Button></td>"+
						"<td>"+
							"<div id='tabname"+i+"'>"+json_arr[i].Name+"</div>"+
							"<div><b>OU:</b> "+OU+"<br/><b>Role:</b> "+RoleName+
							"<br/><b>Template:</b> "+json_arr[i].Template_Name+
							"<br/><b>OU Specific:</b> "+ou_specific+
							"</div>"+
						"</td>"+
						"<td align='right'>"+
							"<Button class='btn btn-link' style='height: 40px;' data-toggle='popover"+i+"' type='button' id='edit_tab"+i+"'>"+
							"<span class='glyphicon glyphicon-pencil'></span></Button>"+			  		
							"<div class='container' style='width:20px'>"+
								"<div class='hide' style='max-width:300px;min-width:250px' id='popover-content"+i+"'>"+
									popup_content_form+
								"</div>"+
							"</div>"+
						"</td>"+
						"<td align='right'>"+
								"<Button type='button' style='height: 40px;' class='btn btn-link' onclick='deleteTab(\""+json_arr[i].Id+"\")'>"+
								"<span class='glyphicon glyphicon-remove'></span></Button>"+
						"</td></tr>";
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
							title: "Edit tab here:",
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
		document.getElementById("upadate_tab_resp"+i).style.color="#A4A4A4";
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
					//alert(resp_arr.message);
					document.getElementById("upadate_tab_resp"+i).innerHTML="<br/><center><p>"+resp_arr.message+"</p></center>";
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
					document.getElementById(id).innerHTML="<br/><div>"+
					"<h1 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span><br/>No Record Found</h1></div>";
					document.getElementById(id).style.color="#FE642E";
				}else {
					var resp_array = JSON.parse(resp);
					var layout=" ";
					var ou_specific=" ";
					var btn_class=" ";
					//alert("Length of Array: "+resp_array.length);
					for(var i=0;i<resp_array.length;i++){
						if(parseInt(resp_array[i].OU_Specific)==0){
							ou_specific="No";
							btn_class="btn btn-warning";
						}else{
							ou_specific="Yes";
							btn_class="btn btn-success";
						}
						layout+="<tr><td valign='middle'><div>"+
									resp_array[i].Name+"</div>"+
									"<div><b>OU:</b> "+resp_array[i].OU+
									"<br/><b>Role:</b> "+resp_array[i].RoleName+
									"<br/><b>Template:</b> "+resp_array[i].Template_Name+
									"<br/><b>OU Specific:</b> "+ou_specific+
									"</div>"+
									"</td>"+
									"<td align='right' ><Button type='button'"+
									"style='width: 40px;height: 40px;border-radius: 50%;'"+
									"class='"+btn_class+"' onclick='deleteAssociatedTab(\""+resp_array[i].Id+"\");"+
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
		var confirmation = true;//confirm("Are you sure to drop this tab?");
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
	
	/* Javascript function to get All users */
	function getAllUsers(display_id){
		document.getElementById(display_id).innerHTML="<center><p>Wait Please...</p></center>";
		$.ajax({
			type: "GET",
			url: "getAllUsers.php",
			success: function(response){
				if(response.trim()=="error"){
					document.getElementById(display_id).innerHTML="<p>Oops! something goes wrong, please try again later.";
				}
				else if(response.trim()=="null"){
					document.getElementById(display_id).innerHTML="<center><p>Oops! No record found.</p></center>";
				}
				else{
					displayUsers(display_id,response);	
				}
			},
			error: function(x,y,z){
				document.getElementById(display_id).innerHTML="<p>Oops! The requested resource is not found at server, please try again later.";
			}
		});
		return false;
	}
	
	//javascript function to find users
	function findUsers(display_id,username){
		$.ajax({
			type: "GET",
			url: "findaUser.php",
			data: {"user_name":username},
			success: function(response){
				//alert(response);
				if(response.trim()=="error"){
					document.getElementById(display_id).innerHTML="<p>Oops! something goes wrong, please try again later.";
				}else if(response.trim()=="null"){
					document.getElementById(display_id).innerHTML="<center><p>Oops! No record found.</p></center>";
				}
				else{
					displayUsers(display_id,response);	
				}
			},
			error: function(x,y,z){
				document.getElementById(display_id).innerHTML="<p>Oops! Something is wrong.";
			}
		});
	}
	
	//function to display list of users
	function displayUsers(display_id,data){
		var resp_arr = JSON.parse(data);
		//alert(resp_arr.length);
		if(resp_arr.length==0){
			document.getElementById(display_id).innerHTML="<h1 align='center'>No user found</h1>";
		}
		else{
			var layout="<table class='table' width='100%'"+
					"style='overflow:hidden;"+
						"min-width:120px;"+ 
						"overflow-x:auto;overflow-y:auto;'"+
				">";
			var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			for(var i=0;i<resp_arr.length;i++){
				var created_date = new Date(parseFloat(resp_arr[i].CreateAt));
				var updated_date = new Date(parseFloat(resp_arr[i].UpdateAt));
				layout+="<tr>"+
							"<td width='10%'>"+
								"<img src='img/user.png' class='circular' alt='No profile Image found'/>"+
							"</td>"+
							"<td width='60%'>"+
								"<form class='form-horizontal'>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>ID : </label>"+
										"<div class='col-sm-4'><div>"+resp_arr[i].Id+"</div></div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Display Name : </label>"+
										"<div class='col-sm-4' id='user_display_name"+i+"'>"+resp_arr[i].FirstName+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Username : </label>"+
										"<div class='col-sm-4' id='user_name"+i+"'>"+resp_arr[i].Username+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Email : </label>"+
										"<div class='col-sm-4' id='user_email"+i+"'>"+resp_arr[i].Email+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Role : </label>"+
										"<div class='col-sm-4'>"+resp_arr[i].Roles+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Organisation Unit : </label>"+
										"<div class='col-sm-4'>"+resp_arr[i].OrganisationUnit+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Organisation Name : </label>"+
										"<div class='col-sm-4'>"+resp_arr[i].Organisation+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Has access across all other OU : </label>"+
										"<div class='col-sm-4'>"+yesOrNo(resp_arr[i].UniversalAccess)+"</div>"+
									"</div>"+
								"</form>"+
							"</td>"+
							"<td>"+
								"<div>"+
								"<Button type='button' id='edit_user"+i+"' data-toggle='popover_edit_user"+i+"' class='btn btn-sm btn-link btn-block'>"+
								"<span class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp"+
								"Edit</Button></div>"+
								"<div class='hide' id='edit_user_popover"+i+"'>"+
									"<form style='max-width:250px;min-width:200px;max-height:270px'>"+
										"<div class='form-group'>"+
											"<label>Display Name : </label>"+
											"<div><input type='text' id='update_display_name"+i+"' class='form-control' value='"+resp_arr[i].FirstName+"'/></div>"+
										"</div>"+
										"<div class='form-group'>"+
											"<label>Username : </label>"+
											"<div><input type='text' id='update_username"+i+"' class='form-control' value='"+resp_arr[i].Username+"'/></div>"+
										"</div>"+
										"<div class='form-group'>"+
											"<label>Email : </label>"+
											"<div><input type='email' id='update_email"+i+"' class='form-control' value='"+resp_arr[i].Email+"'/></div>"+
										"</div>"+
										"<div class='form-group'>"+
											"<center>"+
											"<Button type='button' class='btn btn-default' onclick='cancel_update_user(\""+i+"\");' id='cancel_update"+i+"'>Close</Button>"+
											"&nbsp;&nbsp;<Button type='button' class='btn btn-default'"+
											" id='update_user"+i+"' onclick='update_user(\""+i+"\",\""+resp_arr[i].Id+"\")'>Update</Button>"+
											"</center>"+
										"</div>"+
										"<center><div class='form-group' style='min-height:20px' id='update_user_resp"+i+"'></div></center>"+
									"</form>"+
								"</div>"+
							"</td>"+
						"</tr>";
						/*
						 * 
							"<td align='right'>"+
								"<div>"+
								"<label><b>Created on :</b></label> "+created_date.getDate()+" - "+months[created_date.getMonth()]+" - "+
															created_date.getFullYear()+"<br/>"+
								"<label><b>Time: </b>&nbsp;</label>"+getHumanReadableTime(created_date)+"<br/>"+
								"<label><b>Last updated on:</b></label> "+updated_date.getDate()+" - "+months[updated_date.getMonth()]+" - "+
															updated_date.getFullYear()+"<br/>"+
								"<label><b>Time: </b>&nbsp;</label>"+getHumanReadableTime(updated_date)+"<br/>"+
								"</div>"+
							"</td>"+
						 * */
			}
			layout+="</table>";
			document.getElementById(display_id).innerHTML=layout;
			
			for(var i=0;i<resp_arr.length;i++){
				$("#edit_user"+i).popover({
					html: true,
					title: "Edit user here:",
					placement: "bottom", 
					content: getEditUserContent(i)
				});
				
			}
		}
	}
	function cancel_update_user(index){
		//$("#edit_user"+i).popover("hide");
		$("[data-toggle='popover_edit_user"+index+"']").popover('hide');
	}
	
	function update_user(index,user_id){
		//alert("Index: "+index+" User ID: "+user_id);
		$("#update_user_resp"+index).html("Wait Please...");
		var display_name = $("#update_display_name"+index).val();
		var username = $("#update_username"+index).val();
		var email = $("#update_email"+index).val();
		$.ajax({
			type: "POST",
			url: "updateUser.php",
			data: {"user_id":user_id,"display_name":display_name,"username":username,"email":email},
			success: function(resp){
				var json_resp = JSON.parse(resp);
				if(json_resp.state==true){
					$("#user_display_name"+index).html(display_name);
					$("#user_name"+index).html(username);
					$("#user_email"+index).html(email);
				}
				$("#update_user_resp"+index).html(json_resp.message);
				//alert(resp);
			},
			error: function(x,y,z){
				$("#update_user_resp"+index).html("Oops! Something is wrong, pleae try again later.");
			}
		});
	}
	
	//function to get edit user content
	function getEditUserContent(index){
		return $("#edit_user_popover"+index).html();
	}
	
	//function to return yes/no according to 0 and 1
	function yesOrNo(val){
		if(parseInt(val)==0)
			return "No";
		else
			return "Yes";
	}
	
	//get human readable time
	function getHumanReadableTime(date){
		var hour;
		var min;
		var sec;
		var shift;
		if(date.getHours()>12){
			hour = date.getHours()-12;
			shift = "P.M.";
		}
		else{
			hour = date.getHours();
			shift = "A.M.";
		}
		min = date.getMinutes();
		sec = date.getSeconds();
		return (hour+":"+min+":"+sec+" "+shift);
	}
	

	
	
	

