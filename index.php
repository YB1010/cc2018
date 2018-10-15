<! DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script
	  src="https://code.jquery.com/jquery-3.1.1.min.js"
	  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
	  crossorigin="anonymous"></script>
	<script src="semantic/dist/semantic.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Weather on Calendar</title>
</head>
<body>
<div class="land_background">
<div id="wrapper" style="margin-left:auto;margin-right:auto;margin-top:auto;width:1024;">
<div id="nav" class="ui huge menu">
  <a class="active item">
    Upcomming Events
  </a>

  <div class="right menu">

    <a href="logout.php" class="ui item" id="LogOut">
      Logout
    </a>
  </div> 
</div>
	<?php

  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: userlogin.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: userlogin.php");
  }

	require_once('./login.php');
	$client = getclient(); // Call getclient in login.php to login
	
	//if  user haven't linked to Google calendar
	if(! is_a($client,"Google_Client")){
		echo $client;
	}
	
	//if linked
	else { ?>


<div id="dump" style="width:99%;margin-left:auto;margin-right:auto;">
<div class="ui active inverted dimmer">
    <div class="ui huge text loader">Loading</div>
  </div>


</div>



		<script>
			console.log("From server 2");
			$(document).ready(function(){
				
					get_times(this);
				

			});
			
			function get_times(date_picker){
			
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){
						document.getElementById('dump').innerHTML = xhttp.responseText;
						
					}
				};
				// call the calendars.php to render the event details as html.
				xhttp.open('GET','calendars.php?action=get_times');
				xhttp.setRequestHeader('X-Requested-With','xmlhttprequest');
				xhttp.send();
			}

		</script>
	<?php }
	?>
	
</div>
</div>
</body>
</html>
