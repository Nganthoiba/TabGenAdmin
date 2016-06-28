<!DOCTYPE html>
<html>
	<?php 
	
	?>
	<title>More About a Tab</title>
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
		$(document).ready(function(){
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		});
		
	</script>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#menu-toggle" id="menu-toggle">
				  <span class="glyphicon glyphicon-align-justify"></span>
			  </a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">  
				<li class="active">
					<a href="#">
					<?php 
						include('connect_db.php');
						include('tabgen_php_functions.php');
						$tab_id = $_GET['tab_id'];
						if($conn){
							$tab_details = getTabWithTemplate($conn,$tab_id);
							echo $tab_details['Name'];
						}
						else{
							echo "Failed to connect Database";
						}
						
					?> <span class="sr-only">(current)</span></a>
				</li>
				<script type="text/JavaScript">
					var queryString = new Array();
					var indicator;
					if (window.location.search.split('?').length > 1) {
						var params = window.location.search.split('?')[1].split('&');
						for (var i = 0; i < params.length; i++) {
							var key = params[i].split('=')[0];
							var value = decodeURIComponent(params[i].split('=')[1]);
							queryString[key] = value;
							//alert(key);
						}
					}	
					var tab_id=queryString['tab_id'];
					getArticles(tab_id);
					
					function getArticles(tab_id){
						//alert(tab_id);
						$.ajax({
							url: "getArticles.php?tab_id="+tab_id,
							type: "GET",
							success: function(resp){
								//alert(resp);
								var result = JSON.parse(resp);
								if(result.state==true){
									var output = result.output;
									if(output==null){
										document.getElementById("tab_contents").innerHTML="<center>No article found, create a new one.</center>";
										return false;
									}
									var article_layout="";
									for(var i=0;i<output.length;i++){
										var Link=output[i].Links;
										var image=output[i].Images;
										Link=Link.trim();
										var link_layout="";
										//image==null?"":
										var image_layout=image==null?"":"<div id='image_content"+i+"'><center><img src='"+image+
											"' height='200px' width='300px'/></center></div>";
										if(Link.trim()==0){
											link_layout="";
										}
										else{
											link_layout="<a href='"+output[i].Links+"' target='_blank'>"+output[i].Links+"</a>";
										}
										article_layout+=""+
											"<div class='article'>"+
												"<div class='headLine' id='article_title"+i+"'>"+
													output[i].Name+
													"<button type='button' style='float:right;'"+ 
														"class='close' aria-label='Close' id='deleteArticle"+i+"'>"+
														"<span class='glyphicon glyphicon-remove'></span>"+
													"</button>"+
													"<input type='hidden' id='article_id"+i+"' value='"+output[i].Id+"'/>"+	
												"</div>"+
												"<div style='height:70%;padding:10px'>"+
													"<div id='textual_content"+i+"'>"+output[i].Textual_content+
														"<button class='btn btn-link' style='float:right' onclick='editArticle(\""+i+"\");'>"+
														"<span class='glyphicon glyphicon-pencil'></span></button>"+
													"</div>"+
													"<div><input type='hidden' id='edit_text"+i+
														"' value='"+output[i].Textual_content+"'/></div>"+
													"<br/>"+
													"<div id='file_content"+i+"'></div>"+
													image_layout+
													"<div id='link_content"+i+"'>"+link_layout+"</div>"+
												"</div>"+
												"<br/>"+
												"<div id='btn_group"+i+"' class='btn-group' style='float:right;padding-right:5px;padding-bottom:5px'>"+
													"<button class='btn btn-info'><span class='glyphicon glyphicon-picture'></span></button>"+
													"<button class='btn btn-info'><span class='glyphicon glyphicon-paperclip'></span></button>"+
													"<a href='#' data-toggle='modal' data-target='#Editlink' "+
														"onclick='editLink(\""+i+"\",\""+Link+"\");'"+
														"class='btn btn-info'><span class='glyphicon glyphicon-link'></span></a>"+
												"</div>"+
											"</div>";
										document.getElementById("tab_contents").innerHTML=article_layout;
									}
								}
								else{
									document.getElementById("tab_contents").innerHTML="<center>"+result.message+"</center>";
								}
							}
						});
					}
				</script>
			  </ul>
			</div>
		  </div>
		</nav>
	<div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
			<center>
				<li class="active"><a href="#"><span class="sr-only">(current)</span></a></li>
			</center>
			
            <ul class="sidebar-nav">
				<br/>
				<li>
					<a href="#" onclick="window.history.back();">
						Back to home
					</a>
				</li>
                <li>
					<a href="#" data-toggle="modal" data-target="#createArticle">
						Create an article</span>
					</a>
				</li>

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			
			<div class="container-fluid">
            <div class="box" id="tab_contents">	
				<script type='text/JavaScript'>
					function editLink(i,article_link){
						indicator=i;
						var article_id = document.getElementById("article_id"+i).value;
						if(article_link.trim()==0 || article_link==null){
							document.getElementById("edit_link_label").innerHTML="Add a link for the article below:";
							document.getElementById("article_link").value="";
						}
						else{
							document.getElementById("edit_link_label").innerHTML="Edit the link below:";
							document.getElementById("article_link").value=article_link;
						}
						document.getElementById("article_ID").value=article_id;
						document.getElementById("editLinkResponse").innerHTML="";
					}
					function edit_link_done(){
						//alert(indicator);
						var article_id = document.getElementById("article_ID").value;
						var new_link=document.getElementById("article_link").value;
						$.ajax({
							url: "update_article.php",
							type: "POST",
							data: {"article_id":article_id,"Links":new_link},
							success: function(resp){
								var json_resp = JSON.parse(resp);
								if(json_resp.status==true){
									document.getElementById("link_content"+indicator).innerHTML="<a href='"+new_link+"' target='_blank'>"+new_link+"</a>";
									document.getElementById("editLinkResponse").innerHTML=json_resp.message;
									document.getElementById("editLinkResponse").style.color="green";
									document.getElementById("btn_group"+indicator).innerHTML="<button class='btn btn-info'><span class='glyphicon glyphicon-picture'></span></button>"+
													"<button class='btn btn-info'><span class='glyphicon glyphicon-paperclip'></span></button>"+
													"<a href='#' data-toggle='modal' data-target='#Editlink' "+
														"onclick='editLink(\""+indicator+"\",\""+new_link+"\");'"+
														"class='btn btn-info'><span class='glyphicon glyphicon-link'></span></a>";
								}
								else{
									document.getElementById("editLinkResponse").innerHTML=json_resp.message;
									document.getElementById("editLinkResponse").style.color="red";
								}
							},
							error: function(x,y,z){
								document.getElementById("editLinkResponse").innerHTML="<b>Oops! Unable to reach server.</b>";
								document.getElementById("editLinkResponse").style.color="red";
							}
						});	
					}
					function edit_link_cancel(){
						
					}
					function editArticle(i){
						var content = document.getElementById("edit_text"+i).value;
						//alert(content);
						document.getElementById("textual_content"+i).innerHTML="<div class='textarea_bg'>"+
							"<div class='div_bg'><label for='edited_text"+i+"'>Edit the content:</label>"+
							"<textarea class='form-control' rows='6' cols='50' id='edited_text"+i+"'>"+content+"</textarea></div></div>"+
							"<div class='textarea_bg' style='float:right'><input type='button' value='CANCEL' class='btn btn-primary' "+
								"onclick='cancel_edit(\""+i+"\");'/>&nbsp;"+
							"<input type='button' value='DONE' class='btn btn-primary' onclick='done_edit(\""+i+"\");'/></div><br/><br/>";
					}
					
					function cancel_edit(i){
						//alert(i);
						var content = document.getElementById("edit_text"+i).value;
						document.getElementById("textual_content"+i).innerHTML=content+
							"<button class='btn btn-link' style='float:right' onclick='editArticle(\""+i+"\");'>"+
								"<span class='glyphicon glyphicon-pencil'></span></button>";
					}
					
					function done_edit(i){
						//alert(i);
						var content = document.getElementById("edited_text"+i).value;
						var article_id = document.getElementById("article_id"+i).value;
						$.ajax({
							url: "update_article.php",
							type: "POST",
							data: {"article_id":article_id,"textual_content":content},
							success: function(resp){
								var resp_json = JSON.parse(resp);
								if(resp_json.status==true){
									document.getElementById("textual_content"+i).innerHTML=content+
										"<button class='btn btn-link' style='float:right' onclick='editArticle(\""+i+"\");'>"+
											"<span class='glyphicon glyphicon-pencil'></span></button>";
									document.getElementById("edit_text"+i).value=content;
								}
								else{
									alert(resp_json.message);
									cancel_edit(i);
								}
							},
							error: function(x,y,z){
								alert("Unable to connect server...");
							}
						});
						
					}
					
					function createArticle(){
						var tab_id = queryString['tab_id'];
						//alert("Tab Id: "+tab_id);
						var name = document.getElementById("title").value;
						var textual_content = document.getElementById("textual_content").value;
						var link_site = document.getElementById("link").value;
						name=name.trim();
						textual_content=textual_content.trim();
						if(name==null || name.length==0){
							document.getElementById("createArticleResp").innerHTML="<center><b>Please fill up article name.</b></center>";
							document.getElementById("createArticleResp").style.color="red";
							return false;
						}
						else if(textual_content==null || textual_content.length==0){
							document.getElementById("createArticleResp").innerHTML="<center><b>Please write something"+
							" about the article.</b></center>";
							document.getElementById("createArticleResp").style.color="red";
							return false;
						}
						else{
							var post_data = {"tab_id":tab_id,"Name":name,"textual_content":textual_content,"link":link_site};
							//alert(JSON.stringify(post_data));
							$.ajax({
								type: "POST",
								url: "createArticle.php",
								data: post_data,
								success: function(resp){
									var result = JSON.parse(resp);
									document.getElementById("createArticleResp").innerHTML="<center><b>"+result.message+
									"</b></center>";
									if(result.status==true){
										document.getElementById("createArticleResp").style.color="green";
										getArticles(tab_id);
									}
									else{
										document.getElementById("createArticleResp").style.color="red";
									}
								}
							});
						}
						return false;
					}
					
					function updateArticle(){
						
					}
					
				</script>
				
			</div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
	</body>
	<!-- Modal for creating article -->
	<div class="modal fade" id="createArticle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Create an article:</h4>
				</div>
				<div class="modal-body">
				  <form class="form-horizontal" role="form" method="post" action="#">
					 <div class="form-group">	
							<div class="col-sm-12">
								<label for="title" class="control-label">Name:</label>
								<input type="text" class="form-control" value="" name="title" id="title"
										placeholder="Name of the article">
							</div>

							<div class="col-sm-12">
								<label for="textual_content" class="control-label">Say something about the article:</label>
								<textarea class="form-control" name="textual_content" id="textual_content" rows="6" cols="40"></textarea>
							</div>
						
						
							<div class="col-sm-12">
								<label for="link" class="control-label">Link:</label>
								<input type="text" class="form-control" value="" name="link" id="link"
										placeholder="Paste here any link related to the article">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<center><label id="createArticleResp" class="col-sm-offset-2 col-sm-8"></label></center>
					<button type="submit" onclick='createArticle();' class="btn btn-info">Create</button>
				</div>
			 </div>
		</div>
	</div>
	<div class="modal fade" id="Editlink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<input type='hidden' id='article_ID'/>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<label for='article_link' id='edit_link_label'>Edit the link below:</label>
					<input type='text' class='form-control' id='article_link' placeholder='http://www.domain.com'/>
					<br/>
					<div>
						<center>
							<button type="button" style='width:45%' onclick='edit_link_cancel();' 
								class="btn" data-dismiss="modal" aria-label="Close">CANCEL</button>
							&nbsp;&nbsp;
							<button type="button"  style='width:45%' onclick='edit_link_done();' 
								class="btn" style="width:20%" id="saveLink">
								DONE</button>
						</center>
					</div>
					<br/>
					<center>
						<div id='editLinkResponse'></div>
					</center>
					
				</div>	
			</div>
		</div>
	</div>
</html>
