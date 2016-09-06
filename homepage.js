/*code for displaying modals verically centered*/
var modalVerticalCenterClass = ".modal";
var user_list; //list of users
var IP="128.199.111.18";//server IP
/*displaying modals vertically center*/
function centerModals($element) {
    var $modals;
    if ($element.length) {
        $modals = $element;
    } else {
        $modals = $(modalVerticalCenterClass + ':visible');
    }
    $modals.each( function(i) {
        var $clone = $(this).clone().css('display', 'block').appendTo('body');
        var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
        top = top > 0 ? top : 0;
        $clone.remove();
        $(this).find('.modal-content').css("margin-top", top);
    });
}

$(document).ready(function(){		
	$(modalVerticalCenterClass).on('show.bs.modal', function(e) {
		centerModals($(this));
	});
	$(window).on('resize', centerModals);
});

/*JavaScript function for setting template to a layout by giving Ids*/
function setTemplateList(id){	
	$.ajax({
		url: "TemplateList.php",
		success: function(templates_arr) {
			if(templates_arr=="error" || templates_arr=="false"){
				document.getElementById(id).innerHTML="";
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

//refresh function
function refresh_all_entries(){
	/*refershing entries for creating organisation*/
	document.getElementById("error1").innerHTML="";
	document.getElementById("orgname").value="";
	
	/*refreshing entries for creating OU*/
	document.getElementById("error2").innerHTML="";
	document.getElementById("orgunit").value="";
	
	/*refreshing entries for creating role*/
	document.getElementById("error3").innerHTML="";
	document.getElementById("rolaname").value="";
	
	/*refreshing entries for creating users*/
	document.getElementById("error4").innerHTML="";
	document.getElementById("user_displayname").value="";
	document.getElementById("username").value="";
	document.getElementById("password").value="";
	document.getElementById("conf_pwd").value="";
	document.getElementById("email").value="";
	
	/*refreshing entries for creating tab*/
	document.getElementById("createTabResponse").innerHTML="";
	document.getElementById("tab_name").value="";
	
	/*refreshing entries for creating tabstrip*/
	document.getElementById("createTabstripResponse").innerHTML="";
	document.getElementById("createTabstripResponse").style.color="black";
	document.getElementById("tabstrip_name").innerHTML="";
}
function getRoleId(role_name,role_list){
	var role_id=null;
	for(var i=0;i<role_list.length;i++){
		if((role_list[i].RoleName).trim()==role_name)
		{
			role_id=role_list[i].Id;
			break;
		}
	}
	return role_id;
}
$(document).ready(function(){
		//If the tab to be created is OU specific
		viewOrgUnits("dropdown","ou_selector","all");
			
		$("#ou_selector").change(function(){
			getRoles("role_selector",$("#ou_selector").val(),"createTabResponse");
		});
				
		//javascript for creating tab
		$('#createTab').click(function(){
			createTab();
		});
});
/*function to create a tab*/
function createTab(){
			document.getElementById("createTabResponse").innerHTML="<center>Wait please....</center>";
			document.getElementById("createTabResponse").style.color="black";
			var tab_name = ($("#tab_name").val()).trim();
			var template_name = ($("#choose_templates").val()).trim();
			var ou_specific = document.getElementById("ou_specific_yes").checked;
			var post_data;//data to be posted
			var org_name=($("#choose_org").val()).trim();
			var ou_name = ($("#ou_selector").val()).trim();
			var role_name = ($("#role_selector").val()).trim();
			var role_id = getRoleId(role_name,role_list);
			var token=user_session.token;
			
			if(tab_name.length==0){
				document.getElementById("createTabResponse").innerHTML="<center><b>Dont leave tab name blank.</b></center>";
				document.getElementById("createTabResponse").style.color="red";
				return false;
			}	
			
			if(org_name==null || org_name.length==0){
				document.getElementById("createTabResponse").innerHTML="<center>Select an organisation.</center>";
				document.getElementById("createTabResponse").style.color="red";
				return false;
			}
				
		if(document.getElementById("ou_specific_yes").checked==false && document.getElementById("ou_specific_no").checked==false){
			document.getElementById("createTabResponse").innerHTML="<center><b>Please select whether OU specific or not.</b></center>";
			document.getElementById("createTabResponse").style.color="red";
			return false;
		}					
		
			if(role_name==null || role_name.length==0){
				document.getElementById("createTabResponse").innerHTML="<center>Select a role.</center>";
				document.getElementById("createTabResponse").style.color="red";
				return false;
			}
			if(role_id==null){
				document.getElementById("createTabResponse").innerHTML="<center>Invalid Role! Select another role.</center>";
				document.getElementById("createTabResponse").style.color="red";
				return false;
			}
			$.ajax({
						type: "POST",
						url: "createTab.php",
						data: {"tab_name":tab_name,"template_name":template_name,"ou_specific":ou_specific,"org_name":org_name,
						"ou_name":ou_name,"role_name":role_name,"role_id":role_id,"token":token},
						beforeSend: function (xhr) {
							xhr.setRequestHeader('Authorization',user_session.token);
						},
						success: function(resp){
							
							var resp_arr = JSON.parse(resp);
							if(resp_arr.status==true){
								getAllTabs("get_all_tabs");
								document.getElementById("createTabResponse").innerHTML="<center><b>"+
									resp_arr.message+"</b></center>";
								document.getElementById("createTabResponse").style.color="green";
								validate_and_get_tabs();
								getAssociatedTabs("associated_tabs");
								
								
							}
							else{
								document.getElementById("createTabResponse").innerHTML="<center>"+resp_arr.message+"</center>";
								document.getElementById("createTabResponse").style.color="red";
							}
						}
			});
	return false;
}

/* script for creating organisation*/
            $(document).ready(function (){
                $('#submit').click(function() {
                    var orgname=$("#orgname").val();
					
					$("#error1").css('color', 'black');
                    $("#error1").html("<img src='img/loading.gif'/> Wait a moment please...");
                    if(orgname.length==0){
						$("#error1").css('color', 'red');
						$("#error1").html("<center><b>Don't leave Organisation name blank.</b></center>");
						return false;
					}
					else if(orgname.length<3){
						$("#error1").css('color', 'red');
						$("#error1").html("<center><b>Organisation name is too short,name must be at least 3 characters length.</b></center>");
						return false;
					}
                    $.ajax({
                        type: "POST",
                        url: "createorg.php",
                        data: "orgname="+orgname,
                    
                        success: function(s){  
													  
                            if(s.trim()=="true")
                            {
								$("#error1").css('color', 'green');
								$("#error1").html("<center><b>Organisation Created</b></center>");
								viewOrgs("dropdown","orgnamesel","all");/*this will display drop down list of 
								organisation at the popup dialog for creating Organisation Units*/
								viewOrgs("list","showOrgsList","all");
                                
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
			

/*function to Validate OU name*/
function validate_ou_name(ou_name)
{
	if(ou_name.trim().length==0){
		document.getElementById("error2").innerHTML="<font color='red'><strong>Don't leave Organisation Unit name blank.</strong></font>";
		return false;
	}
	else return true;
}

/*JavaScript for creating organisation unit*/
$(document).ready(function (){
    $('#createorgunitbtn').click(function() {
				//alert('hh');	
            var orgunit=$("#orgunit").val();
			if(validate_ou_name(orgunit)==true){
				$("#error2").html("<div><img src='img/loading.gif'/></div> Wait Please...");
				$("#error2").css('color', 'black');
				var displaynameunit =$("#displaynameunit").val();
				var orgnamesel =($("#orgnamesel").val()).trim();
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
								if(e.trim()=="true")
								{
									$("#error2").css('color', 'green');
									$("#error2").html("<center><b>Organisation Unit Created</b></center>");
									viewOrgUnits("list","showOrgUnits","all");
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
			var rolaname=($("#rolaname").val()).trim();	
			var access =document.getElementById("access_yes").checked;
			var org_name = $("#select_org_for_role").val();
			//var access =$("#access_yes").val();
			var ousel=$("#select_ou_4_role").val();
			var post_data="";
			var role_type=$("#roletype").val();
			if(rolaname.trim().length==0){
				$("#error3").css('color','red');
				$("#error3").html("<center>Please fill up role name.</center>");
				return false;
			}
			if(ousel.trim().length==0){
				$("#error3").css('color','red');
				$("#error3").html("<center>It seems no OU exists for the selected Organisation. Create it first.</center>");
				return false;
			}
			/*if(access==true)
			{*/
				post_data = "role_name="+rolaname+"&ousel="+ousel+"&role_type="+role_type+"&ou_specific="+access+"&org_name="+org_name;
			/*}
			else{
				post_data = "role_name="+rolaname+"&role_type="+role_type+"&ou_specific="+access+"&org_name="+org_name;
			}*/
			$("#error3").html("<center><img src='img/loading.gif'/></center>");
			$("#error3").css('color','black');
			$.ajax({
                type: "POST",
                url: "createrole.php",
                data: post_data,    
                success: function(e){ 
					if(e.trim()=="true")
                    {
						$("#error3").css('color','green');
						$("#error3").html("<center>Role Created</center>");
						viewOrgUnits("dropdown","OrgUnitList","all");/*this will display drop down list of 
						organisation units at the popup dialog for creating users*/
                        getRoles("UserRole",$("#OrgUnitList").val(),"error4"); //to display role in creating user 
                        var orgunit=($("#tabstrip_ou_selector").val()).trim();
						getRoles("tabstrip_role_selector",orgunit,"createTabstripResponse");
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
			var user_displayname = ($("#user_displayname").val()).trim();
			var username = ($("#username").val()).trim();
			var password = $("#password").val();
			var conf_pwd = $("#conf_pwd").val();
			var email = $("#email").val();
			var org_unit = $("#OrgUnitList").val();
			var user_role = $("#UserRole").val();
			var team_id=user_session.team_id;
			var is_universal = document.getElementById("universal_access_yes").checked;
			
			//validating all the entries for creating users
			if(user_displayname.length==0){
				document.getElementById("error4").innerHTML="<b>Full name is blank</b>";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(user_displayname.length<5){
				document.getElementById("error4").innerHTML="<b>Full name is too short, make it at least 5 characters long.</b>";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(username.length==0){
				document.getElementById("error4").innerHTML="<b>Username is blank</b>";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(username.length<5){
				document.getElementById("error4").innerHTML="<b>Username is too short, make it atleast 5 characters long.</b>";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(hasWhiteSpace(username)){
				document.getElementById("error4").innerHTML="<b>Invalid Username! No blank space is allowed in the"+
					" middle of the text while entering username.</b>";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(password.length==0){
				document.getElementById("error4").innerHTML="Password is blank";
				document.getElementById("error4").style.color="red";
				return false;
			}
			else if(password.length<8){
				document.getElementById("error4").innerHTML="Password is too short, make it at least 8 characters long.";
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
				
				var role_id=null;
				for(var i=0;i<role_list.length;i++){
					if(role_list[i].RoleName==user_role)
					{
						role_id=role_list[i].Id;
						break;
					}
				}
				if(role_id==null){
					document.getElementById("error4").innerHTML="<b>Sorry, Invalid Role. Please select other.</b>";
					document.getElementById("error4").style.color="red";
					return false;
				}
				$.ajax({
					type: "POST",
					url: "createUsers.php",
					data: "user_displayname="+user_displayname+"&username="+username+"&password="+password+"&conf_pwd="+conf_pwd+
							"&email="+email+"&org_unit="+org_unit+"&Role="+user_role+"&role_id="+role_id+
							"&type="+is_universal+"&team_id="+team_id,    
					success: function(e){  
						if(e.trim()=="true")
						{
							$("#error4").css('color','green');
							$("#error4").text("User Created ");
							getAllUsers("user_display_content");			
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

/*javascript function to extract video id from a youtube url*/
function youtube_parser(url){
	if(is_youtube_url(url)){
		var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
		var match = url.match(regExp);
		if (match&&match[7].length==11){
			var id=match[7];
			return id;
		}else{
			return null;
		}
	}
	else {
		return null;
	}
}

/*Javascript function to check whether the url is youtube or not*/
function is_youtube_url(url){
	var reg_exp="^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.?be)\/.+$";
	return url.match(reg_exp);
}

/*Javascript to check if url is valid or not using regular expression*/
function isValidUrl(url){
	var regExp = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;//regular expression
	return regExp.test(url);
}	

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
				data: "username="+user_session.username,
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
							view=" ";
							//view="<tr><th>Organisation Unit Name</th></tr>";
							for(var i=0;i<limit;i++){
								var created_date = new Date(json_arr[i].create_at);
								var updated_date = new Date(json_arr[i].update_at);
								view+='<tr><td><div class="">'+json_arr[i].organisation_unit+'</div></td>'+
								'</tr>';
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
					document.getElementById(id).innerHTML=(view==null)?"":view;
				},
				error: function(x,y,z){
					if(method=="list"){
						document.getElementById(id).innerHTML="<tr><td>We are very sorry that an error occurs, please try again after sometime</td></tr>";
					}
				}
			});
			return false;
	}
	
	/*javascript function to get list of OUs*/
	function getOUlists(org_name,target_id){
		/* target_id is the id of layout (html tags) like div, span, select where the list is going to be displayed*/
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
					document.getElementById(target_id).innerHTML=list;
				}
				else{
					document.getElementById(target_id).innerHTML="<option></option>";
				}
				onChangeOU();// update the changes in tabstrip layout
			} 
		});										
	}
	
	/*javascript function to get list of OUs dropdown*/
	function get_list_of_OUs(org_name,target_id,error_display){
		/* target_id is the id of layout (html tags) like div, span, select where the list is going to be displayed*/
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
					document.getElementById(target_id).innerHTML=list;
					document.getElementById(error_display).innerHTML="";
					$("#error3").css('color','black');
				}
				else{
					document.getElementById(target_id).innerHTML="<option></option>";
					document.getElementById(error_display).innerHTML="<center>It seems no OU exists for the selected Organisation."+
					" Create it first.</center>";
					$("#error3").css('color','red');
				}
				onChangeOU();// update the changes in tabstrip layout
			} 
		});										
	}
	
	//javascript function to get human readable time
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
	
	//javascript function to get human readable date
	function getHumanReadableDate(created_date){
		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
		return days[created_date.getDay()]+", "+months[created_date.getMonth()]+" "+created_date.getDate()+", "+created_date.getFullYear();
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
				data: "username="+user_session.username,
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
							//view="<tr><th>Organisation Name</th></tr>";
							view=" ";
							for(var i=0;i<limit;i++){
								/*var created_date = new Date(json_arr[i].create_at);
								var updated_date = new Date(json_arr[i].update_at);*/
								view+="<tr><td><div class=''>"+json_arr[i].name+"</div></td>"+
								'</tr>';
							}
							if(limit==0)
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
	/*This will get OU list and role list under the selected OU*/
	function viewOrgListWithOUsRoles(orgListingId,ouListingId,roleListingId,resultDisplayId){
		$.ajax({
			type:"GET",
				url: "view_org_list.php",
				data: "username="+user_session.username,
				success: function(data){
					var view="";
					var limit=0;				
					if(data.trim()=="error"){
						view="<option></option>";
						document.getElementById(resultDisplayId).innerHTML="<p>We are very sorry that an error occurs, "+
							"please try again after sometime.</p>";
					}
					else
					{
						document.getElementById(resultDisplayId).innerHTML="";
						var json_arr = JSON.parse(data);
						limit=json_arr.length;
						if(limit==0){
							view="<option></option>";
							document.getElementById(resultDisplayId).innerHTML="<p><center>No organisation exists."+
								"</center></p>";
							return;
						}
						else{
							document.getElementById(resultDisplayId).innerHTML="";
						}
						for(var i=0;i<limit;i++){
							view+="<option>"+json_arr[i].name+"</option>";
						}
						document.getElementById(orgListingId).innerHTML=view;
						var org_name=($("#"+orgListingId).val()).trim();
						$.ajax({
							type: "GET",
							url: "orgUnitList.php",
							data: {"org_name":org_name},
							success: function(data){
							if(data.trim()!="null"){
								var ou_list = JSON.parse(data);
								var list=" ";
								if(ou_list.length==0){
									list="<option></option>";
									document.getElementById(resultDisplayId).innerHTML="<p><center>No organisation"+
										" unit exists for the selected organisation.</center></p>";
									return;
								}
								else{
										document.getElementById(resultDisplayId).innerHTML="";
								}
								for(var i=0;i<ou_list.length;i++){
									list+="<option>"+ou_list[i].OrganisationUnit+"</option>";
								}
								document.getElementById(ouListingId).innerHTML=list;	
							}
							else{
								document.getElementById(ouListingId).innerHTML="<option></option>";
							}
							var orgunit=($("#"+ouListingId).val()).trim();								
							$.ajax({
								type:"GET",
								url: "getRoles.php",
								data: "org="+org_name+"&org_unit="+orgunit+"&only_ou_roles=no",
								success: function(data){
									if(data.trim()=="false"){
										document.getElementById(roleListingId).innerHTML="<option></option>";
										document.getElementById(resultDisplayId).innerHTML="<center>No role exists.</center>";
										document.getElementById(resultDisplayId).style.color="red";
									}
									else{
										document.getElementById(resultDisplayId).innerHTML=" ";
										var arr = JSON.parse(data);
										role_list = JSON.parse(data);
										var roleList=" ";
										var i;
										var count=0;
										for(i=0;i<arr.length;i++){
											roleList+="<option>"+arr[i].RoleName+"</option>";
											count++;
										}
										document.getElementById(roleListingId).innerHTML=roleList;
										var  ou_specific=document.getElementById("ou_specific_tab_yes").checked;
										getTabs("list_of_tabs",ou_specific);
										getAssociatedTabs("associated_tabs");
									}
								},
								error: function(x,y,z){
									document.getElementById(roleListingId).innerHTML="<option></option>";
									document.getElementById(resultDisplayId).innerHTML="<center>Sorry! Unable to get server.</center>";
									document.getElementById(resultDisplayId).style.color="red";
								}
							});								}
						});
					}
					
				},
				error: function(x,y,z){
					document.getElementById(resultDisplayId).innerHTML="<p>We are very sorry that an error occurs, "+
						"please try again after sometime</p>";
					document.getElementById(orgListingId).innerHTML="<option></option>";
					
				}
		});
		return false;
	}
	
	/*This function will get Org list and OU list*/
	function viewOrgListWithOUs(orgListingId,ouListingId,resultDisplayId){
		$.ajax({
			type:"GET",
				url: "view_org_list.php",
				data: "username="+user_session.username,
				success: function(data){
					var view="";
					var limit=0;				
					if(data.trim()=="error"){
						view="<option></option>";
						document.getElementById(resultDisplayId).innerHTML="<p>We are very sorry that an error occurs, "+
							"please try again after sometime.</p>";
					}
					else
					{
						document.getElementById(resultDisplayId).innerHTML="";
						var json_arr = JSON.parse(data);
						limit=json_arr.length;
						if(limit==0){
							view="<option></option>";
							document.getElementById(resultDisplayId).innerHTML="<p><center>No organisation exists."+
								"</center></p>";
							return;
						}
						else{
							document.getElementById(resultDisplayId).innerHTML="";
						}
						for(var i=0;i<limit;i++){
							view+="<option>"+json_arr[i].name+"</option>";
						}
						document.getElementById(orgListingId).innerHTML=view;
						var org_name=($("#"+orgListingId).val()).trim();
						$.ajax({
							type: "GET",
							url: "orgUnitList.php",
							data: {"org_name":org_name},
							success: function(data){
								if(data.trim()!="null"){
									var ou_list = JSON.parse(data);
									var list=" ";
									if(ou_list.length==0){
										list="<option></option>";
										document.getElementById(resultDisplayId).innerHTML="<p><center>No organisation"+
											" unit exists for the selected organisation.</center></p>";
										return;
									}
									else{
											document.getElementById(resultDisplayId).innerHTML="";
									}
									for(var i=0;i<ou_list.length;i++){
										list+="<option>"+ou_list[i].OrganisationUnit+"</option>";
									}
									document.getElementById(ouListingId).innerHTML=list;	
								}
								else{
									document.getElementById(ouListingId).innerHTML="<option></option>";
									document.getElementById(resultDisplayId).innerHTML="<p>No OU exists.</p>";
									alert("No OU exists.");
								}
								onChangeOU();
							}
						});
					}
				},
				error: function(x,y,z){
					document.getElementById(resultDisplayId).innerHTML="<p>We are very sorry that an error occurs, "+
						"please try again after sometime</p>";
					document.getElementById(orgListingId).innerHTML="<option></option>";
					
				}
		});
		return false;
	}

	function getOUandRole(org_selector,ou_selector,role_selector,res_display){
		var org_name=($("#"+org_selector).val()).trim();
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
					document.getElementById(ou_selector).innerHTML=list;
				}
				else{
					document.getElementById(ou_selector).innerHTML="<option></option>";
				}
				var orgunit=($("#"+ou_selector).val()).trim();
				//getRoles(role_selector,orgunit,res_display);
				$.ajax({
					type:"GET",
					url: "getRoles.php",
					data: "org="+org_name+"&org_unit="+orgunit+"&only_ou_roles=no",
					success: function(data){
						if(data.trim()=="false"){
							document.getElementById(role_selector).innerHTML="<option></option>";
							document.getElementById(res_display).innerHTML="<center>No role exists.</center>";
							document.getElementById(res_display).style.color="red";
						}
						else{
							document.getElementById(res_display).innerHTML=" ";
							var arr = JSON.parse(data);
							var roleList=" ";
							var i;
							var count=0;
							for(i=0;i<arr.length;i++){
								roleList+="<option>"+arr[i].RoleName+"</option>";
								count++;
							}
							document.getElementById(role_selector).innerHTML=roleList;
						}
					},
					error: function(x,y,z){
						document.getElementById(role_selector).innerHTML="<option></option>";
						document.getElementById(res_display).innerHTML="<center>Sorry! Unable to get server.</center>";
						document.getElementById(res_display).style.color="red";
					}
				});
			}
		});
		return false;
	}
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
			if(template_name.length==0){
				$("#createTemplateResponse").html("<center><b>Please don't leave the template name blank</b></center>");
				return false;
			}
			$.ajax({
				type:"POST",
				url:"createTemplate.php",
				data: "template_name="+template_name+"&template="+template,
				success: function(resp){
					$("#createTemplateResponse").html("<center><b>"+resp+"</b></center>");
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
	function getRoles(id,orgunit,resp_layout){
			$.ajax({
				type:"GET",
				url: "getRoles.php",
				data: "org_unit="+orgunit+"&only_ou_roles=no",
				success: function(data){
					if(data.trim()=="false"){
						document.getElementById(id).innerHTML="<option></option>";
						document.getElementById(resp_layout).innerHTML="<center>No role exists.</center>";
						document.getElementById(resp_layout).style.color="red";
					}
					else{
						document.getElementById(resp_layout).innerHTML=" ";
						var arr = JSON.parse(data);
						role_list = JSON.parse(data);
						var roleList=" ";
						var i;
						var count=0;
						for(i=0;i<arr.length;i++){
							roleList+="<option>"+arr[i].RoleName+"</option>";
							count++;
						}
						document.getElementById(id).innerHTML=roleList;
						/*if(count==0){
							
						}
						else{
							document.getElementById(resp_layout).innerHTML=" ";
						}*/
					}
				},
				error: function(x,y,z){
					document.getElementById(id).innerHTML="<option></option>";
				}
			});
			return false;
	}
		
	/*js function to add a tab to a tabstrip*/
	function addTabToTabstrip(org_name,ou_name,ou_specific,tabstrip_id,tab_id,display_layout){
		$.ajax({
			type: "POST",
			url: "addTabToTabstrip.php",
			data:{"tab_id":tab_id,"tabstrip_id":tabstrip_id},
			success: function(resp){
				//alert(resp);
				var json_resp = JSON.parse(resp);
				if(json_resp.status==true){
					getUnaddedTab(org_name,ou_name,ou_specific,tabstrip_id,"tabs_to_be_added");
					getAddedTab(org_name,ou_name,ou_specific,tabstrip_id,"tabs_added");
				}
			}
		});
	}
	
	/*js function to remove a tab from a tabstrip*/
	function removeTabFromTabstrip(org_name,ou_name,ou_specific,tabstrip_id,tab_id,display_layout){
		$.ajax({
			type: "POST",
			url: "removeTabFromTabstrip.php",
			data:{"tab_id":tab_id,"tabstrip_id":tabstrip_id},
			success: function(resp){
				//alert(resp);
				var json_resp = JSON.parse(resp);
				if(json_resp.status==true){
					getUnaddedTab(org_name,ou_name,ou_specific,tabstrip_id,"tabs_to_be_added");
					getAddedTab(org_name,ou_name,ou_specific,tabstrip_id,"tabs_added");
				}
			}
		});
	}
	
	/*function to get available tabs to be added in the tabstrip*/
	function getUnaddedTab(org_name,ou_name,ou_specific,tabstrip_id,display_layout){
		
		$.ajax({
			type: "POST",
			url: "getUnaddedTabs.php",
			data: {"org_name":org_name,"ou_name":ou_name,"ou_specific":ou_specific,"tabstrip_id":tabstrip_id},
			success: function(resp){
				//alert(resp);
				var json_resp = JSON.parse(resp);
				if(json_resp.status==false){
					document.getElementById(display_layout).innerHTML="<div><center>"+json_resp.message+"</center></div>";
				}
				else{
					var tabs = json_resp.output;
					var view="";
					for(var i=0;i<tabs.length;i++){
						view+="<tr><td><Button class='btn btn-link'"+
						" onclick='addTabToTabstrip(\""+org_name+"\",\""+ou_name+"\",\""+
						ou_specific+"\",\""+tabstrip_id+"\",\""+tabs[i].Id+"\",\""+display_layout+"\");'>Add</Button></td>"+
						"<td><div class='col-sm-8'>"+tabs[i].Name+"</div></td></tr>";
					}
					document.getElementById(display_layout).innerHTML=view;
				}
			}
		});
	}
	
	/*function to get tabs already added in the tabstrip*/
	function getAddedTab(org_name,ou_name,ou_specific,tabstrip_id,display_layout){
		
		$.ajax({
			type: "POST",
			url: "getAddedTabs.php",
			data: {"org_name":org_name,"ou_name":ou_name,"ou_specific":ou_specific,"tabstrip_id":tabstrip_id},
			success: function(resp){
				//alert(resp);
				var json_resp = JSON.parse(resp);
				if(json_resp.status==false){
					document.getElementById(display_layout).innerHTML="<div><center>"+json_resp.message+"</center></div>";
				}
				else{
					var tabs = json_resp.output;
					var view="";
					for(var i=0;i<tabs.length;i++){
						view+="<tr><td><div class='col-sm-8'>"+tabs[i].Name+"</div></td><td><Button style='float:right' "+
						"onclick='removeTabFromTabstrip(\""+org_name+"\",\""+ou_name+"\",\""+
						ou_specific+"\",\""+tabstrip_id+"\",\""+tabs[i].Id+"\",\""+display_layout+"\");' "+
						"class='btn btn-link'>Remove</Button></td></tr>";
					}
					document.getElementById(display_layout).innerHTML=view;
				}
			}
		});
	}
	
	/* function to get list of unassociated tabs for seleted OU and Role */
	function getTabs(id,ou_specific_tab){
		document.getElementById(id).innerHTML="<p><h1 align='center'>Wait please...</h1></p>";
		document.getElementById(id).style.color="#A4A4A4";
		
		var or_name=$("#org_lists").val();
		var role_name=$("#choose_role").val();
		var role_id =  getRoleId(role_name,role_list);
		var post_data="";
		if(ou_specific_tab==false){
			if(or_name.length==0){
				document.getElementById(id).innerHTML="<p><center>No Organisation selected!</center></p>";
				return false;
			}
			if(role_name.length==0){
				document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry,No role exists for the selected OU.</h1></p>";
				document.getElementById(id).style.color="#A4A4A4";
				return false;
			}
			else if(role_id==null){
				document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry, we have an error, unable to get Role ID. Please refresh the page.</h1></p>";
				document.getElementById(id).style.color="#A4A4A4";
				return false;
			}
			else post_data={"ou_specific_tab":ou_specific_tab,"role_id":role_id,"org":or_name};
		}else{
			var ou_name=$("#choose_ou").val();
			if(ou_name==null){
				document.getElementById(id).innerHTML="<br/><div>"+
					"<h3 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
					"<br/>No OU exists for the selected Organisation!</h3></div>";
				document.getElementById(id).style.color="#FE642E";
				return false;
			}
			if(role_name==null || role_name.length==0){
				document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry,No role exists for the selected OU.</h1></p>";
				document.getElementById(id).style.color="#A4A4A4";
				return false;
			}
			else if(role_id==null){
				document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry, we have an error, unable to get Role ID. Please refresh the page.</h1></p>";
				document.getElementById(id).style.color="#A4A4A4";
				return false;
			}
			else post_data={"ou_specific_tab":ou_specific_tab,"role_id":role_id,"org":or_name,"ou":ou_name};
		}
		
		$.ajax({
			url: "getTabs.php",
			type: "GET",
			data: post_data,
			success: function(resp){
				if(resp.trim()=="false"){
					document.getElementById(id).innerHTML="<h1>Unable to connect database<h1>";
				}
				else if(resp.trim()=="sesssion_expired!"){
					document.getElementById(id).innerHTML="<h1>Oops! Session expired, Please Login again.</h1>";
				}
				else if(resp.trim()=="null"){
					document.getElementById(id).innerHTML="<br/><div>"+
					"<h3 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
					" It seems no more tab exists.</h3></div>";
					document.getElementById(id).style.color="#FE642E";
				}
				else{						
					var json_arr = JSON.parse(resp);
					var layout = "";
					//alert(json_arr.length);
					var not_ou_specific_count=0;
					var ou_specific_count=0;
					for(i=0;i<json_arr.length;i++){
						var btn_class;
						var OU = json_arr[i].OU;
						var ORG = json_arr[i].Org;
						var RoleName = json_arr[i].RoleName;
						var ou_specific=" ";
						prev_tab_name[i] = 	json_arr[i].Name;
						if(ou_specific_tab==false){
							if(parseInt(json_arr[i].OU_Specific)==0 && or_name==ORG.trim()){
								btn_class="btn btn-warning";
								ou_specific="No";
								not_ou_specific_count++;
								layout+= "<tr><td>"+
											"<Button style='width: 40px;height: 40px;border-radius: 50%;' "+
											"class='"+btn_class+"' onclick='associate(\""+json_arr[i].Id+"\");return false;'>"+
											"<span class='glyphicon glyphicon-chevron-left'></span></Button></td>"+
											"<td>"+
												"<div id='tabname"+i+"'>"+json_arr[i].Name+"</div>"+
												"<b>Template:</b> "+json_arr[i].Template_Name+
												"</div>"+
											"</td>"+
											"</tr>";
							}
						}
									
						else {
							if(parseInt(json_arr[i].OU_Specific)==1 && ou_name==OU.trim()){
								btn_class="btn btn-success";
								ou_specific="Yes";
								ou_specific_count++;
								layout+= "<tr><td>"+
											"<Button style='width: 40px;height: 40px;border-radius: 50%;' "+
											"class='"+btn_class+"' onclick='associate(\""+json_arr[i].Id+"\");return false;'>"+
											"<span class='glyphicon glyphicon-chevron-left'></span></Button></td>"+
											"<td>"+
												"<div id='tabname"+i+"'>"+json_arr[i].Name+"</div>"+
												"<b>Template:</b> "+json_arr[i].Template_Name+
												"</div>"+
											"</td>"+
											"</tr>";
							}
						}
								
					}
					document.getElementById(id).innerHTML=layout;
					if(ou_specific_tab==false){
						if(not_ou_specific_count==0){
							document.getElementById(id).innerHTML="<br/><div>"+
							"<h3 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
							"<br/>No tab or no more unassociated tab exists for the selected Organisation: <b>"+or_name+"</b>.</h3></div>";
							document.getElementById(id).style.color="#FE642E";
						}
					}else{
						if(ou_specific_count==0){
							document.getElementById(id).innerHTML="<br/><div>"+
							"<h3 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
							"<br/>No tab or no more unassociated tab exists for the selected OU: <b>"+ou_name+"</b>.</h3></div>";
							document.getElementById(id).style.color="#FE642E";
						}
					}
				}
			}
		});
	}
	
	/*function to get all tabs in details*/
	function getAllTabs(id){		
		$.ajax({
			url:"getAllTabs.php",
			type:"GET",
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization',user_session.token);
			},
			success: function(resp){
				var result = JSON.parse(resp);
				if(result.status==false){
					/*for any negative response from server*/
					document.getElementById(id).innerHTML="<br/><div>"+
						"<h1 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
						"<br/>"+result.message+"</h1></div>";
					document.getElementById(id).style.color="#FE642E";
				}
				else{
					/*for positive response from server*/
					var json_arr = result.tabs_resp;
					var layout="";
					var popup_content_form=" ";
					var see_more="";
					var i;
					for(i=0;i<json_arr.length;i++){
						var btn_class;
						var OU = json_arr[i].OU;
						var ORG = json_arr[i].Org;
						var RoleName = json_arr[i].RoleName;
						var ou_specific=" ";
						
						prev_tab_name[i] = 	json_arr[i].Name;
						if(json_arr[i].Template_Name=="Chat Template"){
							see_more = ""+
							"<div style='float:right' class='dropdown'>"+
							  "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true'"+ 
							  "aria-expanded='false'><span class='glyphicon glyphicon-option-vertical'></span></a>"+
							  "<ul class='dropdown-menu dropdown-menu-right'>"+
								"<li><a class='tools'"+
									"onclick='deleteTab(\""+json_arr[i].Id+"\")'>"+
									"Delete Tab</a></li>"+
							  "</ul>"+
							"</div>";
						}
						else if(json_arr[i].Template_Name=="Latest News Template"){
							see_more = ""+
							"<div style='float:right' class='dropdown'>"+
							  "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true'"+ 
							  "aria-expanded='false'><span class='glyphicon glyphicon-option-vertical'></span></a>"+
							  "<ul class='dropdown-menu dropdown-menu-right'>"+
								"<li>"+
									"<a class='tools'"+
										"onclick='deleteTab(\""+json_arr[i].Id+"\")'>"+
										"Delete Tab</a>"+
								"</li>"+
								"<li>"+
									"<a class='tools' href='more_about_news.php?tab_id="+json_arr[i].Id+"'>"+
									"See More</a>"+
								"</li>"+
							  "</ul>"+
							"</div>";
						}
						else{
							see_more = ""+
							"<div style='float:right' class='dropdown'>"+
							  "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true'"+ 
							  "aria-expanded='false'><span class='glyphicon glyphicon-option-vertical'></span></a>"+
							  "<ul class='dropdown-menu dropdown-menu-right'>"+
								"<li>"+
									"<a class='tools'"+
										"onclick='deleteTab(\""+json_arr[i].Id+"\")'>"+
										"Delete Tab</a>"+
								"</li>"+
								"<li>"+
									"<a class='tools' href='more_about_a_tab.php?tab_id="+json_arr[i].Id+"'>"+
									"See More</a>"+
								"</li>"+
							  "</ul>"+
							"</div>";
						}		
						layout+= "<tr>"+
								"<td width='100%'>"+
									"<div class='tab_bg'>"+see_more+
										"<div class='tab_name' id='tabname"+i+"'>"+json_arr[i].Name+"</div>"+
										"<div><strong>Organisation:</strong> "+ORG+
											"<br/><strong>Organisation Unit:</strong> "+OU+
											"<br/><strong>Template:</strong> "+json_arr[i].Template_Name+
											"<br/><strong>OU Specific:</strong> "+yesOrNo(json_arr[i].OU_Specific)+
										"</div>"+
									"</div>"+
								"</td></tr>";
					}						
					document.getElementById(id).innerHTML=layout;
				}
			},
			error:function(err_data){
				document.getElementById(id).innerHTML="<br/><div>"+
					"<h1 align='center'><span class='glyphicon glyphicon-alert' style='height:80px;width:80px'></span>"+
					"<br/>Oops! an unknown problem with the server. Please try again later.</h1></div>";
				document.getElementById(id).style.color="#FE642E";
			}
		});
	}
		
	/*Javascript function to associate a tab to a particular role*/
	function associate(tab_id){
		
		var ou_name = $("#choose_ou").val();
		
		var role_name = $("#choose_role").val();//getting role name
		var role_id = getRoleId(role_name,role_list);
		if(ou_name.length==0){
			alert("Choose an OU");
			return;
		}
		if(role_name.length==0){
			alert("Choose a role");
			return;
		}
		if(role_id==null){
			alert("Invalid role, role id does not exists, choose another role");
			return;
		}
		
		$.ajax({
			type: "POST",
			url: "associateTab_to_Role.php",
			data: {"ou_name":ou_name,
					"role_name":role_name,
					"role_id":role_id,
					"tab_id":tab_id},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization',user_session.token);
			},
			success: function(resp){
				var resp_json = JSON.parse(resp);
				if(resp_json.status==true){
					getAssociatedTabs("associated_tabs");
					var  ou_specific=document.getElementById("ou_specific_tab_yes").checked;
					getTabs("list_of_tabs",ou_specific);
				}
				else{
					alert(resp_json.message);
				}
			},
			error: function(x,y,z){
				alert("Something goes wrong. Please try again later..");
			}
		});
		return false;
	}
	
	/*function to get associated tabs for a role*/
	function getAssociatedTabs(id){
		var ou_name = ($("#choose_ou").val()).trim();
		var role_name = ($("#choose_role").val()).trim();
		var role_id =  getRoleId(role_name,role_list); /*role_list is a global js variable that stores list of roles and
		getRoleId is a function to get the id of the selected role*/
		
		if(role_name.length==0){
			document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry, No role exists for the selected OU.</h1></p>";
			document.getElementById(id).style.color="#A4A4A4";
			return false;
		}
		else if(role_id==null){
			document.getElementById(id).innerHTML="<p><h1 align='center'>Sorry, we have an error. Please refresh the page.</h1></p>";
			document.getElementById(id).style.color="#A4A4A4";
			return false;
		}
		else{
			document.getElementById(id).innerHTML="<p><h1 align='center'>Wait please...</h1></p>";
			document.getElementById(id).style.color="#A4A4A4";
			$.ajax({
				type: "POST",
				url: "getAssociatedTabs.php",
				data: "ou_name="+ou_name+"&role_name="+role_name+"&role_id="+role_id,
				success: function(resp){
					
					if(resp=="problem"){
						alert(resp);
						document.getElementById(id).innerHTML="Something Goes Wrong!";
						document.getElementById(id).style.color="#FE642E";
					}else if(resp.trim()=="null"){
						document.getElementById(id).innerHTML="<br/><div>"+
						"<h3 align='center'><span class='glyphicon glyphicon-alert' "+
						"style='height:80px;width:80px'></span>No Record Found</h3></div>";
						document.getElementById(id).style.color="#FE642E";
					}
					else 
					{
						var resp_array = JSON.parse(resp);
						var tab_layout="";
						var ou_specific="";
						var btn_class="";
						for(var i=0;i<resp_array.length;i++){
							if(parseInt(resp_array[i].OU_Specific)==0){
								ou_specific="No";
								btn_class="btn btn-warning";
								tab_layout+="<tr><td valign='middle'><div>"+
										resp_array[i].Name+"</div>"+
										"<b>Template:</b> "+resp_array[i].Template_Name+
										"</div>"+
										"</td>"+
										"<td align='right' ><Button type='button'"+
										"style='width: 40px;height: 40px;border-radius: 50%;'"+
										"class='"+btn_class+"' onclick='deleteAssociatedTab(\""+resp_array[i].Id+"\");"+
										"return false;'>"+
										"<span class='glyphicon glyphicon-minus'></span></Button></td></tr>";
							}else{
								ou_specific="Yes";
								btn_class="btn btn-success";
								tab_layout+="<tr><td valign='middle'><div>"+
										resp_array[i].Name+"</div>"+
										"<b>Template:</b> "+resp_array[i].Template_Name+
										"</div>"+
										"</td>"+
										"<td align='right' ><Button type='button'"+
										"style='width: 40px;height: 40px;border-radius: 50%;'"+
										"class='"+btn_class+"' onclick='deleteAssociatedTab(\""+resp_array[i].Id+"\");"+
										"return false;'>"+
										"<span class='glyphicon glyphicon-minus'></span></Button></td></tr>";
							}
								
						}
						document.getElementById(id).innerHTML=tab_layout;
					}
				},
				error: function(x,y,z){
					document.getElementById(id).innerHTML="Something Goes Wrong! "+z;
					document.getElementById(id).style.color="#FE642E";
				}
			});
		}
		return false;
	}
	
	/*This function will get list of tabs which has not been associated to a selected role in the Associate tab to role layout*/
	function validate_and_get_tabs(){
		var  ou_specific=document.getElementById("ou_specific_tab_yes").checked;
		/*Tabs will be listed separately for OU specific and Not OU Specific*/
		if(ou_specific==true){
			var ou_name = ($("#choose_ou").val()).trim();
			var role = $("#choose_role").val();
			if(ou_name.length==0)
			{
				$("#list_of_tabs").html("<h4><center>It seems no OU exists for "+
					"the selected Organisation.</center></h4>");
														
			}
			else if(role.length==0){
				$("#list_of_tabs").html("<h4><center>It seems no role exists for the"+
					" selected Organisation Unit.</center></h4>");
			}
			else{
				getTabs("list_of_tabs",ou_specific);
			}
		}
		else if(ou_specific==false){
			var org = $("#org_lists").val();
			var role = $("#choose_role").val();
			
			if(role==null){
				$("#list_of_tabs").html("<h4><center>It seems no role exists for the"+
					" selected Organisation.</center></h4>");
			}
			else{
				getTabs("list_of_tabs",ou_specific);
			}
		}
	}
	
	/*Javascript function to remove tab from associating to a role*/
	function deleteAssociatedTab(tab_id){
		
		var confirmation = true;//confirm("Are you sure to drop this tab?");
		if(confirmation==true){
			var role_name = $("#choose_role").val();
			var role_id = getRoleId(role_name,role_list);

			$.ajax({
					type: "POST",
					url: "deleteAssociatedTab.php",
					data: {"tab_id":tab_id,"role_id":role_id},
					beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization',user_session.token);
					},
					success: function(response){
						var resp_arr = JSON.parse(response);
						if(resp_arr.status==true){
							getAssociatedTabs("associated_tabs");
							validate_and_get_tabs();//this will display a list of tabs which are not associated to the selected role
						}
						else {
							alert(resp_arr.message);
						}
					},
					error: function(error){
						alert("Oops! An error occurs with the server...");
					}
			});
		}
	}
	
	/*Javascript function to delete a tab*/
	function deleteTab(tab_id){
		swal({   
			title: "Are you sure to delete this tab?", 
			text: "Once it is deleted, you will not be able to recover it!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "YES",   
			cancelButtonText: "NO",   
			closeOnConfirm: false,   
			closeOnCancel: false 
		}, 
		function(isConfirm){   
			if (isConfirm) {     
				$.ajax({
					type: "POST",
					url: "delete_a_tab.php",
					data: {"tab_id":tab_id},
					beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization',user_session.token);
					},
					success:function(resp){
						var json_resp=JSON.parse(resp);
						if(json_resp.status==true){
							swal("Deleted!", json_resp.message, "success"); 
							getAllTabs("get_all_tabs");
							var ou_specific=document.getElementById("ou_specific_tab_yes").checked;
							getTabs("list_of_tabs",ou_specific);
							getAssociatedTabs("associated_tabs");
						}
						else{
							swal("Failed!", json_resp.message, "error");
						}
					},
					error: function(x,y,z){
						swal("Failed!", "Something goes wrong...", "error");
					}
				});  
			} else {  
				swal({   
						title: "Deletion Cancelled!",  
						text: "Your tab is safe now.",   
						timer: 1000,   
						showConfirmButton: false 
				});  
			} 
		});
		return false;
	}
	
	/* Javascript function to get All users */
	function getAllUsers(display_id){
		document.getElementById(display_id).innerHTML="<center><p>Wait Please...</p></center>";
		var token=user_session.token;
		$.ajax({
			type: "GET",
			url: "getAllUsers.php",
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization',token);
			},
			success: function(response){
				var json_resp = JSON.parse(response);
				if(json_resp.status==false){
					document.getElementById(display_id).innerHTML="<center><div class='isa_error'>Oops! "+
					json_resp.message+"</div></center>";
				}
				else{
					if(json_resp.result!=null)
						displayUsers(display_id,json_resp.result);	
					else
						document.getElementById(display_id).innerHTML="<center><div class='isa_error'>"+
						"No record found</div></center>";	
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
		var token=user_session.token;
		document.getElementById(display_id).innerHTML="<center><p>Wait Please...</p></center>";
		$.ajax({
			type: "GET",
			url: "findaUser.php",
			data: {"user_name":username},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization',token);
			},
			success: function(response){
				var json_resp = JSON.parse(response);
				if(json_resp.status==false){
					document.getElementById(display_id).innerHTML="<center><div class='isa_error'>Oops! "+
					json_resp.message+"</div></center>";
				}
				else{
					if(json_resp.result!=null)
						displayUsers(display_id,json_resp.result);	
					else
						document.getElementById(display_id).innerHTML="<center><div class='isa_error'>"+
						"No record found</div></center>";
				}
			},
			error: function(x,y,z){
				document.getElementById(display_id).innerHTML="<p>Oops! Something is wrong.";
			}
		});
	}
	
	//function to display list of users
	function displayUsers(display_id,data){
		user_list = data;
		if(user_list.length==0 || user_list=="null" || user_list==null){
			document.getElementById(display_id).innerHTML="<div class='isa_info'><h1 align='center'>No user found</h1></div>";
			return false;
		}
		else{
			var layout="<table class='table'"+
					"style='overflow:hidden;"+
						"min-width:120px;"+ 
						"overflow-x:auto;overflow-y:auto;'"+
				">";
			var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			for(var i=0;i<user_list.length;i++){
				/*tools is setting for changing user data*/
				var tools = ""+
						"<div>"+
							"<div class='dropdown' style='float:right'>"+
							  "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true'"+ 
							  "aria-expanded='false'><span class='glyphicon glyphicon-option-vertical'></span></a>"+
							  "<ul class='dropdown-menu dropdown-menu-right'>"+
								"<li><a class='tools'"+
									"onclick='showDisplayNameResetLayout(\""+i+"\",\""+user_list[i].Id+"\");'>Change Display Name</a></li>"+
								"<li><a class='tools' "+
									"onclick='showUsernameResetLayout(\""+i+"\",\""+user_list[i].Id+"\");'>Change Username</a></li>"+
								"<li><a class='tools' "+
									"onclick='showPswdResetLayout(\""+i+"\",\""+user_list[i].Id+"\");'>Change Password</a></li>"+
								"<li><a class='tools' "+
									"onclick='showEmailResetLayout(\""+i+"\",\""+user_list[i].Id+"\");'>Change Email</a></li>"+
							  "</ul>"+
							"</div>"+
						"</div>";
				
				layout+="<tr>"+
							"<td width='10%'>"+
								"<div id='user_profile"+i+"'></div>"+
							"</td>"+
							"<td width='50%'>"+
								"<form class='form-horizontal'>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Display Name : </label>"+
										"<div class='col-sm-4' id='user_display_name"+i+"'>"+user_list[i].FirstName+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Username : </label>"+
										"<div class='col-sm-4' id='user_name"+i+"'>"+user_list[i].Username+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Email : </label>"+
										"<div class='col-sm-4' id='user_email"+i+"'>"+user_list[i].Email+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Organisation Unit : </label>"+
										"<div class='col-sm-4'>"+user_list[i].OrganisationUnit+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Role : </label>"+
										"<div class='col-sm-4'>"+user_list[i].Roles+"</div>"+
									"</div>"+
									"<div class='form-group'>"+
										"<label class='col-sm-4'>Access across other OUs : </label>"+
										"<div class='col-sm-4'>"+yesOrNo(user_list[i].UniversalAccess)+"</div>"+
									"</div>"+
								"</form>"+
							"</td>"+
							"<td>"+tools+
								"<div id='psw_reset_layout"+i+"'></div>"+
								"<div id='username_reset_layout"+i+"'></div>"+
								"<div id='displayname_reset_layout"+i+"'></div>"+
								"<div id='email_reset_layout"+i+"'></div>"+
							"</td>"+
						"</tr>";
			}
			layout+="</table>";
			document.getElementById(display_id).innerHTML=layout;//displaying users here
			for(var i=0;i<user_list.length;i++){
				set_profile(user_list[i].Id,"user_profile"+i);//setting profile image
			}
		}
	}
	//js function to set user profile image
	function set_profile(user_id,display_layout_id){	
		document.getElementById(display_layout_id).innerHTML="<img class='img-circle'"+
				"src='http://"+IP+":8065/api/v1/users/"+user_id+"/image' alt='No Profile Image'/>";					
	}

	/*For resetting password*/
	function showPswdResetLayout(index,user_id){
		//resp_arr is the array that contains user list
		document.getElementById("psw_reset_layout"+index).innerHTML="<br/>"+
								"<div class='div_layout' style='width:350px'>"+
									"Change Password"+
									"<button type='button' class='close' "+
											"onclick='closePswdResetLayout(\""+index+"\");'>&times;</button>"+
									"<form action='#' class='form-horizontal'>"+
										"<div class='form-group'>"+
											"<div class='col-sm-8'>"+
												"<input type='text' id='update_pswd"+index+
													"' class='form-control' value='' placeholder='Enter the new password'/></div>"+
											"<div class='col-sm-3'>"+
												"<Button type='button' class='btn' "+
													"onclick='resetPassword(\""+index+"\",\""+user_id+"\")'>Save</Button>"+
											"</div>"+
										"</div>"+
										"<center><div id='passwd_reset_resp"+index+"'>"+
											"</div></center>"+
									"</form>"+
								"</div>";
	}
	
	/* function to close password reset layout*/
	function closePswdResetLayout(index){
		document.getElementById("psw_reset_layout"+index).innerHTML="";
	}
	
	/*function to reset password*/
	function resetPassword(index,user_id){
		//alert("Wait please... "+index);user_session
		var token=user_session.token;
		var new_password=$("#update_pswd"+index).val();
		var resp_id = "passwd_reset_resp"+index;
		$("#"+resp_id).html("<p>Wait please...</p>");
		document.getElementById(resp_id).style.color="black";
		if(new_password.length==0){
			$("#"+resp_id).html("<div class='isa_error'>Password field is blank.</div>");
			document.getElementById(resp_id).style.color="red";
		}
		else if(new_password.length<8){
			$("#"+resp_id).html("<div class='isa_error'>Please make sure that the password is at "+
			"least 8 characters length.</div>");
			document.getElementById(resp_id).style.color="red";
		}
		else{
			$.ajax({
				url: "resetPassword.php",
				type: "POST",
				data: {"token":token,"user_id":user_id,"new_password":new_password},
				success: function(resp){
					//alert(resp);
					var resp_json = JSON.parse(resp);
					if(resp_json.status==true){
						$("#"+resp_id).html("<p><b>"+resp_json.message+"</b></p>");
						document.getElementById(resp_id).style.color="green";
					}
					else{
						$("#"+resp_id).html("<div class='isa_error'>"+resp_json.message+"</div>");
						//document.getElementById(resp_id).style.color="red";
					}
				},
				error: function(){
					$("#"+resp_id).html("<div class='isa_error'>Sorry, unable to get response from server.</div>");
					document.getElementById(resp_id).style.color="red";
				}
			});
		}
	}
	/*******************************************/
	
	/*Change for Username*/
	function showUsernameResetLayout(index,user_id){
		//resp_arr is the array that contains user list
		document.getElementById("username_reset_layout"+index).innerHTML="<br/>"+
								"<div class='div_layout' style='width:350px'>"+
									"Change Username"+
									"<button type='button' class='close' "+
											"onclick='closeUsernameResetLayout(\""+index+"\");'>&times;</button>"+
									"<form action='#' class='form-horizontal'>"+
										"<div class='form-group'>"+
											"<div class='col-sm-8'>"+
												"<input type='text' id='update_username"+index+
													"' class='form-control' value='' "+
													"placeholder='Enter the new username'/></div>"+
											"<div class='col-sm-3'>"+
												"<Button type='button' class='btn' "+
													"onclick='update_username(\""+index+"\",\""+user_id+"\");'>Save</Button>"+
											"</div>"+
										"</div>"+
										"<center><div id='username_reset_resp"+index+"'>"+
											"</div></center>"+
									"</form>"+
								"</div>";
	}
	function closeUsernameResetLayout(index){
		document.getElementById("username_reset_layout"+index).innerHTML="";
	}
	
	//function for updating username	
	function update_username(index,user_id){
		var token=user_session.token;
		var new_username=$("#update_username"+index).val();
		var resp_id = "username_reset_resp"+index;
		$("#"+resp_id).html("<p>Wait please...</p>");
		document.getElementById(resp_id).style.color="black";
		if(new_username.length==0){
			$("#"+resp_id).html("<div class='isa_error'>Username is blank.</div>");
		}
		else if(new_username.length<5){
			$("#"+resp_id).html("<div class='isa_error'>Please make sure that the username is at "+
			"least 5 characters length.</div>");
		}
		else{
			$.ajax({
				url: "updateUser.php",
				type:"POST",
				data: {"user_id":user_id,"username":new_username},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization',token);
				},
				success: function(resp){
					var json_resp = JSON.parse(resp);
					if(json_resp.status==true){
						document.getElementById("user_name"+index).innerHTML=new_username;
						$("#"+resp_id).html("<div class='isa_success'>"+json_resp.message+"</div>");
					}
					else{
						$("#"+resp_id).html("<div class='isa_error'>"+json_resp.message+"</div>");
					}
				},
				error: function(x,y,z){
					$("#"+resp_id).html("<div class='isa_error'>"+
							"Request could not be fulfilled due to server error or "+
							"requested resource is not found or not working well."+
								+"</div>");
				}
			});
		}
	}
	/**********************************/
		
	/*Change for display name*/
	function showDisplayNameResetLayout(index,user_id){
		//resp_arr is the array that contains user list
		document.getElementById("displayname_reset_layout"+index).innerHTML="<br/>"+
								"<div class='div_layout' style='width:350px'>"+
									"Change Display Name"+
									"<button type='button' class='close' "+
											"onclick='closeDisplayResetLayout(\""+index+"\");'>&times;</button>"+
									"<form action='#' class='form-horizontal'>"+
										"<div class='form-group'>"+
											"<div class='col-sm-8'>"+
												"<input type='text' id='update_display_name"+index+
													"' class='form-control' value='' "+
													"placeholder='Enter the new display name'/></div>"+
											"<div class='col-sm-3'>"+
												"<Button type='button' class='btn' "+
													" onclick='update_displayname(\""+index+"\",\""+user_id+"\");'>Save</Button>"+
											"</div>"+
										"</div>"+
										"<center><div id='displayname_reset_resp"+index+"'>"+
											"</div></center>"+
									"</form>"+
								"</div>";
	}
	function closeDisplayResetLayout(index){
		document.getElementById("displayname_reset_layout"+index).innerHTML="";
	}
	//function for updating display name
	function update_displayname(index,user_id){
		var token=user_session.token;
		var new_display_name=$("#update_display_name"+index).val();
		var resp_id = "displayname_reset_resp"+index;
		$("#"+resp_id).html("<p>Wait please...</p>");
		document.getElementById(resp_id).style.color="black";
		if(new_display_name.trim().length==0){
			$("#"+resp_id).html("<div class='isa_error'>User display name is blank.</div>");
		}
		else if(new_display_name.length<3){
			$("#"+resp_id).html("<div class='isa_error'>Please make sure that display name is at least 3 characters length.</div>");
		}
		else{
			$.ajax({
				url: "updateUser.php",
				type:"POST",
				data: {"user_id":user_id,"display_name":new_display_name},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization',token);
				},
				success: function(resp){
					var json_resp = JSON.parse(resp);
					if(json_resp.status==true){
						$("#"+resp_id).html("<div class='isa_success'>"+json_resp.message+"</div>");
						$("#user_display_name"+index).html(new_display_name);
					}
					else{
						$("#"+resp_id).html("<div class='isa_error'>"+json_resp.message+"</div>");
					}
				},
				error: function(x,y,z){
					$("#"+resp_id).html("<div class='isa_error'>"+
							"Request could not be fulfilled due to server error or "+
							"requested resource is not found or not working well."+
								+"</div>");
				}
			});
		}
	}
	/********************************/
	
	/*Change for user email*/
	function showEmailResetLayout(index,user_id){
		//resp_arr is the array that contains user list
		document.getElementById("email_reset_layout"+index).innerHTML="<br/>"+
								"<div class='div_layout' style='width:350px'>"+
									"Change email"+
									"<button type='button' class='close' "+
											"onclick='closeEmailResetLayout(\""+index+"\");'>&times;</button>"+
									"<form action='#' class='form-horizontal'>"+
										"<div class='form-group'>"+
											"<div class='col-sm-8'>"+
												"<input type='email' id='update_email"+index+
													"' class='form-control' value='' "+
													"placeholder='Enter a new email'/></div>"+
											"<div class='col-sm-3'>"+
												"<Button type='submit' class='btn' "+
													" onclick='update_email(\""+index+"\",\""+user_id+"\");return false;'>Save</Button>"+
											"</div>"+
										"</div>"+
										"<center><div id='email_reset_resp"+index+"'>"+
											"</div></center>"+
									"</form>"+
								"</div>";
	}
	function closeEmailResetLayout(index){
		document.getElementById("email_reset_layout"+index).innerHTML="";
	}
	//function for updating display name
	function update_email(index,user_id){
		var token=user_session.token;
		var update_email=$("#update_email"+index).val();
		var resp_id = "email_reset_resp"+index;
		$("#"+resp_id).html("<p>Wait please...</p>");
		document.getElementById(resp_id).style.color="black";
		if(update_email.length==0){
			$("#"+resp_id).html("<div class='isa_error'>Email is blank.</div>");
		}
		else if(isValidateEmail(update_email)){
			$.ajax({
				url: "updateUser.php",
				type:"POST",
				data: {"user_id":user_id,"email":update_email},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization',token);
				},
				success: function(resp){
					var json_resp = JSON.parse(resp);
					if(json_resp.status==true){
						$("#"+resp_id).html("<div class='isa_success'>"+json_resp.message+"</div>");
						$("#user_email"+index).html(update_email);
					}
					else{
						$("#"+resp_id).html("<div class='isa_error'>"+json_resp.message+"</div>");
					}
				},
				error: function(x,y,z){
					$("#"+resp_id).html("<div class='isa_error'>"+
							"Request could not be fulfilled due to server error or "+
							"requested resource is not found or not working well."+
								+"</div>");
				}
			});
		}
		else{
			$("#"+resp_id).html("<div class='isa_error'>Not a valid email</div>");
		}
	}
	
	/**********************************/
	
	//function to return yes/no according to 0 and 1
	function yesOrNo(val){
		if(parseInt(val)==0)
			return "No";
		else
			return "Yes";
	}
	
	/*function to create tabstrips*/
	function createTabstrip(){
		document.getElementById("createTabstripResponse").innerHTML="<center>Wait Please...</center>";
		document.getElementById("createTabstripResponse").style.color="black";
		var tabstrip_name = $("#tabstrip_name").val();
		tabstrip_name=tabstrip_name.trim();
		if(tabstrip_name==null || tabstrip_name.length==0){
			document.getElementById("createTabstripResponse").innerHTML="<center>Tabstrip name is blank.</center>";
			document.getElementById("createTabstripResponse").style.color="red";
			return false;
		}
		var org_name = $("#choose_org_tabstrip").val();
		if(org_name==null || org_name.length==0){
			document.getElementById("createTabstripResponse").innerHTML="<center>Select an organisation name</center>";
			document.getElementById("createTabstripResponse").style.color="red";
			return false;
		}
		var org_unit = $("#tabstrip_ou_selector").val();
		if(org_unit==null || org_unit.length==0){
			document.getElementById("createTabstripResponse").innerHTML="<center><b>Oops! It seems  no organisation"+
				" unit exists for the selected organisation.</b></center>";
			document.getElementById("createTabstripResponse").style.color="red";
			return false;
		}
		var role = $("#tabstrip_role_selector").val();
		if(role==null || role.length==0){
			document.getElementById("createTabstripResponse").innerHTML="<center>It seems no role exists for the selected OU.</center>";
			document.getElementById("createTabstripResponse").style.color="red";
			return false;
		}
		var role_id = getRoleId(role,role_list);
		if(role_id==null){
			document.getElementById("createTabstripResponse").innerHTML="<center>It seems role ID is missing "+
				"for the selected role. Please refresh the page.</center>";
			document.getElementById("createTabstripResponse").style.color="red";
			return false;
		}
		var ou_specific = document.getElementById("tabstrip_ou_specific_yes").checked;
		$.ajax({
				type: "POST",
				url: "createTabstrip.php",
				data: {"tabstrip_name":tabstrip_name,"ou_specific":ou_specific,"org_name":org_name,
						"org_unit":org_unit,"role_id":role_id},
				success: function(resp){
					var resp_arr = JSON.parse(resp);
					if(resp_arr.status==true){
						document.getElementById("createTabstripResponse").innerHTML="<center><b>"+
							resp_arr.message+"</b></center>";
						document.getElementById("createTabstripResponse").style.color="green";	
						onChangeOU();	
					}
					else{
						document.getElementById("createTabstripResponse").innerHTML="<center>"+resp_arr.message+"</center>";
						document.getElementById("createTabstripResponse").style.color="red";
					}
				}
		});
	}
	
	/*function to validate file type*/
					function is_valid_file(file_name){
						var file_ext = file_name.substring(file_name.lastIndexOf('.') + 1).toLowerCase();
						var flag;
						switch(file_ext){
							case "txt":
							case "pdf":
							case "doc":
							case "csv":
							case "png":
							case "svg":
							case "rtf":
							case "mp4":
							case "jpg":
							case "ppt":
							case "jpeg":
							case "docx":
							case "pptx":
							case "html":
							case "xlsx": 
							case "json":flag=true;
										break;
							default: flag=false;
						}
						return flag;
					}
/*checks if email is valid or not*/
function isValidateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}



	
	

