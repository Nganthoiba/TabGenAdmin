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
					<a href="home.php">
						<button class='btn btn-success'>
							Back to home
						</button>
					</a>
				</li>
                <li>
					<a href="#" data-toggle="modal" data-target="#">Create Article</a>
				</li>

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<div class="container-fluid">
            <div class="box" id="tab_contents">	
				<script type='text/JavaScript'>
					var article_layout="";
					//alert(article_layout);
					for(var i=1;i<=30;i++){
						article_layout+=""+
						"<div class='article'>"+
							"<div class='headLine' id='article_title"+i+"'>"+
								"Article "+i+
								"<button type='button' style='float:right;background-color:#FA8686'"+ 
									"class='close' aria-label='Close' id='deleteArticle"+i+"'>"+
									"<span class='glyphicon glyphicon-remove'></span>"+
								"</button>"+	
							"</div>"+
							"<br/>"+
							"<div style='height:70%;padding:10px'>"+
								"<div id='textual_content"+i+"'>This the content of this article</div>"+
								"<div id='file_content"+i+"'></div>"+
								"<div id='link_content"+i+"'></div>"+
							"</div>"+
							"<br/>"+
							"<div  class='btn-group' style='float:right;padding-right:10px'>"+
								"<button class='btn btn-info'><span class='glyphicon glyphicon-paperclip'></span></button>"+
								"<button class='btn btn-info'><span class='glyphicon glyphicon-link'></span></button>"+
								"<button class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span></button>"+
							"</div>"+
						"</div>";
					}
					document.getElementById("tab_contents").innerHTML=article_layout;
				</script>
				
			</div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
	</body>
</html>
